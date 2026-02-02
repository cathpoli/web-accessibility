<script setup>
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'

const accessibility = useAccessibilityStore()

</script>

<template>
    <div class="aceada-user-mode">
        <div class="aceada-user-mode-title">
            <h1>Elderly Friendly Mode</h1>
            <span>Readable mode, text magnifier</span>
        </div>
        <div class="aceada-user-mode-toggle">
            <label class="aceada-switch">
                <input :checked="accessibility.profile.elderly" type="checkbox" @change="accessibility.setElderlyProfile($event.target.checked)">
                <span class="aceada-slider"></span>
            </label>
        </div>
    </div>

    <!-- <div id="elderly-adjustments" class="options" v-if="accessibility.profile.elderly">
        <div role="button" id="text-reader" class="option aceada-btn" :class="{ 'active': accessibility.navigation.textReader }" @click="accessibility.toggleTextReader()">
            <div class="feature-button">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" width="35" height="35">
                    <rect width="256" height="256" fill="none"/>
                    <path d="M218.9,77.1a71.9,71.9,0,0,1,0,101.8" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="24"/>
                    <path d="M80,168H32a8,8,0,0,1-8-8V96a8,8,0,0,1,8-8H80l72-56V224Z" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="24"/>
                    <line x1="80" y1="88" x2="80" y2="168" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="24"/>
                    <path d="M190.6,105.4a31.9,31.9,0,0,1,0,45.2" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="24"/>
                </svg>
            </div>
            <p class="aceada-feature-title">Text Reader</p>
        </div>
        <div role="button" id="aceada-text-magnifier-content" class="aceada-btn" :class="{ active: accessibility.content.textMagnifier }" @click="accessibility.toggleTextMagnifier()">
            <div class="flex justify-center items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-zoom-in" viewBox="0 0 16 16"> <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"/> <path d="M10.344 11.742c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1 6.538 6.538 0 0 1-1.398 1.4z"/> <path fill-rule="evenodd" d="M6.5 3a.5.5 0 0 1 .5.5V6h2.5a.5.5 0 0 1 0 1H7v2.5a.5.5 0 0 1-1 0V7H3.5a.5.5 0 0 1 0-1H6V3.5a.5.5 0 0 1 .5-.5z"/> </svg>
            </div>
            <p class="mt-4 aceada-feature-title">Text Magnifier</p>
        </div>
        <div role="button" id="aceada-image-magnifier-orientation" class="option aceada-btn" :class="{ 'active': accessibility.orientation.magnifier }" @click="accessibility.toggleMagnifier()">
            <div class="feature-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-zoom-in" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"/>
                    <path d="M10.344 11.742c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1 6.538 6.538 0 0 1-1.398 1.4z"/>
                    <path fill-rule="evenodd" d="M6.5 3a.5.5 0 0 1 .5.5V6h2.5a.5.5 0 0 1 0 1H7v2.5a.5.5 0 0 1-1 0V7H3.5a.5.5 0 0 1 0-1H6V3.5a.5.5 0 0 1 .5-.5z"/>
                </svg>
            </div>
            <p class="aceada-feature-title">Magnifier</p>
        </div>
        <div role="button" id="read-mode" class="option aceada-btn" @click="accessibility.toggleReadableMode()">
            <div class="feature-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 576 512" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path d="M249.6 471.5c10.8 3.8 22.4-4.1 22.4-15.5V78.6c0-4.2-1.6-8.4-5-11C247.4 52 202.4 32 144 32C93.5 32 46.3 45.3 18.1 56.1C6.8 60.5 0 71.7 0 83.8V454.1c0 11.9 12.8 20.2 24.1 16.5C55.6 460.1 105.5 448 144 448c33.9 0 79 14 105.6 23.5zm76.8 0C353 462 398.1 448 432 448c38.5 0 88.4 12.1 119.9 22.6c11.3 3.8 24.1-4.6 24.1-16.5V83.8c0-12.1-6.8-23.3-18.1-27.6C529.7 45.3 482.5 32 432 32c-58.4 0-103.4 20-123 35.6c-3.3 2.6-5 6.8-5 11V456c0 11.4 11.7 19.3 22.4 15.5z"/>
                </svg>
            </div>
            <p class="aceada-feature-title">Read Mode</p>
        </div>
    </div> -->
</template>
