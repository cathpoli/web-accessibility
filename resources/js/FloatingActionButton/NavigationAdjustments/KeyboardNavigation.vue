<script setup>
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'
import { isMobile } from '@/FloatingActionButton/helpers'

const accessibility = useAccessibilityStore()

const textElementTags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'a', 'button', 'p', 'span', 'small', 'label', 'th', 'td']
var textNodes = [] /** nodeList; https://developer.mozilla.org/en-US/docs/Web/API/NodeList */
var currentTextNode = null /** node; value should only be assigned on playUtterance to avoid confusion */
var currentTextNodeIndex = null /** integer */
var selectedCustomKey = false
var totalNodes = null

window.addEventListener('load', () => {
    /** get all textNodes; may have to move this depending on issues */
    if (! isMobile()) {        
        if (accessibility.navigation.keyboardNavigation) {
            selectedCustomKey = false
            setCurrentNode()
            keyboardNavigation()
        }
    }
    
})

accessibility.$subscribe((mutation, state) => {
    /**
     * listen for any changes for smartNavigation and keyboardNavigation;
     * if one is set to true, the other should be set to false (handled in Accessibility.js file)
     */
    if (! isMobile()) {        
        if (state.navigation.keyboardNavigation) {
            setCurrentNode()
            keyboardNavigation()
        } else {
            const elements = document.getElementsByClassName('aceada-keyboard-navigation')
    
            for (let index = 0; index < elements.length; index++) {
                elements[index].classList.remove('aceada-keyboard-navigation')
            }
        }
    }
})

const DOMEvents = ['load','mousemove', 'keypress', 'wheel', 'touchstart']
var pushState = history.pushState;
let prevUrl = undefined;
history.pushState = function () {
    pushState.apply(history, arguments);
    if (accessibility.navigation.keyboardNavigation) {
        DOMEvents.forEach((event) => {
            window.addEventListener(event, function () {
                const currUrl = window.location.href;
                if (currUrl != prevUrl) {
                    // URL changed
                    prevUrl = currUrl;
                    if (accessibility.navigation.keyboardNavigation) {
                        selectedCustomKey = false
                        currentTextNodeIndex = null // reset
                        setCurrentNode()
                        keyboardNavigation()
                    }
                }
            })
        })
    }
};

const getTextNodes = () => {
    textNodes = document.querySelectorAll(textElementTags.join(':not(#aceada *), '))
}

const showActiveTextNodeUtterance = () => {
    const elements = document.getElementsByClassName('aceada-keyboard-navigation')

    for (let index = 0; index < elements.length; index++) {
        elements[index].classList.remove('aceada-keyboard-navigation')
    }

    // add border to current textNode
    if (selectedCustomKey) {
        currentTextNode[currentTextNodeIndex === null ? 0 : currentTextNodeIndex].classList.add('aceada-keyboard-navigation')
    } else {
        currentTextNode.classList.add('aceada-keyboard-navigation')
    }
}

const setCurrentNode = (action = null) => {

    var notLastNode = true

    // Check if the current node reached the first and last element
    if (currentTextNodeIndex !== null &&  totalNodes !== null) {
        if (currentTextNodeIndex > totalNodes) {
            notLastNode = false
            currentTextNodeIndex = totalNodes
            alert("You've reached the last element");
        } else if (currentTextNodeIndex < 0) {
            notLastNode = false
            currentTextNodeIndex = 0
            alert("You've reached the first element");
        }
    }

    if (notLastNode) {

        /** just to make sure textNodes is not empty */
        if (textNodes.length < 1 || currentTextNodeIndex == 0 || currentTextNodeIndex == null) getTextNodes()

        /** currentTextNode should only set assigned here */
        if (selectedCustomKey) {
            currentTextNode = currentTextNode
            totalNodes = (currentTextNode.length) - 1;
        } else {
            currentTextNode = textNodes[currentTextNodeIndex === null ? 0 : currentTextNodeIndex]
            var num = document.querySelectorAll(textElementTags.join(':not(#aceada *), '))
            totalNodes = (num.length) - 1;
        }

        /** check element visibility */
        /** check if the action is previous or next so it will run the correct method */
        if (currentTextNode.offsetWidth == 0 || currentTextNode.offsetHeight == 0) {

            if (action == "previous") {
                previousNode()
            } else {
                nextNode()
            }

            return;
        }

        /** check if currentTextNode has a childNode that has the same textContent; if so proceed to next node */
        /** check if the action is previous or next so it will run the correct method */
        /** TODO: this only current checks first child, for input checkboxes such as remember me on login this doesn't work */
        if (currentTextNode.firstElementChild && currentTextNode.textContent == currentTextNode.firstElementChild.textContent) {

            if (action == "previous") {
                previousNode()
            } else {
                nextNode()
            }

            return;
        }

        // Get all the input elements on the web page
        const inputs = document.getElementsByTagName("input");

        // Loop through the input elements and do something with each one
        for (let i = 0; i < inputs.length; i++) {
            inputs[i].blur()
        }

        showActiveTextNodeUtterance()
    }

}

const nextNode = () => {
    currentTextNodeIndex = currentTextNodeIndex === null ? 0 : currentTextNodeIndex + 1
    setCurrentNode()
}

const previousNode = () => {
    currentTextNodeIndex = currentTextNodeIndex === null ? 0 : currentTextNodeIndex - 1
    setCurrentNode("previous")
}

