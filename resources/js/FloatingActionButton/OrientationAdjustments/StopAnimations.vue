<script setup>
import { onMounted } from 'vue'
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'
import { stopAnimations } from '@/FloatingActionButton/OrientationAdjustments/Functions/StopAnimations'

const accessibility = useAccessibilityStore()

// enable on reload
onMounted(() => {
    if (accessibility.orientation.stopAnimations) document.body.classList.add('aceada-stop-animations')
});

accessibility.$subscribe((mutation, state) => {
    stopAnimations.toggle(state.orientation.stopAnimations)
})

</script>

<template>
    <button id="aceada-stop-animations" class="aceada-adjustment-button" :class="{ 'aceada-adjustment-button-active': accessibility.orientation.stopAnimations }" @click="accessibility.toggleStopAnimations()">
        <div class="aceada-adjustment-icon">
            <svg width="59" height="59" viewBox="0 0 59 59" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="30" cy="30" r="24" stroke="black" stroke-width="2"/>
                <path d="M26 22V38M33 22V38" stroke="#011817" stroke-width="2"/>
            </svg>
        </div>
        <p class="aceada-adjustment-title">Stop Animations</p>
    </button>
</template>
