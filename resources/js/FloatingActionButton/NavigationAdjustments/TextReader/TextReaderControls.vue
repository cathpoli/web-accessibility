<script setup>
import { onMounted } from 'vue';
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'
import { isElementVisible } from '../../helpers'

/** TODO: change voice, rate, pitch, volume, etc.. */

/**
 * Refereneces:
 * https://www.section.io/engineering-education/text-to-speech-in-javascript/
 * https://developer.mozilla.org/en-US/docs/Web/API/SpeechSynthesis
 * https://developer.mozilla.org/en-US/docs/Web/API/SpeechSynthesisUtterance
 */

const textElementTags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'a', 'button', 'p', 'span', 'small', 'label', 'th', 'td', 'em', 'strong', 'div']
var textNodes = [] /** nodeList; https://developer.mozilla.org/en-US/docs/Web/API/NodeList */

const synth = window.speechSynthesis;
const speech = new SpeechSynthesisUtterance();
const accessibility = useAccessibilityStore()

var currentTextNode = null /** node; value should only be assigned on playUtterance to avoid confusion */
var currentTextNodeIndex = null /** integer */
var notMuted = true;

// set language
// speech.lang = "en";

const voices = synth.getVoices();
// const changeVoice = voice => speech.voice = voice

/** Start: Utterance events */
speech.onstart = (event) => {
    /** Change the play icon to pause icon */
    document.getElementById("aceada-text-reader-pause-icon").style.display = 'block';
    document.getElementById("aceada-text-reader-play-icon").style.display = 'none';

    showActiveTextNodeUtterance()

    /** make sure current utterance is visible to screen */
    window.scroll({
        top: (currentTextNode.parentElement.getBoundingClientRect().top + window.scrollY) - (screen.height / 2),
        behavior: 'smooth'
    });
}

speech.onend = (event) => {
    /** Check if it is done speaking then change the icon */
    document.getElementById("aceada-text-reader-pause-icon").style.display = 'none';
    document.getElementById("aceada-text-reader-play-icon").style.display = 'block';

    /** increment textNode index if continuous reading is enabled */
    if (accessibility.navigation.textReaderContinuous) {
        currentTextNodeIndex += 1;

        playUtterance()
    }
}
/** End: Utterance events */

// Watch for state changes: https://pinia.vuejs.org/core-concepts/state.html#subscribing-to-the-state
accessibility.$subscribe((mutation, state) => {
    const isTextReaderActivated = state.navigation.textReader

    if (isTextReaderActivated) {
        getTextNodes()
        document.addEventListener('click', speakClickedText)
    } else {
        document.removeEventListener('click', speakClickedText)
    }
})

onMounted(() => {
    /** get all textNodes; may have to move this depending on issues */
    // textNodes = document.querySelectorAll(textElementTags.join(':not(#aceada *), '))

    if (accessibility.navigation.textReader) {
        document.addEventListener('click', speakClickedText)
        currentTextNodeIndex = null // reset
        getTextNodes()

        if (accessibility.navigation.textReaderContinuous) {
            document.getElementById('aceada-text-reader-continuous').classList.add('active')
        }
    } else {
        document.removeEventListener('click', speakClickedText)
    }
});

window.addEventListener('load', () => {
    /** get all textNodes; may have to move this depending on issues */
    // textNodes = document.querySelectorAll(textElementTags.join(':not(#aceada *), '))

    if (accessibility.navigation.textReader) {
        document.addEventListener('click', speakClickedText)
        currentTextNodeIndex = null // reset
        getTextNodes()

        if (accessibility.navigation.textReaderContinuous) {
            document.getElementById('aceada-text-reader-continuous').classList.add('active')
        }
    } else {
        document.removeEventListener('click', speakClickedText)
    }
})

