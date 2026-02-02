<script setup>
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'

const accessibility = useAccessibilityStore()
const colors = ['blue', 'purple', 'red', 'orange', 'teal', 'green', 'white', 'black'];
const tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'];

let prevUrl = undefined;
setInterval(() => {
    const currUrl = window.location.href;
    if (currUrl != prevUrl) {
        // URL changed
        prevUrl = currUrl;
        if (accessibility.color.titleColor) {
            // document.body.classList.add(`aceada-${accessibility.color.titleColor}-title`);
            window.addEventListener('load', function () {
                var titleColorBtn = document.getElementById(`aceada-title-color-${accessibility.color.titleColor}-option`)

                // if (titleColorBtn) titleColorBtn.classList.add('active')
            })

            //Remove other class
            const otherColors = colors.indexOf(accessibility.color.titleColor);
            if (otherColors > -1) {
                colors.splice(otherColors, 1);
            }

            colors.forEach(color => {
                document.body.classList.remove('aceada-' + color + '-title');
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
                        element[i].style.color = setHexaColor(accessibility.color.titleColor);
                    }, 1000);

                }
            });
        }
    }
}, 1000);

const titleColorAdjustment = (event) => {
    var el = event.target
    var button = el.closest('.aceada-color-picker-option')
    var color = button.getAttribute('data-aceada-title-color')

    var otherColors = ['blue', 'purple', 'red', 'orange', 'teal', 'green', 'white', 'black'];
    const index = otherColors.indexOf(color);

    if (index > -1)
        otherColors.splice(index, 1);

    if (accessibility.color.titleColor != color) {
        accessibility.setTitleColor(color);

        otherColors.forEach(otherColor => {
            document.body.classList.remove(`aceada-${otherColor}-title`);
            // document.getElementById(`aceada-title-color-${otherColor}-option`).classList.remove('active')
        });

        // document.body.classList.add(`aceada-${color}-title`)
        // document.getElementById(`aceada-title-color-${color}-option`).classList.add('active')

        //Adding the selected style color for all the elements
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
        accessibility.setTitleColor(null);

        document.body.classList.remove(`aceada-${color}-title`)
        // document.getElementById(`title-color-${color}-btn`).classList.remove('active')
    }
};

const removeAceAdaColor = () => {
    accessibility.setTitleColor(null);

    colors.forEach(color => {
        document.body.classList.remove('aceada-' + color + '-title');
        // document.getElementById(`aceada-title-color-${color}-option`).classList.remove('active');
    });

    //Remove the style color for the selected tags
    tags.forEach(tag => {
        var element = document.querySelectorAll(`${tag}:not(#aceada *)`);
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
    <div id="aceada-title-color" class="aceada-adjustment-button aceada-color-picker">
        <div class="aceada-color-picker-header">
            <p class="aceada-adjustment-title">Title Color</p>
            <!-- <button class="aceada-color-picker-reset" @click="removeAceAdaColor()"> Reset </button> -->
        </div>
        <div class="aceada-color-picker-options">
            <!-- blue -->
            <button id="aceada-title-color-blue-option" class="aceada-color-picker-option aceada-color-blue-option" :class="{ 'aceada-color-picker-option-active': accessibility.color.titleColor === 'blue' }" title="Change title colors to Blue" @click="titleColorAdjustment" data-aceada-title-color="blue"> </button>
            <!-- purple -->
            <button id="aceada-title-color-purple-option" class="aceada-color-picker-option aceada-color-purple-option" :class="{ 'aceada-color-picker-option-active': accessibility.color.titleColor === 'purple' }" title="Change title colors to Purple" @click="titleColorAdjustment" data-aceada-title-color="purple"> </button>
            <!-- red -->
            <button id="aceada-title-color-red-option" class="aceada-color-picker-option aceada-color-red-option" :class="{ 'aceada-color-picker-option-active': accessibility.color.titleColor === 'red' }" title="Change title colors to Red" @click="titleColorAdjustment" data-aceada-title-color="red"> </button>
            <!-- orange -->
            <button id="aceada-title-color-orange-option" class="aceada-color-picker-option aceada-color-orange-option" :class="{ 'aceada-color-picker-option-active': accessibility.color.titleColor === 'orange' }" title="Change title colors to Orange" @click="titleColorAdjustment" data-aceada-title-color="orange"> </button>
            <!-- teal -->
            <button id="aceada-title-color-teal-option" class="aceada-color-picker-option aceada-color-teal-option" :class="{ 'aceada-color-picker-option-active': accessibility.color.titleColor === 'teal' }" title="Change title colors to Teal" @click="titleColorAdjustment" data-aceada-title-color="teal"> </button>
            <!-- green -->
            <button id="aceada-title-color-green-option" class="aceada-color-picker-option aceada-color-green-option" :class="{ 'aceada-color-picker-option-active': accessibility.color.titleColor === 'green' }" title="Change title colors to Green" @click="titleColorAdjustment" data-aceada-title-color="green"> </button>
            <!-- white-->
            <button id="aceada-title-color-white-option" class="aceada-color-picker-option aceada-color-white-option" :class="{ 'aceada-color-picker-option-active': accessibility.color.titleColor === 'white' }" title="Change title colors to White" @click="titleColorAdjustment" data-aceada-title-color="white"> </button>
            <!-- black -->
            <button id="aceada-title-color-black-option" class="aceada-color-picker-option aceada-color-black-option" :class="{ 'aceada-color-picker-option-active': accessibility.color.titleColor === 'black' }" title="Change title colors to Black" @click="titleColorAdjustment" data-aceada-title-color="black"> </button>
        </div>
        <!-- cancel -->
        <!-- <div class="disable" role="button" tabindex="0">
            <div role="button" @click="removeAceAdaColor()" class="aceada-btn"> Cancel </div>
        </div> -->
    </div>
</template>
