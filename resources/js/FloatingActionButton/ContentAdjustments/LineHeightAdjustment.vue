<script setup>
import { ref } from 'vue';
import { onMounted } from 'vue';
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'

const accessibility = useAccessibilityStore()
const tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'a', 'span', 'li', 'table', 'label', 'button', 'th', 'td', 'tr', 'input', 'div']
const lineHeightLabel = ref(0)
var defaultElements = []
var elArray = []

/**
 * Workaround:
 * For SPAs, the window is not reloading so we're gonna need to add some kind event listener for updating element letter spacing
 *
 * DOM Events: https://www.w3schools.com/jsref/dom_obj_event.asp
 */
const DOMEvents = ['load','mousemove', 'keypress', 'wheel', 'touchstart']
var pushState = history.pushState;
let prevUrl = undefined;
history.pushState = function () {
    pushState.apply(history, arguments);
    if (accessibility.content.lineHeightAdjustment) {
        DOMEvents.forEach((event) => {
            window.addEventListener(event, function () {
                const currUrl = window.location.href;
                if (currUrl != prevUrl) {
                    getDefaultLineHeight()
                    // URL changed
                    prevUrl = currUrl;
                    if (accessibility.content.lineHeightAdjustment) {
                        adjustLineHeight(accessibility.content.lineHeightAdjustment)
                        lineHeightLabel.value = (accessibility.content.lineHeightAdjustment * 10)
                    }
                }
            })
        })
    }
};

onMounted(() => {
    getDefaultLineHeight()
    if (accessibility.content.lineHeightAdjustment) {
        adjustLineHeight(accessibility.content.lineHeightAdjustment)
        lineHeightLabel.value = (accessibility.content.lineHeightAdjustment * 10)
    }

    const rangeInput = document.getElementById('aceada-line-height-range');

    // set event listener for input slider
    rangeInput.addEventListener('change', (event) => {
        const value = Number(event.target.value)
        accessibility.setLineHeightAdjustment(value)
        adjustLineHeight(value)
    })
});


window.addEventListener("load", (event) => {
    getDefaultLineHeight()
    if (accessibility.content.lineHeightAdjustment) {
        adjustLineHeight(accessibility.content.lineHeightAdjustment)
        lineHeightLabel.value = (accessibility.content.lineHeightAdjustment * 10)
    }

    const rangeInput = document.getElementById('aceada-line-height-range');

    // set event listener for input slider
    rangeInput.addEventListener('change', (event) => {
        const value = Number(event.target.value)
        accessibility.setLineHeightAdjustment(value)
        adjustLineHeight(value)
    })
});

accessibility.$subscribe((mutation, state) => {
    adjustLineHeight(state.content.lineHeightAdjustment)
})

const getDefaultLineHeight = () => {
    tags.forEach(tag => {
        var elements = document.querySelectorAll(`${tag}:not(#aceada *)`);

        for(let i = 0; i < elements.length; i++) {
            /** get element default letter spacing */
            var currentHeight = window.getComputedStyle(elements[i], null).getPropertyValue('line-height');
            // elArray.push(elVal)
            // defaultElements.push(elements[i])
            elements[i].setAttribute('data-original-line-height', currentHeight);
        }
    });
}

const increaseLineHeight = () => {
    const newLineHeight = parseInt(accessibility.content.lineHeightAdjustment) + 1

    adjustLineHeight(newLineHeight)
}

const decreaseLineHeight = () => {
    const newLineHeight = parseInt(accessibility.content.lineHeightAdjustment) - 1

    adjustLineHeight(newLineHeight)
}

