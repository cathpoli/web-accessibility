<script setup>
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'
import { onMounted } from 'vue'
const accessibility = useAccessibilityStore()

onMounted(() => {
    if (accessibility.profile.adhdMode) {
        setZoomContent(accessibility.content.zoomContent)
    }
});

accessibility.$subscribe((mutation, state) => {
    if (accessibility.profile.adhdMode) {
        setZoomContent(state.content.zoomContent)
    }
})

const setContrast = (level) => {
    accessibility.setContrast(level === accessibility.color.contrast ? null : level)
}

const setZoomContent = (zoomContent) => {
    var element = document.querySelectorAll('div:not(#aceada)');
    element[0].style.zoom = `${zoomContent}%`;

    // wait for content scaling input to show
    setTimeout(() => {
        //Set input and data set
        var contentInput = document.getElementById('adhd-mode-content-scaling-input');
        //Check if the contentInput is not null, to avoid the javascript error
        if (contentInput !== null) {
            contentInput.value = zoomContent == 100 ? 'Default' : zoomContent - 100+'0%';
            contentInput.dataset.zoomValue = zoomContent;
        }
    }, 500);
}

const zoomInContent = () => {
    var element = document.querySelectorAll('div:not(#aceada)');
    var contentInput = document.getElementById('adhd-mode-content-scaling-input');
    var sizeValue = document.getElementById('adhd-mode-content-scaling-input').value;

    //Compute the zoom value
    var value = sizeValue == 'Default' ? 0 : sizeValue;
    var sum = parseInt(value) + 10;
    var zoomStyleValue = parseInt(contentInput.dataset.zoomValue == 0 ? 100 : contentInput.dataset.zoomValue) + 1;
    element[0].style.zoom = zoomStyleValue+'%';

    //For Firefox
    // element[0].style.MozTransform = "scale(1."+sum+")";
    // element[0].style.MozTransformOrigin = "0 0";

    //Set the input value
    contentInput.value = sum == 0 ? 'Default' : sum+'%';
    contentInput.dataset.zoomValue = zoomStyleValue;

    accessibility.setZoomContent(value == 'Default' ? null : zoomStyleValue);
}

const zoomOutContent = () => {
    //Compute the zoom value
    var sizeValue = document.getElementById('adhd-mode-content-scaling-input').value;
    var value = sizeValue == 'Default' ? 0 : sizeValue;
    var zoomStyleValue = parseInt(accessibility.content.zoomContent == 0 ? 100 : accessibility.content.zoomContent) + (-1);

    accessibility.setZoomContent(value == 'Default' ? null : zoomStyleValue);
}

</script>