const keyboardNavigation = () => {
    // ARROW NEXT - select the next node
    document.addEventListener('keydown', function(event) {
        if (event.keyCode === 39 || event.key === 'ArrowRight') {
            nextNode()
        }
    });

    // ARROW LEFT - select the previous node
    document.addEventListener('keydown', function(event) {
        if (event.keyCode === 37 || event.key === 'ArrowLeft') {
            previousNode()
        }
    });

    // SHIFT + M - select the menu tab
    document.addEventListener("keydown", function(event) {
        if (event.keyCode === 77 && event.shiftKey) {
            selectedCustomKey = true
            const navElement = document.querySelector("nav")
            const ulElement = navElement.querySelector("ul")

            if (ulElement == null) {
                const anchorElements = navElement.querySelectorAll("a");
                currentTextNode = anchorElements
            } else {
                const liElements = ulElement.querySelectorAll("li");
                currentTextNode = liElements
            }

            currentTextNodeIndex = 0
            setCurrentNode()
        }
    });

    // SHIFT + H - select the headings
    document.addEventListener("keydown", function(event) {
        if (event.key === "H" && event.shiftKey) {
            selectedCustomKey = true
            const headings = document.querySelectorAll("h1, h2, h3, h4, h5, h6");
            currentTextNode = headings
            currentTextNodeIndex = 0
            setCurrentNode()
        }
    });

    // SHIFT + G - select the images and videos
    document.addEventListener("keydown", function(event) {
        if (event.key === "G" && event.shiftKey) {
            selectedCustomKey = true
            const mediaElements = document.querySelectorAll("img:not(.image-animation), video, iframe");
            currentTextNode = mediaElements
            currentTextNodeIndex = 0
            setCurrentNode()
        }
    });

    // SHIFT + F - select the forms
    document.addEventListener("keydown", function(event) {
        if (event.key === "F" && event.shiftKey) {
            selectedCustomKey = true
            const formElements = document.querySelectorAll("form:not(.aceada-form)");
            currentTextNode = formElements
            currentTextNodeIndex = 0
            setCurrentNode()
        }
    });

    // SHIFT + F - select the buttons
    document.addEventListener("keydown", function(event) {
        if (event.key === "B" && event.shiftKey) {
            selectedCustomKey = true
            const buttonElements = document.querySelectorAll("button:not(.aceada-btn)");
            currentTextNode = buttonElements
            currentTextNodeIndex = 0
            setCurrentNode()
        }
    });

    // Enter command
    document.addEventListener("keydown", function(event) {
        if (event.keyCode === 13 || event.which === 13) {

            // If the element is an anchor tag
            if (currentTextNode.tagName === 'A') {
                const href = currentTextNode.getAttribute("href");
                window.location.href = href;
            }

            // If the element is button
            if (currentTextNode[currentTextNodeIndex === null ? 0 : currentTextNodeIndex].tagName === "BUTTON") {
                currentTextNode[currentTextNodeIndex === null ? 0 : currentTextNodeIndex].click();
            }

            // If the element is iFrame
            if (currentTextNode[currentTextNodeIndex === null ? 0 : currentTextNodeIndex].tagName === "IFRAME") {
                currentTextNode[currentTextNodeIndex === null ? 0 : currentTextNodeIndex].contentWindow.postMessage('{"event":"command","func":"playVideo","args":""}', '*');
            }

            // If the element is video
            if (currentTextNode[currentTextNodeIndex === null ? 0 : currentTextNodeIndex].tagName === "VIDEO") {
                currentTextNode[currentTextNodeIndex === null ? 0 : currentTextNodeIndex].play();
            }
        }
    });

    // ESC command
    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            currentTextNodeIndex = null
            selectedCustomKey = false
            setCurrentNode()
        }
    });

    // SHIFT + I - focus on input
    document.addEventListener('keydown', function(event) {
        if (event.shiftKey && event.key === "I") {
            currentTextNodeIndex = null
            selectedCustomKey = false
            const inputs = document.querySelectorAll('input[type="text"], input[type="email"]');
            inputs[0].focus()
        }
    });
}

</script>

<template>
    <button id="aceada-keyboard-navigation" class="aceada-adjustment-button" :class="{ 'aceada-adjustment-button-active': accessibility.navigation.keyboardNavigation }" @click="accessibility.toggleKeyboardNavigation()" v-if="! isMobile()">
        <div class="aceada-adjustment-icon">
            <svg width="46" height="22" viewBox="0 0 46 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M15.1742 10.8696L7.49048 10.8696M10.8804 6.95652L7.00621 10.8696L10.8804 14.7826M30.8258 10.8696L38.5095 10.8696M35.1196 14.7826L38.9938 10.8696L35.1196 6.95652M20.1805 18.6957L20.1805 3.30435C20.1805 2.58398 19.5906 2 18.863 2L3.31742 2C2.58983 2 2 2.58398 2 3.30435L2 18.6957C2 19.416 2.58983 20 3.31742 20L18.863 20C19.5906 20 20.1805 19.416 20.1805 18.6957ZM44 18.6957L44 3.30435C44 2.58397 43.4102 2 42.6826 2L27.137 2C26.4094 2 25.8195 2.58398 25.8195 3.30435L25.8195 18.6957C25.8195 19.416 26.4094 20 27.137 20L42.6826 20C43.4102 20 44 19.416 44 18.6957Z" stroke="#011817" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <p class="aceada-adjustment-title">Keyboard Navigation</p>
    </button>
</template>
