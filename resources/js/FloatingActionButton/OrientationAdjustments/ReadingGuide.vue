<script setup>
import { onMounted } from 'vue'
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'
import { readingGuide } from '@/FloatingActionButton/OrientationAdjustments/Functions/ReadingGuide'
import { isMobile } from '@/FloatingActionButton/helpers'

const accessibility = useAccessibilityStore()
const guideMargin = 250 // distance between top and bottom mask

// enable on reload
onMounted(() => {
    if (accessibility.orientation.readingGuide && ! isMobile()) readingGuide.toggle(accessibility)
});

const clickReadingGuide = () => {
    accessibility.toggleReadingGuide()

    readingGuide.toggle(accessibility)
};

</script>

<template>
    <button id="aceada-reading-guide" class="aceada-adjustment-button" :class="{ 'aceada-adjustment-button-active': accessibility.orientation.readingGuide }" @click="clickReadingGuide" v-if="! isMobile()">
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
            <svg width="72" height="28" viewBox="0 0 72 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_206_4315)">
                    <path d="M0 2H70" stroke="#011817" stroke-width="2"/>
                    <path d="M56.4841 18.2273L56.4827 18.2278L53.0412 19.387C53.0405 19.3872 53.0398 19.3874 53.0392 19.3877C51.8005 19.8005 50.7988 20.7665 50.3791 22.0456L49.2182 25.4801L49.2171 25.4835C48.5347 27.527 45.6563 27.4983 45.0122 25.439C45.0122 25.4388 45.0122 25.4387 45.0121 25.4386L41.1041 12.8913C41.1039 12.8905 41.1036 12.8897 41.1034 12.889C40.5741 11.151 42.1774 9.57551 43.8623 10.1074L43.8674 10.109L56.4603 14.0111C56.4608 14.0113 56.4613 14.0115 56.4619 14.0116C58.4883 14.6489 58.5295 17.5418 56.4841 18.2273Z" stroke="black" stroke-width="2"/>
                    <circle cx="70" cy="2" r="2" fill="#011817"/>
                    <circle cx="2" cy="2" r="2" fill="#011817"/>
                </g>
                <defs>
                    <clipPath id="clip0_206_4315">
                        <rect width="72" height="28" fill="white"/>
                    </clipPath>
                </defs>
            </svg>
        </div>
        <p class="aceada-adjustment-title">Reading Guide</p>
    </button>
</template>
