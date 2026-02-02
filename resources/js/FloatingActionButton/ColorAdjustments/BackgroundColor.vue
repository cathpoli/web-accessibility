<script setup>
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'

const accessibility = useAccessibilityStore()
const colors = ['blue', 'purple', 'red', 'orange', 'teal', 'green', 'white', 'black'];
const tags = ['header', 'nav', 'section', 'footer'];

var appElem = document.body;

// enable on reload
let prevUrl = undefined;
setTimeout(() => {
    const currUrl = window.location.href;
    if (currUrl != prevUrl) {
        // URL changed
        prevUrl = currUrl;
        if (accessibility.color.backgroundColor) {

            // appElem.classList.add(`aceada-${accessibility.color.backgroundColor}-bg`);

            //Adding the selected background color for all the elements
            tags.forEach(tag => {
                var element = document.querySelectorAll(tag);

                //If the element is empty, set the style on the body tag
                if (element.length == 0) {
                    appElem.classList.add(`aceada-${accessibility.color.backgroundColor}-bg`);
                }

                for(let i = 0; i < element.length; i++) {

                    if(!element[i].hasAttribute('data-default-color')) {
                        let defaultColor = window.getComputedStyle(element[i], null).getPropertyValue('background-color');
                        element[i].setAttribute('data-default-color', defaultColor);
                    }

                    setTimeout(function() {
                        element[i].style.backgroundColor  = setHexaColor(accessibility.color.backgroundColor);
                        appElem.classList.add(`aceada-${accessibility.color.backgroundColor}-bg`);
                    }, 1000);

                }
            });
        }
    }
}, 1000);

const setBackgroundColor = (event) => {
    var el = event.target
    var button = el.closest('.aceada-color-picker-option')

    var color = button.getAttribute('data-aceada-background-color')

    // remove classes
    for (let index = 0; index < colors.length; index++) {
        appElem.classList.remove(`aceada-${colors[index]}-bg`);
    }

    // set color
    if (accessibility.color.backgroundColor !== color) {
        accessibility.setBackgroundColor(color)
        // appElem.classList.add(`aceada-${color}-bg`);

        //Adding the selected background color for all the elements
        tags.forEach(tag => {
            var element = document.querySelectorAll(tag);

            //If the element is empty, set the style on the body tag
            if (element.length == 0) {
                appElem.classList.add(`aceada-${color}-bg`);
            }

            for(let i = 0; i < element.length; i++) {

                if(!element[i].hasAttribute('data-default-color')) {
                    let defaultColor = window.getComputedStyle(element[i], null).getPropertyValue('background-color');
                    element[i].setAttribute('data-default-color', defaultColor);
                }

                setTimeout(function() {
                    element[i].style.backgroundColor  = setHexaColor(color);
                }, 1000);

            }
        });
    } else {
        removeAceAdaColor()
        accessibility.setBackgroundColor(null) // set to null if turned off
    }
}

const removeAceAdaColor = () => {
    accessibility.setBackgroundColor(null)

    for (let index = 0; index < colors.length; index++) {
        appElem.classList.remove(`aceada-${colors[index]}-bg`);
    }

    tags.forEach(tag => {
        var element = document.querySelectorAll(tag);
        for(let i = 0; i < element.length; i++) {
            let defaultColor = element[i].getAttribute('data-default-color');
            element[i].style.backgroundColor = defaultColor;
        }
    });
}

const setHexaColor = (color) => {
    let result = '';

    switch (color) {
        case 'blue':
            result = '#0076B4'
            break;
        case 'purple':
            result = '#7A549C'
            break;
        case 'red':
            result = '#C83733'
            break;
        case 'orange':
            result = '#D07021'
            break;
        case 'teal':
            result = '#1AD2C7'
            break;
        case 'green':
            result = '#4D7831'
            break;
        case 'white':
            result = '#ffffff'
            break;
        case 'black':
            result = '#000000'
            break;
    }

    return result;
}
</script>

<template>
    <div id="aceada-background-color" class="aceada-adjustment-button aceada-color-picker">
        <div class="aceada-color-picker-header">
            <p class="aceada-adjustment-title">Background Color</p>
            <!-- <button class="aceada-color-picker-reset" @click="removeAceAdaColor()"> Reset </button> -->
        </div>
        <div class="aceada-color-picker-options">
            <!-- blue -->
            <button id="aceada-background-color-blue-option" class="aceada-color-picker-option aceada-color-blue-option" :class="{ 'aceada-color-picker-option-active': accessibility.color.backgroundColor === 'blue' }" title="Change background colors to Blue" @click="setBackgroundColor" data-aceada-background-color="blue"> </button>
            <!-- purple -->
            <button id="aceada-background-color-purple-option" class="aceada-color-picker-option aceada-color-purple-option" :class="{ 'aceada-color-picker-option-active': accessibility.color.backgroundColor === 'purple' }" title="Change background colors to Purple" @click="setBackgroundColor" data-aceada-background-color="purple"> </button>
            <!-- red -->
            <button id="aceada-background-color-red-option" class="aceada-color-picker-option aceada-color-red-option" :class="{ 'aceada-color-picker-option-active': accessibility.color.backgroundColor === 'red' }" title="Change background colors to Red" @click="setBackgroundColor" data-aceada-background-color="red"> </button>
            <!-- orange -->
            <button id="aceada-background-color-orange-option" class="aceada-color-picker-option aceada-color-orange-option" :class="{ 'aceada-color-picker-option-active': accessibility.color.backgroundColor === 'orange' }" title="Change background colors to Orange" @click="setBackgroundColor" data-aceada-background-color="orange"> </button>
            <!-- teal -->
            <button id="aceada-background-color-teal-option" class="aceada-color-picker-option aceada-color-teal-option" :class="{ 'aceada-color-picker-option-active': accessibility.color.backgroundColor === 'teal' }" title="Change background colors to Teal" @click="setBackgroundColor" data-aceada-background-color="teal"> </button>
            <!-- green -->
            <button id="aceada-background-color-green-option" class="aceada-color-picker-option aceada-color-green-option" :class="{ 'aceada-color-picker-option-active': accessibility.color.backgroundColor === 'green' }" title="Change background colors to Green" @click="setBackgroundColor" data-aceada-background-color="green"> </button>
            <!-- white-->
            <button id="aceada-background-color-white-option" class="aceada-color-picker-option aceada-color-white-option" :class="{ 'aceada-color-picker-option-active': accessibility.color.backgroundColor === 'white' }" title="Change background colors to White" @click="setBackgroundColor" data-aceada-background-color="white"> </button>
            <!-- black -->
            <button id="aceada-background-color-black-option" class="aceada-color-picker-option aceada-color-black-option" :class="{ 'aceada-color-picker-option-active': accessibility.color.backgroundColor === 'black' }" title="Change background colors to Black" @click="setBackgroundColor" data-aceada-background-color="black"> </button>
        </div>
    </div>
</template>