var pushState = history.pushState;
let prevUrl = undefined;
history.pushState = function () {
    pushState.apply(history, arguments);

    if (accessibility.navigation.textReader) {
        /** get all textNodes; may have to move this depending on issues */
        // textNodes = document.querySelectorAll(textElementTags.join(':not(#aceada *), '))

        // document.addEventListener('click', speakClickedText)
        currentTextNodeIndex = null // reset
        getTextNodes()

        if (accessibility.navigation.textReaderContinuous) {
            document.getElementById('aceada-text-reader-continuous').classList.add('active')
        }
    }
};

const getTextNodes = () => {
    // const selectors = textElementTags.join(':not(#aceada *), ')
    // textNodes = [...document.querySelectorAll(selectors)].filter(node => isElementVisible(node))
    // textNodes = Array.from(document.querySelectorAll(textElementTags.join(':not(#aceada *), ')))

    // textNodes = textNodes.filter((node) => node.offsetHeight > 0 || node.offsetWidth > 0)

    // https://stackoverflow.com/a/10730777
    var n, walk = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT, null, false);
    textNodes = []

    while (n = walk.nextNode()) {
        if ((n.textContent).replace(/(\r\n|\n|\r)/gm, "").trim() === '') continue
        if (n.parentElement.closest('#aceada')) continue

        if (isElementVisible(n.parentElement) && textElementTags.includes((n.parentElement.tagName).toLowerCase())) {
            textNodes.push(n)
        }
    };
}

const speakClickedText = (e) => {
    const element = e.target
    const isAceadaElement = element.closest('#aceada')
    const tagName = (element.tagName).toLowerCase()

    /** Validate if clicked element is a textNode and is outside of aceada popup */
    if (! isAceadaElement && textElementTags.includes(tagName)) {
        // Note: fix for when textReader is not working when page changes
        getTextNodes()

        /** set current textNode for continuous reading */
        currentTextNodeIndex = Array.from(textNodes).findIndex(node => node.parentElement.isEqualNode(element))

        if (currentTextNodeIndex != -1) {
            playUtterance()
        }
    }
}

const showActiveTextNodeUtterance = () => {
    const elements = document.getElementsByClassName('aceada-text-reader')

    for (let index = 0; index < elements.length; index++) {
        elements[index].classList.remove('aceada-text-reader')
    }

    // add border to current textNode
    currentTextNode.parentElement.classList.add('aceada-text-reader')
}

const closeTextReaderPopup = () => document.getElementById("aceada-text-reader-pop-up").style.display = "none"

const toggleContinuousReading = () => {
    if (synth.speaking) {
        document.getElementById("aceada-text-reader-pause-icon").style.display = 'none';
        document.getElementById("aceada-text-reader-play-icon").style.display = 'block';
    }

    if (accessibility.navigation.textReaderContinuous) {
        accessibility.setTextReaderContinuous(false);
        synth.cancel();
        document.getElementById('aceada-text-reader-continuous').classList.remove('active')
        return;
    }

    accessibility.setTextReaderContinuous(true)
    document.getElementById('aceada-text-reader-continuous').classList.add('active')
    // playUtterance()
}

const disableUtterance = () => {
    if (notMuted) {
        notMuted = false;
        synth.cancel()
        document.getElementById("aceada-text-reader-muted").style.display = 'block';
        document.getElementById("aceada-text-reader-unmuted").style.display = 'none';
        return;
    }

    notMuted = true
    document.getElementById("aceada-text-reader-muted").style.display = 'none';
    document.getElementById("aceada-text-reader-unmuted").style.display = 'block';
    playUtterance()
}

const pauseUtterance = () => {
    if (synth.speaking) {
        document.getElementById("aceada-text-reader-pause-icon").style.display = 'none';
        document.getElementById("aceada-text-reader-play-icon").style.display = 'block';
        synth.pause()
    }
}

