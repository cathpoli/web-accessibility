<script setup>
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'

const accessibility = useAccessibilityStore()
const colors = ['blue', 'purple', 'red', 'orange', 'teal', 'green', 'white', 'black'];
const tags = ['p', 'a', 'span', 'li', 'table', 'label', 'button', 'th', 'td', 'tr', 'input', 'div'];

// enable on reload
let prevUrl = undefined;
setInterval(() => {
    const currUrl = window.location.href;
    if (currUrl != prevUrl) {
        // URL changed
        prevUrl = currUrl;
        if (accessibility.color.textColor) {
            // document.body.classList.add(`aceada-${accessibility.color.textColor}-text`);
            window.addEventListener('load', function () {
                var textColorBtn = document.getElementById(`aceada-text-color-${accessibility.color.textColor}-option`)

                // if (textColorBtn) textColorBtn.classList.add('active')
            })

            //Remove other class
            const otherColors = colors.indexOf(accessibility.color.textColor);
            if (otherColors > -1) {
                colors.splice(otherColors, 1);
            }

            colors.forEach(color => {
                document.body.classList.remove('aceada-' + color + '-text');
            });

            //Adding the selected style color for all the elements
            tags.forEach(tag => {
                var element = document.querySelectorAll(`${tag}:not(#aceada *)`);
                for(let i = 0; i < element.length; i++) {

                    if(!element[i].hasAttribute('data-default-color')) {
                        let defaultColor = window.getComputedStyle(element[i], null).getPropertyValue('color');
                        element[i].setAttribute('data-default-color', defaultColor);
                    }

                    setTimeout(function() {
                        element[i].style.color = setHexaColor(accessibility.color.textColor);
                    }, 1000);

                }
            });
        }
    }
}, 1000);

const textColorAdjustment = (event) => {
    var el = event.target
    var button = el.closest('.aceada-color-picker-option')
    var color = button.getAttribute('data-aceada-text-color')

    var otherColors = ['blue', 'purple', 'red', 'orange', 'teal', 'green', 'white', 'black'];
    const index = otherColors.indexOf(color);

    if (index > -1)
        otherColors.splice(index, 1);

    if (accessibility.color.textColor != color) {
        accessibility.setTextColor(color)

        otherColors.forEach(otherColor => {
            document.body.classList.remove(`aceada-${otherColor}-text`);
            // document.getElementById(`aceada-text-color-${otherColor}-option`).classList.remove('active')
        });

        // document.body.classList.add(`aceada-${color}-text`)
        // document.getElementById(`aceada-text-color-${color}-option`).classList.add('active')

        tags.forEach(tag => {
            var element = document.querySelectorAll(`${tag}:not(#aceada *)`);
            for(let i = 0; i < element.length; i++) {

                if(!element[i].hasAttribute('data-default-color')) {
                    let defaultColor = window.getComputedStyle(element[i], null).getPropertyValue('color');
                    element[i].setAttribute('data-default-color', defaultColor);
                }

                setTimeout(function() {
                    element[i].style.color = setHexaColor(color);
                }, 1000);

            }
        });
    } else {
        removeAceAdaColor()
        accessibility.setTextColor(null)

        document.body.classList.remove(`aceada-${color}-text`)
        // document.getElementById(`aceada-text-color-${color}-option`).classList.remove('active')
    }
};

const removeAceAdaColor = () => {
    accessibility.setTextColor(null)

    colors.forEach(color => {
        document.body.classList.remove('aceada-' + color + '-text');
        // document.getElementById(`aceada-text-color-${color}-option`).classList.remove('active')
    });

    tags.forEach(tag => {
        var element = document.querySelectorAll(`${tag}:not(.aceada-feature-title):not(.aceada-adjustment-title):not(.aceada-seizures-profile):not(.aceada-btn):not(.aceada-input)`);
        for(let i = 0; i < element.length; i++) {
            let defaultColor = element[i].getAttribute('data-default-color');
            element[i].style.color = defaultColor;
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
    <div id="aceada-text-color" class="aceada-adjustment-button aceada-color-picker">
        <div class="aceada-color-picker-header">
            <p class="aceada-adjustment-title">Text Color</p>
            <!-- <button class="aceada-color-picker-reset" @click="removeAceAdaColor()"> Reset </button> -->
        </div>
        <div class="aceada-color-picker-options">
            <!-- blue -->
            <button id="aceada-text-color-blue-option" class="aceada-color-picker-option aceada-color-blue-option" :class="{ 'aceada-color-picker-option-active': accessibility.color.textColor == 'blue' }" title="Change text colors to Blue" @click="textColorAdjustment" data-aceada-text-color="blue"> </button>
            <!-- purple -->
            <button id="aceada-text-color-purple-option" class="aceada-color-picker-option aceada-color-purple-option" :class="{ 'aceada-color-picker-option-active': accessibility.color.textColor == 'purple' }" title="Change text colors to Purple" @click="textColorAdjustment" data-aceada-text-color="purple"> </button>
            <!-- red -->
            <button id="aceada-text-color-red-option" class="aceada-color-picker-option aceada-color-red-option" :class="{ 'aceada-color-picker-option-active': accessibility.color.textColor == 'red' }" title="Change text colors to Red" @click="textColorAdjustment" data-aceada-text-color="red"> </button>
            <!-- orange -->
            <button id="aceada-text-color-orange-option" class="aceada-color-picker-option aceada-color-orange-option" :class="{ 'aceada-color-picker-option-active': accessibility.color.textColor == 'orange' }" title="Change text colors to Orange" @click="textColorAdjustment" data-aceada-text-color="orange"> </button>
            <!-- teal -->
            <button id="aceada-text-color-teal-option" class="aceada-color-picker-option aceada-color-teal-option" :class="{ 'aceada-color-picker-option-active': accessibility.color.textColor == 'teal' }" title="Change text colors to Teal" @click="textColorAdjustment" data-aceada-text-color="teal"> </button>
            <!-- green -->
            <button id="aceada-text-color-green-option" class="aceada-color-picker-option aceada-color-green-option" :class="{ 'aceada-color-picker-option-active': accessibility.color.textColor == 'green' }" title="Change text colors to Green" @click="textColorAdjustment" data-aceada-text-color="green"> </button>
            <!-- white-->
            <button id="aceada-text-color-white-option" class="aceada-color-picker-option aceada-color-white-option" :class="{ 'aceada-color-picker-option-active': accessibility.color.textColor == 'white' }" title="Change text colors to White" @click="textColorAdjustment" data-aceada-text-color="white"> </button>
            <!-- black -->
            <button id="aceada-text-color-black-option" class="aceada-color-picker-option aceada-color-black-option" :class="{ 'aceada-color-picker-option-active': accessibility.color.textColor == 'black' }" title="Change text colors to Black" @click="textColorAdjustment" data-aceada-text-color="black"></button>
        </div>
        <!-- cancel -->
        <!-- <div class="disable" role="button" tabindex="0">
            <div role="button" @click="removeAceAdaColor()" class="aceada-btn"> Cancel </div>
        </div> -->
    </div>
</template>
