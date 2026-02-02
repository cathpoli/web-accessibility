<script setup>
import { onMounted } from 'vue';
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'
import { bigCursor } from '@/FloatingActionButton/OrientationAdjustments/Functions/BigCursor'
import { isMobile } from '@/FloatingActionButton/helpers'

const accessibility = useAccessibilityStore()
const colors = ['white', 'black']
var appElem = document.body;

// enable on reload
onMounted(() => {
    if (accessibility.orientation.cursorColor && ! isMobile())
        bigCursor.setCursor(accessibility)
});

const selectCursor = (event) => {
    if  (isMobile()) return;

    var el = event.target
    var button = el.closest('.aceada-adjustment-button')

    var color = button.getAttribute('data-aceada-cursor-color')

    // remove classes
    for (let index = 0; index < colors.length; index++) {
        document.body.classList.remove(`aceada-${colors[index]}-cursor`);
    }

    // set color
    if (accessibility.orientation.cursorColor !== color) {
        accessibility.setCursorColor(color)
        bigCursor.setCursor(accessibility)
    } else {
        accessibility.setCursorColor(null) // set to null if turned off
    }
};
</script>

<template>
    <div v-if="! isMobile()">
        <button id="aceada-white-cursor" class="aceada-adjustment-button" :class="{ 'aceada-adjustment-button-active': accessibility.orientation.cursorColor == 'white' }" @click="selectCursor" data-aceada-cursor-color="white">
            <div class="aceada-adjustment-icon">
                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.5265 14.267L22.5251 14.2675L18.5623 15.6023C18.5617 15.6025 18.561 15.6027 18.5604 15.6029C17.1777 16.0637 16.0608 17.1414 15.5932 18.5674L14.2565 22.5222L14.2553 22.5257C13.4226 25.0193 9.91103 24.981 9.12624 22.4715C9.1262 22.4714 9.12616 22.4713 9.12612 22.4712L4.62646 8.02432C4.62622 8.02354 4.62598 8.02275 4.62575 8.02197C3.98135 5.90727 5.93326 3.98067 7.99229 4.63069L7.99735 4.63227L22.4963 9.12499C22.4968 9.12517 22.4974 9.12534 22.4979 9.12551C24.972 9.90331 25.0196 13.4314 22.5265 14.267Z" stroke="#011817" stroke-width="2"/>
                </svg>
            </div>
            <p class="aceada-adjustment-title">White Cursor</p>
        </button>
        <button id="aceada-black-cursor" class="aceada-adjustment-button" :class="{ 'aceada-adjustment-button-active': accessibility.orientation.cursorColor == 'black' }" @click="selectCursor" data-aceada-cursor-color="black">
            <div class="aceada-adjustment-icon">
                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.5265 14.267L22.5251 14.2675L18.5623 15.6023C18.5617 15.6025 18.561 15.6027 18.5604 15.6029C17.1777 16.0637 16.0608 17.1414 15.5932 18.5674L14.2565 22.5222L14.2553 22.5257C13.4226 25.0193 9.91103 24.981 9.12624 22.4715C9.1262 22.4714 9.12616 22.4713 9.12612 22.4712L4.62646 8.02432C4.62622 8.02354 4.62598 8.02275 4.62575 8.02197C3.98135 5.90727 5.93326 3.98067 7.99229 4.63069L7.99735 4.63227L22.4963 9.12499C22.4968 9.12517 22.4974 9.12534 22.4979 9.12551C24.972 9.90331 25.0196 13.4314 22.5265 14.267Z" fill="#011817" stroke="#011817" stroke-width="2"/>
                </svg>
            </div>
            <p class="aceada-adjustment-title">Black Cursor</p>
        </button>
    </div>
</template>
