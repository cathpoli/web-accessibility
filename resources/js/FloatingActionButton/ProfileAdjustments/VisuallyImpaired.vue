<script setup>
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'
import { onMounted, ref } from 'vue'

const accessibility = useAccessibilityStore()
const showAdjustments = ref(false)

onMounted(() => {
    if (accessibility.profile.visuallyImpaired) {
        showAdjustments.value = true
    }
})

accessibility.$subscribe((mutation, state) => {
    if (state.profile.visuallyImpaired) {
        showAdjustments.value = true
    }
})

const toggleVisuallyImpairedMode = (event) => {
    showAdjustments.value = !showAdjustments.value
    accessibility.setVisuallyImpairedProfile(event.target.checked)
}

const setContrast = (level) => {
    accessibility.setContrast(level === accessibility.color.contrast ? null : level)
}

</script>

<template>
    <div class="aceada-user-mode">
        <div class="aceada-user-mode-title">
            <h1>Vision Impaired Mode</h1>
            <span>Enhance websites presentation</span>
        </div>
        <div class="aceada-user-mode-toggle">
            <label class="aceada-switch">
                <input :checked="accessibility.profile.visuallyImpaired" type="checkbox" @change="toggleVisuallyImpairedMode">
                <span class="aceada-slider"></span>
            </label>
        </div>
    </div>

    <!-- <div id="visually-impaired-adjustments" class="options" v-if="showAdjustments">
        <div role="button" id="visually-impaired-text-reader" class="option aceada-btn" :class="{ 'active': accessibility.navigation.textReader }" @click="accessibility.toggleTextReader()">
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
        <div role="button" id="visually-impaired-dark-contrast" class="contrast aceada-btn" :class="{ 'active': accessibility.color.contrast == 'dark' }" @click="setContrast('dark')">
            <div class="feature-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 512 512" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path d="M223.5 32C100 32 0 132.3 0 256S100 480 223.5 480c60.6 0 115.5-24.2 155.8-63.4c5-4.9 6.3-12.5 3.1-18.7s-10.1-9.7-17-8.5c-9.8 1.7-19.8 2.6-30.1 2.6c-96.9 0-175.5-78.8-175.5-176c0-65.8 36-123.1 89.3-153.3c6.1-3.5 9.2-10.5 7.7-17.3s-7.3-11.9-14.3-12.5c-6.3-.5-12.6-.8-19-.8z"/>
                </svg>
            </div>
            <p class="aceada-feature-title">Dark Contrast</p>
        </div>
        <div role="button" id="visually-impaired-high-contrast" class="contrast aceada-btn" :class="{ 'active': accessibility.color.contrast == 'high' }" @click="setContrast('high')">
            <div class="feature-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 512 512" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path d="M448 256c0-106-86-192-192-192V448c106 0 192-86 192-192zm64 0c0 141.4-114.6 256-256 256S0 397.4 0 256S114.6 0 256 0S512 114.6 512 256z"/>
                </svg>
            </div>
            <p class="aceada-feature-title">High Contrast</p>
        </div>
        <div role="button" id="visually-impaired-magnifier" class="option aceada-btn" :class="{ 'active': accessibility.orientation.magnifier }" @click="accessibility.toggleMagnifier()">
            <div class="feature-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-zoom-in" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"/>
                    <path d="M10.344 11.742c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1 6.538 6.538 0 0 1-1.398 1.4z"/>
                    <path fill-rule="evenodd" d="M6.5 3a.5.5 0 0 1 .5.5V6h2.5a.5.5 0 0 1 0 1H7v2.5a.5.5 0 0 1-1 0V7H3.5a.5.5 0 0 1 0-1H6V3.5a.5.5 0 0 1 .5-.5z"/>
                </svg>
            </div>
            <p class="aceada-feature-title">Magnifier</p>
        </div>
        <div role="button" class="aceada-btn" :class="{ active: accessibility.content.readingFont }" @click="accessibility.toggleReadingFont()">
            <div class="feature-button">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" id="IconChangeColor" height="35" width="35">
                    <g>
                        <path fill="none" d="M0 0h24v24H0z" id="mainIconPathAttribute" stroke-width="35"></path>
                        <path d="M15.246 14H8.754l-1.6 4H5l6-15h2l6 15h-2.154l-1.6-4zm-.8-2L12 5.885 9.554 12h4.892zM3 20h18v2H3v-2z" id="mainIconPathAttribute"></path>
                    </g>
                </svg>
            </div>
            <p class="aceada-feature-title">Readable Font</p>
        </div>
        <div role="button" class="aceada-btn" :class="{ active: accessibility.content.highlightLinks }" @click="accessibility.toggleHighlightLinks()">
            <div class="feature-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-link" viewBox="0 0 16 16">
                    <path d="M6.354 5.5H4a3 3 0 0 0 0 6h3a3 3 0 0 0 2.83-4H9c-.086 0-.17.01-.25.031A2 2 0 0 1 7 10.5H4a2 2 0 1 1 0-4h1.535c.218-.376.495-.714.82-1z"/>
                    <path d="M9 5.5a3 3 0 0 0-2.83 4h1.098A2 2 0 0 1 9 6.5h3a2 2 0 1 1 0 4h-1.535a4.02 4.02 0 0 1-.82 1H12a3 3 0 1 0 0-6H9z"/>
                </svg>
            </div>
            <p class="aceada-feature-title">Highlight Links</p>
        </div>
        <div role="button" class="aceada-btn" id="aceada-highlight-text-btn" :class="{ active: accessibility.content.highlightTitles }" @click="accessibility.toggleHighlightTitles()">
            <div class="feature-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-fonts" viewBox="0 0 16 16" id="IconChangeColor">
                    <path d="M12.258 3h-8.51l-.083 2.46h.479c.26-1.544.758-1.783 2.693-1.845l.424-.013v7.827c0 .663-.144.82-1.3.923v.52h4.082v-.52c-1.162-.103-1.306-.26-1.306-.923V3.602l.431.013c1.934.062 2.434.301 2.693 1.846h.479L12.258 3z" id="mainIconPathAttribute"></path>
                </svg>
            </div>
            <p class="aceada-feature-title">Highlight Titles</p>
        </div>
        <div role="button" class="aceada-btn" :class="{ active: accessibility.content.textMagnifier }" @click="accessibility.toggleTextMagnifier()">
            <div class="flex justify-center items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-zoom-in" viewBox="0 0 16 16"> <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z"/> <path d="M10.344 11.742c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1 6.538 6.538 0 0 1-1.398 1.4z"/> <path fill-rule="evenodd" d="M6.5 3a.5.5 0 0 1 .5.5V6h2.5a.5.5 0 0 1 0 1H7v2.5a.5.5 0 0 1-1 0V7H3.5a.5.5 0 0 1 0-1H6V3.5a.5.5 0 0 1 .5-.5z"/> </svg>
            </div>
            <p class="mt-4 aceada-feature-title">Text Magnifier</p>
        </div>
    </div> -->
</template>
