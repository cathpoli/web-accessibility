<script setup>
import { onMounted } from 'vue';
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'

const accessibility = useAccessibilityStore()
const alignments = ['left', 'right', 'center']

onMounted(() => {
    if (accessibility.content.textAlignment)
        document.body.classList.add(`aceada-align-${accessibility.content.textAlignment}`);
});

const alignTexts = (event) => {
    var el = event.target
    var button = el.closest('.aceada-text-alignments')
    var alignment = button.getAttribute('data-aceada-text-alignment')

    // remove classes
    for (let index = 0; index < alignments.length; index++) {
        document.body.classList.remove(`aceada-align-${alignments[index]}`);
    }

    // set alignment
    if (accessibility.content.textAlignment !== alignment) {
        accessibility.setTextAlignment(alignment)
        document.body.classList.add(`aceada-align-${alignment}`);
    } else {
        accessibility.setTextAlignment(null) // set to null if turned off
    }
}

</script>

<template>
    <div>
        <p class="aceada-group-title">Text Alignment</p>
        <div id="aceada-alignments">
            <button id="aceada-left-alignment" class="aceada-adjustment-button aceada-text-alignments" :class="{ 'aceada-adjustment-button-active': accessibility.content.textAlignment === 'left' }" @click="alignTexts" data-aceada-text-alignment="left">
                <svg width="27" height="25" viewBox="0 0 27 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1.9231 1.92307H24.0769" stroke="#011817" stroke-width="2" stroke-linecap="round"/>
                    <path d="M1.9231 8.69229H16.6923" stroke="#011817" stroke-width="2" stroke-linecap="round"/>
                    <path d="M1.9231 16.0769H25.3077" stroke="#011817" stroke-width="2" stroke-linecap="round"/>
                    <path d="M1.9231 23.4615H13" stroke="#011817" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
            <button id="aceada-center-alignment" class="aceada-adjustment-button aceada-text-alignments" :class="{ 'aceada-adjustment-button-active': accessibility.content.textAlignment === 'center' }" @click="alignTexts" data-aceada-text-alignment="center">
                <svg width="25" height="23" viewBox="0 0 25 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 0.999969L21 0.999971" stroke="#011817" stroke-width="2" stroke-linecap="round"/>
                    <path d="M5 15L21 15" stroke="#011817" stroke-width="2" stroke-linecap="round"/>
                    <path d="M1 7.99997H24" stroke="#011817" stroke-width="2" stroke-linecap="round"/>
                    <path d="M1 22H24" stroke="#011817" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
            <button id="aceada-right-alignment" class="aceada-adjustment-button aceada-text-alignments" :class="{ 'aceada-adjustment-button-active': accessibility.content.textAlignment === 'right' }" @click="alignTexts" data-aceada-text-alignment="right">
                <svg width="27" height="25" viewBox="0 0 27 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M25.3077 1.92307H3.15383" stroke="#011817" stroke-width="2" stroke-linecap="round"/>
                    <path d="M25.3077 8.69229H10.5384" stroke="#011817" stroke-width="2" stroke-linecap="round"/>
                    <path d="M25.3077 16.0769H1.92306" stroke="#011817" stroke-width="2" stroke-linecap="round"/>
                    <path d="M25.3077 23.4615H14.2308" stroke="#011817" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
        </div>
    </div>
</template>
