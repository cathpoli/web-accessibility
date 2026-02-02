<script setup>
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'

const accessibility = useAccessibilityStore()
</script>

<template>
    <button id="aceada-magnifier" class="aceada-adjustment-button" :class="{ 'aceada-adjustment-button-active': accessibility.orientation.magnifier }" @click="accessibility.toggleMagnifier()">
        <div class="aceada-adjustment-icon">
            <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M45 45.625L32.1875 32.1875M36.25 20.625C36.25 29.2545 29.2545 36.25 20.625 36.25C11.9956 36.25 5 29.2545 5 20.625C5 11.9956 11.9956 5 20.625 5C29.2545 5 36.25 11.9956 36.25 20.625Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M20.625 13.75V27.5M13.75 20.625H27.5" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <p class="aceada-adjustment-title">Magnifier</p>
    </button>
</template>
