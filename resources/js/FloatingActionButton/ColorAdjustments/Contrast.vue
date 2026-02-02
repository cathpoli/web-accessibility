<script setup>
import { onMounted } from 'vue';
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'

const accessibility = useAccessibilityStore()
const levels = ['dark', 'light', 'high']

onMounted(() => {
    if (accessibility.color.contrast) {
        // document.getElementById(`${accessibility.color.contrast}-contrast`).classList.add('active')
        document.body.classList.add(`aceada-${accessibility.color.contrast}-contrast`)
    }
});

accessibility.$subscribe((mutation, state) => {
    const newLevel = state.color.contrast

    // remove remove if null or changed
    for (let index = 0; index < levels.length; index++) {
        document.body.classList.remove(`aceada-${levels[index]}-contrast`)

        var choosePlanModal = document.getElementById('choose-plan-modal');
        if (choosePlanModal) {
            choosePlanModal.style.marginTop = '0px'; 
        }

        var domainModal = document.getElementById('domain-modal');
        if (domainModal) {
            domainModal.style.marginTop = '0px'; 
        }
    }

    // set selected contrast
    if (newLevel) {
        document.body.classList.add(`aceada-${newLevel}-contrast`)

        if (newLevel == 'high') {
            var choosePlanModal = document.getElementById('choose-plan-modal');
            if (choosePlanModal) {
                choosePlanModal.style.marginTop = '-80rem'; 
            }

            var domainModal = document.getElementById('domain-modal');
            if (domainModal) {
                domainModal.style.marginTop = '-80rem'; 
            }
        }
    }
})

const setContrast = (level) => {
    const newLevel = level;
    const oldLevel = accessibility.color.contrast;

    accessibility.setContrast(oldLevel === newLevel ? null : newLevel)

    if (oldLevel === newLevel) {
        document.body.classList.remove(`aceada-${newLevel}-contrast`)
    }
}

</script>

<template>
    <!-- <button id="aceada-light-contrast" class="aceada-adjustment-button" :class="{ 'aceada-adjustment-button-active': accessibility.color.contrast == 'light' }" @click="setContrast('light')">
        <div class="aceada-adjustment-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 512 512" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path d="M361.5 1.2c5 2.1 8.6 6.6 9.6 11.9L391 121l107.9 19.8c5.3 1 9.8 4.6 11.9 9.6s1.5 10.7-1.6 15.2L446.9 256l62.3 90.3c3.1 4.5 3.7 10.2 1.6 15.2s-6.6 8.6-11.9 9.6L391 391 371.1 498.9c-1 5.3-4.6 9.8-9.6 11.9s-10.7 1.5-15.2-1.6L256 446.9l-90.3 62.3c-4.5 3.1-10.2 3.7-15.2 1.6s-8.6-6.6-9.6-11.9L121 391 13.1 371.1c-5.3-1-9.8-4.6-11.9-9.6s-1.5-10.7 1.6-15.2L65.1 256 2.8 165.7c-3.1-4.5-3.7-10.2-1.6-15.2s6.6-8.6 11.9-9.6L121 121 140.9 13.1c1-5.3 4.6-9.8 9.6-11.9s10.7-1.5 15.2 1.6L256 65.1 346.3 2.8c4.5-3.1 10.2-3.7 15.2-1.6zM352 256c0 53-43 96-96 96s-96-43-96-96s43-96 96-96s96 43 96 96zm32 0c0-70.7-57.3-128-128-128s-128 57.3-128 128s57.3 128 128 128s128-57.3 128-128z"/>
            </svg>
            <img src="assets/images/icons/contrast/Light Contrast.png" alt="Light Contrast">
        </div>
        <p class="aceada-adjustment-title">Light Contrast</p>
    </button> -->
    <button id="aceada-high-contrast" class="aceada-adjustment-button" :class="{ 'aceada-adjustment-button-active': accessibility.color.contrast == 'high' }" @click="setContrast('high')">
        <div class="aceada-adjustment-icon">
            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M16 6V3M8.92894 8.92894L6.80762 6.80762M6 16H3M16 29V26M25.1924 25.1924L23.071 23.0711M29 16H26M8.92894 23.0711L6.80762 25.1924M25.1924 6.80763L23.0711 8.92896M22 16C22 19.3137 19.3137 22 16 22C12.6863 22 10 19.3137 10 16C10 12.6863 12.6863 10 16 10C19.3137 10 22 12.6863 22 16Z" stroke="#011817" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <p class="aceada-adjustment-title">High</p>
    </button>
    <button id="aceada-dark-contrast" class="aceada-adjustment-button" :class="{ 'aceada-adjustment-button-active': accessibility.color.contrast == 'dark' }" @click="setContrast('dark')">
        <div class="aceada-adjustment-icon">
            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M22 16C22 19.3137 19.3137 22 16 22C12.6863 22 10 19.3137 10 16C10 12.6863 12.6863 10 16 10C19.3137 10 22 12.6863 22 16Z" fill="#011817"/>
                <path d="M16 6V3M8.92894 8.92894L6.80762 6.80762M6 16H3M16 29V26M25.1924 25.1924L23.071 23.0711M29 16H26M8.92894 23.0711L6.80762 25.1924M25.1924 6.80763L23.0711 8.92896M22 16C22 19.3137 19.3137 22 16 22C12.6863 22 10 19.3137 10 16C10 12.6863 12.6863 10 16 10C19.3137 10 22 12.6863 22 16Z" stroke="#011817" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <p class="aceada-adjustment-title">Dark</p>
    </button>
</template>
