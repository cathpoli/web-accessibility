<script setup>
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'

const accessibility = useAccessibilityStore()

/**
 * This feature is only activated on click so we will not store the status on session.
 * User has to click the pages he/she wants to activate this feature.
 */

 /** TODO: Improve read mode; maybe like read mode on safari */

 accessibility.$subscribe((mutation, state) => {
    if (state.orientation.readableMode) {
        // set it to false again cause we don't have readableMode to activate on load
        accessibility.toggleReadableMode()

        var clone = document.cloneNode(true)

        // remove aceada fab
        const aceada = clone.getElementById('aceada').remove();

        // remove all images
        const imgs = clone.getElementsByTagName('img');

        for (let index = imgs.length - 1; index >= 0; index--) {
            imgs[index].parentNode.removeChild(imgs[index]);
        }

        // remove all svgs
        const svgs = clone.getElementsByTagName('svg');

        for (let index = svgs.length - 1; index >= 0; index--) {
            svgs[index].parentNode.removeChild(svgs[index]);
        }

        // remove all videos
        const videos = clone.getElementsByTagName('video');

        for (let index = videos.length - 1; index >= 0; index--) {
            videos[index].parentNode.removeChild(videos[index]);
        }

        // remove all iframes
        const iframes = clone.getElementsByTagName('iframe');

        for (let index = iframes.length - 1; index >= 0; index--) {
            iframes[index].parentNode.removeChild(iframes[index]);
        }

        // remove all anchor hrefs
        const anchors = clone.getElementsByTagName('a');

        for (let index = anchors.length - 1; index >= 0; index--) {
            anchors[index].removeAttribute('href');
        }

        const readModeWindow = window.open(URL.createObjectURL(new Blob([clone.body.innerHTML], { type: "text/html" })))
    }
})

</script>

<template>
    <div role="button" id="read-mode" class="option aceada-btn" @click="accessibility.toggleReadableMode()">
        <div class="feature-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 576 512" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path d="M249.6 471.5c10.8 3.8 22.4-4.1 22.4-15.5V78.6c0-4.2-1.6-8.4-5-11C247.4 52 202.4 32 144 32C93.5 32 46.3 45.3 18.1 56.1C6.8 60.5 0 71.7 0 83.8V454.1c0 11.9 12.8 20.2 24.1 16.5C55.6 460.1 105.5 448 144 448c33.9 0 79 14 105.6 23.5zm76.8 0C353 462 398.1 448 432 448c38.5 0 88.4 12.1 119.9 22.6c11.3 3.8 24.1-4.6 24.1-16.5V83.8c0-12.1-6.8-23.3-18.1-27.6C529.7 45.3 482.5 32 432 32c-58.4 0-103.4 20-123 35.6c-3.3 2.6-5 6.8-5 11V456c0 11.4 11.7 19.3 22.4 15.5z"/>
            </svg>
        </div>
        <p class="aceada-feature-title">Read Mode</p>
    </div>
</template>