const adjustLineHeight = (heightAdjustment) => {
    /** set text nodes' letter spacing style */
    // defaultElements.forEach(function(element, i) {
    //     /** use element current letter spacing */
    //     var currentHeight = elArray[i]; // get element current letter spacing value
    //     currentHeight = currentHeight == 'normal' ? '0' : Number(currentHeight.replace('px',''))
    //     var newLineHeight = parseInt(heightAdjustment) * 10

    //     var letterSpacing = getNewLineHeight(currentHeight, newLineHeight)

    //     /** set element letter spacing style value */
    //     element.style.lineHeight = letterSpacing
    // });

    tags.forEach(tag => {
        var elements = document.querySelectorAll(`${tag}:not(#aceada *)`);

        for(let i = 0; i < elements.length; i++) {
            var currentHeight = elements[i].getAttribute('data-original-line-height');

            if (currentHeight) {
                currentHeight = currentHeight == 'normal' ? '0' : Number(currentHeight.replace('px',''))
                var newLineHeight = parseInt(heightAdjustment) * 10

                var letterSpacing = getNewLineHeight(currentHeight, newLineHeight)

                /** set element letter spacing style value */
                elements[i].style.lineHeight = letterSpacing
            }
        }

        const aceadaDiv = document.getElementById('aceada');
        aceadaDiv.style.lineHeight = '';
    });

    /** set input attribute and state */
    setStateAndInputAttributes(heightAdjustment)
}

/** compute input attribute and state */
const setStateAndInputAttributes = (heightAdjustment) => {
    // const spacingInput = document.getElementById('aceada-line-height-range');

    /** set input attribute */
    // spacingInput.dataset.aceadaLineHeight = heightAdjustment == 0 ? 'Default' : heightAdjustment;

    /** set input value */
    // spacingInput.value = heightAdjustment == 0 ? 'Default' : heightAdjustment + '%';

    /** set state */
    accessibility.setLineHeightAdjustment(heightAdjustment);

    // lineHeightLabel.value = heightAdjustment == 0 ? 'Default' : (heightAdjustment * 10) + '%'
    lineHeightLabel.value = heightAdjustment * 10

    /** remove event listener */
    if (heightAdjustment === 0) {
        DOMEvents.forEach((event) => {
            window.removeEventListener(event, function () {
                adjustLineHeight(accessibility.content.lineHeightAdjustment, null)
            })
        })
    }
}

const getNewLineHeight = (defaultHeight, adjustmentValue) => {
    var perc = (defaultHeight / 100) * adjustmentValue;
    var lineHeight = adjustmentValue == 0 ? `${parseFloat(defaultHeight)}px` : `${parseFloat(defaultHeight + perc)}px`

    return lineHeight
}
</script>

<template>
    <div id="aceada-line-height-adjustment">
        <div class="aceada-range-input">
            <p class="aceada-group-title">Line Height</p>
            <input id="aceada-line-height-range" name="aceada-line-height-range" type="range" min="-10" max="10" step="1" v-model="accessibility.content.lineHeightAdjustment" />
        </div>
        <div class="aceada-button-group">
            <div class="aceada-range-value">
                <span>{{ lineHeightLabel + '%' }}</span>
            </div>
            <div class="aceada-range-actions">
                <button id="aceada-increase-line-height" class="aceada-increase-range" value="increase Value" @click="increaseLineHeight()" data-aceada-line-height="increase">
                    <svg xmlns="http://www.w3.org/2000/svg" height="12" viewBox="0 0 320 512">
                        <path d="M182.6 137.4c-12.5-12.5-32.8-12.5-45.3 0l-128 128c-9.2 9.2-11.9 22.9-6.9 34.9s16.6 19.8 29.6 19.8H288c12.9 0 24.6-7.8 29.6-19.8s2.2-25.7-6.9-34.9l-128-128z"/>
                    </svg>
                </button>
                <button id="aceada-decrease-line-height" class="aceada-decrease-range" value="Decrease Value" @click="decreaseLineHeight()" data-aceada-line-height="increase">
                    <svg xmlns="http://www.w3.org/2000/svg" height="12" viewBox="0 0 320 512">
                        <path d="M137.4 374.6c12.5 12.5 32.8 12.5 45.3 0l128-128c9.2-9.2 11.9-22.9 6.9-34.9s-16.6-19.8-29.6-19.8L32 192c-12.9 0-24.6 7.8-29.6 19.8s-2.2 25.7 6.9 34.9l128 128z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>
