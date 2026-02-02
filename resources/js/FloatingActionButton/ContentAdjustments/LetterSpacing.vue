<script setup>
import { ref, onMounted } from 'vue';
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'

const accessibility = useAccessibilityStore()
const letterSpacingAdjustmentValue = 0.2;
const tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'a', 'span', 'li', 'table', 'label', 'button', 'th', 'td', 'tr', 'input', 'div']
// const letterSpacingLabel = ref('Default')
const letterSpacingLabel = ref(0)

/**
 * Workaround:
 * For SPAs, the window is not reloading so we're gonna need to add some kind event listener for updating element letter spacing
 *
 * DOM Events: https://www.w3schools.com/jsref/dom_obj_event.asp
 */
const DOMEvents = ['load','mousemove', 'keypress', 'wheel', 'touchstart']

if (accessibility.content.letterSpacing) {
    DOMEvents.forEach((event) => {
        window.addEventListener(event, function () {
            //Set the letter spacing attribute on page load
            setLetterSpacingAttribute();

            adjustLetterSpacing(accessibility.content.letterSpacing, null)
            letterSpacingLabel.value = accessibility.content.letterSpacing * 10
        })
    })
}

onMounted(() => {
    //Set the letter spacing attribute on page load
    setLetterSpacingAttribute();

    const rangeInput = document.getElementById('aceada-letter-spacing-range');

    rangeInput.addEventListener('change', (event) => {
        adjustLetterSpacing(event.target.value, null)
    })
});

accessibility.$subscribe((mutation, state) => {
    //Set the letter spacing attribute on page load
    setLetterSpacingAttribute();

    adjustLetterSpacing(state.content.letterSpacing)
})

const increaseLetterSpacing = () => {
    const newLetterSpacing = parseInt(accessibility.content.letterSpacing) + 1

    adjustLetterSpacing(newLetterSpacing, 'increase')
}

const decreaseLetterSpacing = () => {
    const newLetterSpacing = parseInt(accessibility.content.letterSpacing) - 1

    adjustLetterSpacing(newLetterSpacing, 'decrease')
}

const setLetterSpacingAttribute = () => {
    tags.forEach(tag => {
        var elements = document.querySelectorAll(`${tag}:not(#aceada)`);
       
        for(let i = 0; i < elements.length; i++) {
            var currentSize = window.getComputedStyle(elements[i], null).getPropertyValue('letter-spacing');
            elements[i].setAttribute('data-original-letter-spacing-size', currentSize);
        }
    });
}

const adjustLetterSpacing = (spaceAdjustment, mode) => {
    /** set text nodes' letter spacing style */
    tags.forEach(tag => {
        var elements = document.querySelectorAll(`${tag}:not(#aceada *)`);

        for(let i = 0; i < elements.length; i++) {
            // make sure element is not inside the widget
            //if (elements[i].closest('#aceada')) return;

            if (mode) {
                /** use element current letter spacing */
                var currentSpacing = elements[i].getAttribute('data-original-letter-spacing-size'); // get element current letter spacing value

                if (currentSpacing) {
                    currentSpacing = currentSpacing == 'normal' ? '0' : Number(currentSpacing.replace('px',''))
                    var letterSpacing = getNewLetterSpacing(mode, currentSpacing)
                }
            } else {
                var letterSpacing = `${parseFloat(spaceAdjustment * letterSpacingAdjustmentValue)}px`
            }

            /** set element letter spacing style value */
            elements[i].style.letterSpacing = letterSpacing
        }

        const aceadaDiv = document.getElementById('aceada');
        aceadaDiv.style.letterSpacing = '';
    });

    /** set input attribute and state */
    setStateAndInputAttributes(spaceAdjustment)
}

/** compute input attribute and state */
const setStateAndInputAttributes = (spaceAdjustment) => {
    // const spacingInput = document.getElementById('aceada-letter-spacing-range');

    /** set input attribute */
    // spacingInput.dataset.aceadaLetterSpacing = spaceAdjustment == 0 ? 'Default' : spaceAdjustment;

    /** set input value */
    // spacingInput.value = spaceAdjustment == 0 ? 'Default' : (spaceAdjustment * 10) + '%';

    /** set state */
    accessibility.setLetterSpacing(spaceAdjustment);

    // letterSpacingLabel.value = spaceAdjustment == 0 || spaceAdjustment == null ? 'Default' : (spaceAdjustment * 10) + '%'
    letterSpacingLabel.value = spaceAdjustment * 10

    /** remove event listener */
    if (spaceAdjustment === 0) {
        DOMEvents.forEach((event) => {
            window.removeEventListener(event, function () {
                adjustLetterSpacing(accessibility.content.letterSpacing, null)
            })
        })
    }
}

const getNewLetterSpacing = (mode, letterSpacing) => {
    /** Note: set element letter-spacing on onMounted */
    if (mode === 'increase') return `${parseFloat(letterSpacing + letterSpacingAdjustmentValue)}px`

    return `${parseFloat(letterSpacing - letterSpacingAdjustmentValue)}px`
}

</script>

<template>
    <div id="aceada-letter-spacing-adjustment">
        <div class="aceada-range-input">
            <p class="aceada-group-title">Letter Spacing</p>
            <input id="aceada-letter-spacing-range" name="aceada-letter-spacing-range" type="range" min="-10" max="10" step="1" v-model="accessibility.content.letterSpacing" />
        </div>
        <div class="aceada-button-group">
            <div class="aceada-range-value">
                <span>{{ letterSpacingLabel + '%' }}</span>
            </div>
            <div class="aceada-range-actions">
                <button id="aceada-increase-letter-spacing" class="aceada-increase-range" @click="increaseLetterSpacing()"  data-aceada-letter-spacing="increase">
                    <svg xmlns="http://www.w3.org/2000/svg" height="12" viewBox="0 0 320 512">
                        <path d="M182.6 137.4c-12.5-12.5-32.8-12.5-45.3 0l-128 128c-9.2 9.2-11.9 22.9-6.9 34.9s16.6 19.8 29.6 19.8H288c12.9 0 24.6-7.8 29.6-19.8s2.2-25.7-6.9-34.9l-128-128z"/>
                    </svg>
                </button>
                <button id="aceada-decrease-letter-spacing" class="aceada-decrease-range" @click="decreaseLetterSpacing()" data-aceada-letter-spacing="decrease">
                    <svg xmlns="http://www.w3.org/2000/svg" height="12" viewBox="0 0 320 512">
                        <path d="M137.4 374.6c12.5 12.5 32.8 12.5 45.3 0l128-128c9.2-9.2 11.9-22.9 6.9-34.9s-16.6-19.8-29.6-19.8L32 192c-12.9 0-24.6 7.8-29.6 19.8s-2.2 25.7 6.9 34.9l128 128z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>
