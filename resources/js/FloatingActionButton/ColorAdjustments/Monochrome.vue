<script setup>
import { onMounted } from 'vue';
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'
import { monochrome } from '@/FloatingActionButton/ColorAdjustments/Functions/Monochrome'
import { isMobile } from '@/FloatingActionButton/helpers'

const accessibility = useAccessibilityStore()
const root = document.getElementsByTagName('html')[0]

// enable on reload
onMounted(() => {
    if (! isMobile()) {        
        if (accessibility.color.monochrome) monochrome.toggle(accessibility)
    }
});

window.addEventListener('load', () => {
    if (! isMobile()) {        
        if (accessibility.color.monochrome) monochrome.toggle(accessibility)
    }
})

accessibility.$subscribe((mutation, state) => {
    monochrome.toggle(state)
})

</script>

<template>
    <button id="aceada-monochrome" class="aceada-adjustment-button" :class="{ 'aceada-adjustment-button-active': accessibility.color.monochrome }" @click="accessibility.toggleMonochrome()">
        <div class="aceada-adjustment-icon">
            <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="34" height="34" rx="6" fill="url(#paint0_linear_206_4420)"/>
                <defs>
                    <linearGradient id="paint0_linear_206_4420" x1="4.558e-08" y1="14.5" x2="34" y2="14.5" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#011817"/>
                        <stop offset="1" stop-color="#011817" stop-opacity="0"/>
                    </linearGradient>
                </defs>
            </svg>
        </div>
        <p class="aceada-adjustment-title">Monochrome</p>
    </button>
</template>
