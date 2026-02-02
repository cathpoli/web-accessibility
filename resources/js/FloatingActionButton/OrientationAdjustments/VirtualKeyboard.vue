<script setup>
import { onMounted } from 'vue'
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'
import { virtualKeyboard } from '@/FloatingActionButton/OrientationAdjustments/Functions/VirtualKeyboard'
import { isMobile } from '@/FloatingActionButton/helpers'

const accessibility = useAccessibilityStore()
window.addEventListener("load", (event) => {
    toggleKeyboardPopUp()
});

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
    if (accessibility.orientation.virtualKeyboard && ! isMobile()) {
        DOMEvents.forEach((event) => {
            window.addEventListener(event, function () {
                const currUrl = window.location.href;
                if (currUrl != prevUrl) {
                    // URL changed
                    prevUrl = currUrl;
                    toggleKeyboardPopUp()
                }
            })
        })
    }
};

const toggleVirtualKeyboard = () => {
    accessibility.toggleVirtualKeyboard()
    toggleKeyboardPopUp()
};

const toggleKeyboardPopUp = () => {
    if(accessibility.orientation.virtualKeyboard && ! isMobile()) {
        document.getElementById('aceada-virtual-keyboard-pop-up').style.display = 'inline-flex'
    } else {
        document.getElementById('aceada-virtual-keyboard-pop-up').style.display = 'none'
    }
};

</script>

<template>
    <button id="aceada-virtual-keyboard" class="aceada-adjustment-button" :class="{ 'aceada-adjustment-button-active': accessibility.orientation.virtualKeyboard }" @click="toggleVirtualKeyboard" v-if="! isMobile()">
        <div class="aceada-adjustment-icon">
            <svg width="43" height="30" viewBox="0 0 43 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7.52212 10.069H9.93805M12.6991 10.25H15.115M17.7035 10.25H20.1195M22.8805 10.25H25.2965M28.0575 10.25H30.3009M33.0619 10.25H35.3053M7.52212 15.1379H9.93805M12.6991 15.319H15.115M17.7035 15.319H20.1195M22.8805 15.319H25.2965M28.0575 15.319H30.3009M33.0619 15.319H35.3053M7.52212 20.3879H9.93805M12.6991 20.569H30.3009M33.0619 20.569H35.3053M4.0708 26H38.9292C40.0729 26 41 25.0274 41 23.8276V7.17241C41 5.97262 40.0729 5 38.9292 5H4.0708C2.92713 5 2 5.97262 2 7.17241V23.8276C2 25.0274 2.92713 26 4.0708 26Z" stroke="#011817" stroke-width="2"/>
            </svg>
        </div>
        <p class="aceada-adjustment-title">Virtual Keyboard</p>
    </button>
</template>