<template>
    <div class="aceada-user-mode">
        <div class="aceada-user-mode-title">
            <h1>ADHD Approach Mode</h1>
            <span>Extra Focus, Lesser Distraction</span>
        </div>
        <div class="aceada-user-mode-toggle">
            <label class="aceada-switch">
                <input :checked="accessibility.profile.adhdMode" type="checkbox" @change="accessibility.setADHDModeProfile($event.target.checked)">
                <span class="aceada-slider"></span>
            </label>
        </div>
    </div>

    <!-- <div id="adhd-mode-adjustments" class="options" v-if="accessibility.profile.adhdMode">
        <div role="button" id="adhd-mode-high-contrast" class="contrast aceada-btn" :class="{ 'active': accessibility.color.contrast == 'high' }" @click="setContrast('high')">
            <div class="feature-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 512 512" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path d="M448 256c0-106-86-192-192-192V448c106 0 192-86 192-192zm64 0c0 141.4-114.6 256-256 256S0 397.4 0 256S114.6 0 256 0S512 114.6 512 256z"/>
                </svg>
            </div>
            <p class="aceada-feature-title">High Contrast</p>
        </div>
        <div role="button" id="adhd-mode-reading-mask" class="option aceada-btn" :class="{ 'active': accessibility.orientation.readingMask }" @click="accessibility.toggleReadingMask()">
            <div class="feature-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 576 512" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path d="M528.3 46.5H388.5c-48.1 0-89.9 33.3-100.4 80.3-10.6-47-52.3-80.3-100.4-80.3H48c-26.5 0-48 21.5-48 48v245.8c0 26.5 21.5 48 48 48h89.7c102.2 0 132.7 24.4 147.3 75 .7 2.8 5.2 2.8 6 0 14.7-50.6 45.2-75 147.3-75H528c26.5 0 48-21.5 48-48V94.6c0-26.4-21.3-47.9-47.7-48.1zM242 311.9c0 1.9-1.5 3.5-3.5 3.5H78.2c-1.9 0-3.5-1.5-3.5-3.5V289c0-1.9 1.5-3.5 3.5-3.5h160.4c1.9 0 3.5 1.5 3.5 3.5v22.9zm0-60.9c0 1.9-1.5 3.5-3.5 3.5H78.2c-1.9 0-3.5-1.5-3.5-3.5v-22.9c0-1.9 1.5-3.5 3.5-3.5h160.4c1.9 0 3.5 1.5 3.5 3.5V251zm0-60.9c0 1.9-1.5 3.5-3.5 3.5H78.2c-1.9 0-3.5-1.5-3.5-3.5v-22.9c0-1.9 1.5-3.5 3.5-3.5h160.4c1.9 0 3.5 1.5 3.5 3.5v22.9zm259.3 121.7c0 1.9-1.5 3.5-3.5 3.5H337.5c-1.9 0-3.5-1.5-3.5-3.5v-22.9c0-1.9 1.5-3.5 3.5-3.5h160.4c1.9 0 3.5 1.5 3.5 3.5v22.9zm0-60.9c0 1.9-1.5 3.5-3.5 3.5H337.5c-1.9 0-3.5-1.5-3.5-3.5V228c0-1.9 1.5-3.5 3.5-3.5h160.4c1.9 0 3.5 1.5 3.5 3.5v22.9zm0-60.9c0 1.9-1.5 3.5-3.5 3.5H337.5c-1.9 0-3.5-1.5-3.5-3.5v-22.8c0-1.9 1.5-3.5 3.5-3.5h160.4c1.9 0 3.5 1.5 3.5 3.5V190z"/>
                </svg>
            </div>
            <p class="aceada-feature-title">Reading Mask</p>
        </div>
        <div role="button" id="adhd-mode-stop-animations" class="option aceada-btn" :class="{ 'active': accessibility.orientation.stopAnimations }" @click="accessibility.toggleStopAnimations()">
            <div class="feature-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 512 512" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path d="M328 160h-144C170.8 160 160 170.8 160 184v144C160 341.2 170.8 352 184 352h144c13.2 0 24-10.8 24-24v-144C352 170.8 341.2 160 328 160zM256 0C114.6 0 0 114.6 0 256s114.6 256 256 256s256-114.6 256-256S397.4 0 256 0zM256 464c-114.7 0-208-93.31-208-208S141.3 48 256 48s208 93.31 208 208S370.7 464 256 464z"/>
                </svg>
            </div>
            <p class="aceada-feature-title">Stop Animations</p>
        </div>
        <div id="adhd-content-scaling-adjustment">
            <div class="options">
                <div class="content-scaling-svg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24">
                        <path d="M15 3l2.3 2.3-2.89 2.87 1.42 1.42L18.7 6.7 21 9V3zM3 9l2.3-2.3 2.87 2.89 1.42-1.42L6.7 5.3 9 3H3zm6 12l-2.3-2.3 2.89-2.87-1.42-1.42L5.3 17.3 3 15v6zm12-6l-2.3 2.3-2.87-2.89-1.42 1.42 2.89 2.87L15 21h6z"/>
                    </svg>
                </div>
                <div>
                    <p class="aceada-feature-title">
                        Content Scaling
                    </p>
                </div>
            </div>
            <form class="aceada-form">
                <div class="value-button" id="adhd-mode-content-zoom-out" value="Decrease Value" @click="zoomOutContent()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16"> <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/> </svg>
                </div>
                <input type="" id="adhd-mode-content-scaling-input" class="aceada-input" value="Default" disabled data-zoom-value="0"/>
                <div class="value-button" id="adhd-mode-content-zoom-in" value="Increase Value" @click="zoomInContent()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-up" viewBox="0 0 16 16"> <path fill-rule="evenodd" d="M7.646 4.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1-.708.708L8 5.707l-5.646 5.647a.5.5 0 0 1-.708-.708l6-6z"/> </svg>
                </div>
            </form>
        </div>
    </div> -->
</template>
