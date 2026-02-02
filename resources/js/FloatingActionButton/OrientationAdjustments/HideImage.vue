<script setup>
import { onMounted } from 'vue';
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'
import { hideImages } from '@/FloatingActionButton/OrientationAdjustments/Functions/HideImages'

const accessibility = useAccessibilityStore()

// enable on reload
onMounted(() => {
    if (accessibility.orientation.hideImage) document.body.classList.add('aceada-hide-image')
});

accessibility.$subscribe((mutation, state) => {
    hideImages.toggle(state.orientation.hideImage)
})
</script>

<template>
    <button id="aceada-hide-images" class="aceada-adjustment-button" :class="{ 'aceada-adjustment-button-active': accessibility.orientation.hideImage }" @click="accessibility.toggleHideImage()">
        <div class="aceada-adjustment-icon">
            <svg width="59" height="59" viewBox="0 0 59 59" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M44.7692 22.6307C44.7692 24.2281 43.4743 25.523 41.8769 25.523C40.2795 25.523 38.9846 24.2281 38.9846 22.6307C38.9846 21.0333 40.2795 19.7384 41.8769 19.7384C43.4743 19.7384 44.7692 21.0333 44.7692 22.6307Z" fill="#011817"/>
                <path d="M23.8 12.5077H49.4462C51.6553 12.5077 53.4462 14.2986 53.4462 16.5077V41.4308M11.5077 12.5077H9.72308C7.51394 12.5077 5.72308 14.2986 5.72308 16.5077V46.1077C5.72308 48.3168 7.51394 50.1077 9.72308 50.1077H50.5538M5 6L53.4462 53M40.4307 40.7078H15.123L20.9077 32.7539L26.6923 37.8154L32.4769 32.7539M44.7692 22.6307C44.7692 24.2281 43.4743 25.523 41.8769 25.523C40.2795 25.523 38.9846 24.2281 38.9846 22.6307C38.9846 21.0333 40.2795 19.7384 41.8769 19.7384C43.4743 19.7384 44.7692 21.0333 44.7692 22.6307Z" stroke="#011817" stroke-width="2"/>
            </svg>
        </div>
        <p class="aceada-adjustment-title">Hide Images</p>
    </button>
</template>
