<script setup>
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'

const accessibility = useAccessibilityStore()

window.addEventListener('load', () => {
    document.getElementById("aceada-text-reader-pop-up").style.display = accessibility.navigation.textReader ? 'block' : 'none';
})

var pushState = history.pushState;
let prevUrl = undefined;
history.pushState = function () {
    pushState.apply(history, arguments);

    document.getElementById("aceada-text-reader-pop-up").style.display = accessibility.navigation.textReader ? 'block' : 'none';
};

accessibility.$subscribe((mutation, state) => {
    document.getElementById("aceada-text-reader-pop-up").style.display = state.navigation.textReader ? 'block' : 'none';
})

</script>

<template>
    <button id="aceada-text-reader" class="aceada-adjustment-button" :class="{ 'aceada-adjustment-button-active': accessibility.navigation.textReader }" @click="accessibility.toggleTextReader()">
        <div class="aceada-adjustment-icon">
            <svg width="28" height="32" viewBox="0 0 28 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7.41935 13.1481H20.5806M7.41935 7.44444H20.5806M7.41935 18.8518H20.5806M7.41935 24.5556H20.5806M4.32258 30H23.6774C24.9601 30 26 28.9553 26 27.6667V4.33333C26 3.04467 24.9601 2 23.6774 2H4.32258C3.03985 2 2 3.04467 2 4.33333V27.6667C2 28.9553 3.03986 30 4.32258 30Z" stroke="#011817" stroke-width="2" stroke-linecap="round"/>
            </svg>
        </div>
        <p class="aceada-adjustment-title">Text Reader</p>
    </button>
</template>