const playUtterance = (action = null) => {
    /** play paused utterance */
    if (synth.paused) {
        synth.resume()
        document.getElementById("aceada-text-reader-pause-icon").style.display = 'block';
        document.getElementById("aceada-text-reader-play-icon").style.display = 'none';
        return;
    }

    /** stop current utterance if speaking or queue is not empty  */
    if (synth.speaking) synth.cancel()

    /** just to make sure textNodes is not empty */
    // if (textNodes.length) {
    //     textNodes = []
    //     getTextNodes()
    // }

    // if (currentTextNodeIndex == 0 || currentTextNodeIndex == null) getTextNodes()

    /** currentTextNode should only set assigned here */
    currentTextNode = textNodes[currentTextNodeIndex === null || currentTextNodeIndex <= 0 ? 0 : currentTextNodeIndex]

    /** check element visibility */
    /** check if the action is previous or next so it will run the correct method */
    // if (currentTextNode.offsetWidth == 0 || currentTextNode.offsetHeight == 0) {
    if (! isElementVisible(currentTextNode.parentElement)) {

        if (action == "previous") {
            previousUtterance()
        } else {
            nextUtterance()
        }

        return;
    }

    /** check if currentTextNode has a childNode that has the same textContent; if so proceed to next node */
    /** check if the action is previous or next so it will run the correct method */
    /** TODO: this only current checks first child, for input checkboxes such as remember me on login this doesn't work */
    if (currentTextNode.parentElement.firstElementChild && currentTextNode.textContent == currentTextNode.parentElement.firstElementChild.textContent) {

        if (action == "previous") {
            previousUtterance()
        } else {
            nextUtterance()
        }

        return;
    }

    /** cancel the speech if it is muted */
    if (notMuted) {
        speech.text = currentTextNode.textContent
        synth.speak(speech);
    }
}

const previousUtterance = () => {
    currentTextNodeIndex = currentTextNodeIndex === null ? 0 : currentTextNodeIndex - 1

    if (currentTextNodeIndex < 0) {
        currentTextNodeIndex = null
        return
    }

    playUtterance("previous")
}

const nextUtterance = () => {
    currentTextNodeIndex = currentTextNodeIndex === null ? 0 : currentTextNodeIndex + 1

    if (currentTextNodeIndex < textNodes.length) {
        playUtterance()
    }
}

const restartUtterance = () => {
    currentTextNodeIndex = 0

    playUtterance()
}
</script>

