<script setup>
import { onMounted } from 'vue'
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'

const accessibility = useAccessibilityStore()

// enable on reload
onMounted(() => {
    if (accessibility.profile.epilepsy) {
        document.body.classList.add('aceada-stop-animations')
    }
});

const toggleEpilepsyProfile = () => {
    accessibility.toggleEpilepsyProfile()

    if (accessibility.profile.epilepsy) {
        document.body.classList.add('aceada-stop-animations')
    }
};

</script>

<template>
    <div class="aceada-user-mode">
        <div class="aceada-user-mode-title">
            <h1>Epilepsy Mode</h1>
            <span>Stop animation</span>
        </div>
        <div class="aceada-user-mode-toggle">
            <label class="aceada-switch">
                <input :checked="accessibility.profile.epilepsy" type="checkbox" @change="toggleEpilepsyProfile">
                <span class="aceada-slider"></span>
            </label>
        </div>
    </div>

    <div id="aceada-epilepsy-mode-adjustments" class="aceada-user-mode-sub-adjustments" v-if="accessibility.profile.epilepsy">
        <button id="aceada-stop-animations-epilepsy-mode" class="aceada-adjustment-button" :class="{ 'aceada-adjustment-button-active': accessibility.orientation.stopAnimations }" @click="accessibility.toggleStopAnimations()">
            <div class="aceada-adjustment-icon">
                <svg width="40" height="40" viewBox="0 0 59 59" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="30" cy="30" r="24" stroke="black" stroke-width="2"/>
                    <path d="M26 22V38M33 22V38" stroke="#011817" stroke-width="2"/>
                </svg>
            </div>
            <p class="aceada-adjustment-title">Stop Animations</p>
        </button>
    </div>
</template>
