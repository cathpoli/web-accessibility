<script setup>
import { onMounted } from 'vue'
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'

const accessibility = useAccessibilityStore();
const tags = ['header', 'nav', 'section', 'footer'];

// enable on reload
onMounted(() => {
    if (accessibility.profile.colorBlindness) {
        document.getElementById('aceada-color-blindness-checkbox').checked = true;
    }
});

const toggleColorBlindnessProfile = () => {
    accessibility.toggleColorBlindnessProfile();
    if (document.getElementById('aceada-color-blindness-checkbox').checked) {
        //set Dark Contrast
        accessibility.setContrast('dark')
        document.body.classList.add(`aceada-dark-contrast`)

        // single selection: remove other profile feature
        document.body.classList.remove('aceada-highlighted-link')
        document.body.classList.remove('aceada-highlighted-title')
        document.body.classList.remove('aceada-stop-animations')
    } else {
        accessibility.setContrast(null)

        // disable monochrome is enabled on uncheck
        if (accessibility.color.monochrome) accessibility.toggleMonochrome()
    }
};

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
    <div class="aceada-user-mode">
        <div class="aceada-user-mode-title">
            <h1>Color Blindness Mode</h1>
            <span>Dark or high contrast and monochrome options</span>
        </div>
        <div class="aceada-user-mode-toggle">
            <label class="aceada-switch">
                <input id="aceada-color-blindness-checkbox" :checked="accessibility.profile.colorBlindness" type="checkbox" @change="toggleColorBlindnessProfile">
                <span class="aceada-slider"></span>
            </label>
        </div>
    </div>

    <div id="aceada-color-blindness-adjustments" class="aceada-user-mode-sub-adjustments" v-if="accessibility.profile.colorBlindness">
        <button id="aceada-reading-guide" class="aceada-adjustment-button" :class="{ 'aceada-adjustment-button-active': accessibility.color.contrast === 'dark' }" @click="setContrast('dark')">
            <div class="aceada-reading-orientation-selected" v-if="accessibility.orientation.readingGuide">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_206_4323)">
                        <circle cx="9" cy="9" r="9" fill="#1AD2C7"/>
                        <path d="M14.25 6L7.03125 12.75L3.75 9.68182" fill="#1AD2C7"/>
                        <path d="M14.25 6L7.03125 12.75L3.75 9.68182" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </g>
                    <defs>
                        <clipPath id="clip0_206_4323">
                            <rect width="18" height="18" fill="white"/>
                        </clipPath>
                    </defs>
                </svg>
            </div>
            <div class="aceada-adjustment-icon">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22 16C22 19.3137 19.3137 22 16 22C12.6863 22 10 19.3137 10 16C10 12.6863 12.6863 10 16 10C19.3137 10 22 12.6863 22 16Z" fill="#011817"/>
                    <path d="M16 6V3M8.92894 8.92894L6.80762 6.80762M6 16H3M16 29V26M25.1924 25.1924L23.071 23.0711M29 16H26M8.92894 23.0711L6.80762 25.1924M25.1924 6.80763L23.0711 8.92896M22 16C22 19.3137 19.3137 22 16 22C12.6863 22 10 19.3137 10 16C10 12.6863 12.6863 10 16 10C19.3137 10 22 12.6863 22 16Z" stroke="#011817" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <p class="aceada-adjustment-title">Dark Contrast</p>
        </button>
        <button id="aceada-reading-guide" class="aceada-adjustment-button" :class="{ 'aceada-adjustment-button-active': accessibility.color.contrast === 'high' }" @click="setContrast('high')">
            <div class="aceada-reading-orientation-selected" v-if="accessibility.orientation.readingGuide">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_206_4323)">
                        <circle cx="9" cy="9" r="9" fill="#1AD2C7"/>
                        <path d="M14.25 6L7.03125 12.75L3.75 9.68182" fill="#1AD2C7"/>
                        <path d="M14.25 6L7.03125 12.75L3.75 9.68182" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </g>
                    <defs>
                        <clipPath id="clip0_206_4323">
                            <rect width="18" height="18" fill="white"/>
                        </clipPath>
                    </defs>
                </svg>
            </div>
            <div class="aceada-adjustment-icon">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16 6V3M8.92894 8.92894L6.80762 6.80762M6 16H3M16 29V26M25.1924 25.1924L23.071 23.0711M29 16H26M8.92894 23.0711L6.80762 25.1924M25.1924 6.80763L23.0711 8.92896M22 16C22 19.3137 19.3137 22 16 22C12.6863 22 10 19.3137 10 16C10 12.6863 12.6863 10 16 10C19.3137 10 22 12.6863 22 16Z" stroke="#011817" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <p class="aceada-adjustment-title">High Contrast</p>
        </button>
        <button id="aceada-reading-guide" class="aceada-adjustment-button" :class="{ 'aceada-adjustment-button-active': accessibility.color.monochrome }" @click="accessibility.toggleMonochrome()">
            <div class="aceada-reading-orientation-selected" v-if="accessibility.orientation.readingGuide">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_206_4323)">
                        <circle cx="9" cy="9" r="9" fill="#1AD2C7"/>
                        <path d="M14.25 6L7.03125 12.75L3.75 9.68182" fill="#1AD2C7"/>
                        <path d="M14.25 6L7.03125 12.75L3.75 9.68182" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </g>
                    <defs>
                        <clipPath id="clip0_206_4323">
                            <rect width="18" height="18" fill="white"/>
                        </clipPath>
                    </defs>
                </svg>
            </div>
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
    </div>
</template>