<template>
    <!-- Text Reader Pop-up -->
    <div id="aceada-text-reader-pop-up" :style="{ display: accessibility.navigation.textReader ? 'block' : 'none' }">
        <div id="aceada-text-reader-pop-up-minimize" role="button" @click="closeTextReaderPopup">
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-dash" viewBox="0 0 16 16">
                    <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z"/>
                </svg>
            </span>
        </div>
        <div class="aceada-text-reader-pop-up-icons">
            <!-- AUDIO ICON -->
            <div role="button" id="aceada-disable-text-reader-controls" @click="disableUtterance">
                <span id="aceada-text-reader-unmuted">
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-volume-up" viewBox="0 0 16 16">
                        <path d="M11.536 14.01A8.473 8.473 0 0 0 14.026 8a8.473 8.473 0 0 0-2.49-6.01l-.708.707A7.476 7.476 0 0 1 13.025 8c0 2.071-.84 3.946-2.197 5.303l.708.707z"/>
                        <path d="M10.121 12.596A6.48 6.48 0 0 0 12.025 8a6.48 6.48 0 0 0-1.904-4.596l-.707.707A5.483 5.483 0 0 1 11.025 8a5.483 5.483 0 0 1-1.61 3.89l.706.706z"/>
                        <path d="M10.025 8a4.486 4.486 0 0 1-1.318 3.182L8 10.475A3.489 3.489 0 0 0 9.025 8c0-.966-.392-1.841-1.025-2.475l.707-.707A4.486 4.486 0 0 1 10.025 8zM7 4a.5.5 0 0 0-.812-.39L3.825 5.5H1.5A.5.5 0 0 0 1 6v4a.5.5 0 0 0 .5.5h2.325l2.363 1.89A.5.5 0 0 0 7 12V4zM4.312 6.39 6 5.04v5.92L4.312 9.61A.5.5 0 0 0 4 9.5H2v-3h2a.5.5 0 0 0 .312-.11z"/>
                    </svg>
                </span>
                <span id="aceada-text-reader-muted">
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-volume-mute" viewBox="0 0 16 16">
                        <path d="M6.717 3.55A.5.5 0 0 1 7 4v8a.5.5 0 0 1-.812.39L3.825 10.5H1.5A.5.5 0 0 1 1 10V6a.5.5 0 0 1 .5-.5h2.325l2.363-1.89a.5.5 0 0 1 .529-.06zM6 5.04 4.312 6.39A.5.5 0 0 1 4 6.5H2v3h2a.5.5 0 0 1 .312.11L6 10.96V5.04zm7.854.606a.5.5 0 0 1 0 .708L12.207 8l1.647 1.646a.5.5 0 0 1-.708.708L11.5 8.707l-1.646 1.647a.5.5 0 0 1-.708-.708L10.793 8 9.146 6.354a.5.5 0 1 1 .708-.708L11.5 7.293l1.646-1.647a.5.5 0 0 1 .708 0z"/>
                    </svg>
                </span>
            </div>

            <!-- PREVIOUS ICON -->
            <div role="button" id="aceada-text-reader-previous" @click="previousUtterance">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-skip-start-fill" viewBox="0 0 16 16">
                    <path d="M4 4a.5.5 0 0 1 1 0v3.248l6.267-3.636c.54-.313 1.232.066 1.232.696v7.384c0 .63-.692 1.01-1.232.697L5 8.753V12a.5.5 0 0 1-1 0V4z"/>
                </svg>
            </div>

            <!-- PLAY ICON -->
            <div role="button" id="aceada-text-reader-play">
                <span id="aceada-text-reader-play-icon" @click="playUtterance">
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-play-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M6.271 5.055a.5.5 0 0 1 .52.038l3.5 2.5a.5.5 0 0 1 0 .814l-3.5 2.5A.5.5 0 0 1 6 10.5v-5a.5.5 0 0 1 .271-.445z"/>
                    </svg>
                </span>
                <span id="aceada-text-reader-pause-icon" @click="pauseUtterance">
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-pause-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M5 6.25a1.25 1.25 0 1 1 2.5 0v3.5a1.25 1.25 0 1 1-2.5 0v-3.5zm3.5 0a1.25 1.25 0 1 1 2.5 0v3.5a1.25 1.25 0 1 1-2.5 0v-3.5z"/>
                    </svg>
                </span>
            </div>

            <!-- NEXT ICON -->
            <div role="button" id="aceada-text-reader-next" @click="nextUtterance">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-skip-end-fill" viewBox="0 0 16 16">
                    <path d="M12.5 4a.5.5 0 0 0-1 0v3.248L5.233 3.612C4.693 3.3 4 3.678 4 4.308v7.384c0 .63.692 1.01 1.233.697L11.5 8.753V12a.5.5 0 0 0 1 0V4z"/>
                </svg>
            </div>

            <!-- CONTINUOUS ICON -->
            <div role="button" id="aceada-text-reader-continuous" @click="toggleContinuousReading">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-file-text" viewBox="0 0 16 16" :style="{ fill: accessibility.navigation.textReaderContinuous ? '#1AD2C7' : '' }">
                    <path d="M5 4a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm-.5 2.5A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zM5 8a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1H5z"/> <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1z"/>
                </svg>
            </div>

            <!-- RESTART ICON -->
            <div role="button" id="aceada-text-reader-restart" @click="restartUtterance">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-arrow-counterclockwise" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z"/>
                    <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z"/>
                </svg>
            </div>

            <!-- SETTINGS ICON -->
            <!-- <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-gear-fill" viewBox="0 0 16 16">
                    <path d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 1 0-5.86 2.929 2.929 0 0 1 0 5.858z"/>
                </svg>
            </div> -->
        </div>
    </div>
</template>
