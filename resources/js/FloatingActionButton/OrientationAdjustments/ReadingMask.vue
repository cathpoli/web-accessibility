<script setup>
import { onMounted } from 'vue'
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'
import { isMobile } from '@/FloatingActionButton/helpers'

const accessibility = useAccessibilityStore()
const maskMargin = 80 // distance between top and bottom mask

// enable on reload
onMounted(() => {
    if (accessibility.orientation.readingMask && ! isMobile()) toggleReadingMask()
});

accessibility.$subscribe((mutation, state) => {
    /** aside from hiding the button on mobile, we also have to check for changes from other parts like user mode */
    if (! isMobile()) toggleReadingMask()
})

const adjustReadingMask = (event) => {
    var cursorYPosition = event.clientY // current vertical position of the cursor
    var screenHeight = window.innerHeight

    // adjust the height of the masks
    var topReadingMaskHeight = cursorYPosition - maskMargin;
    var bottomReadingMaskHeight = (screenHeight - cursorYPosition) - maskMargin;

    document.getElementById('aceada-reading-mask-top').style.height = `${topReadingMaskHeight}px`
    document.getElementById('aceada-reading-mask-bottom').style.height = `${bottomReadingMaskHeight}px`
}

const toggleReadingMask = () => {
    if (! accessibility.orientation.readingMask) {
        document.removeEventListener('mousemove', adjustReadingMask)
        document.querySelectorAll('.aceada-reading-mask').forEach((el) => el.remove())
    } else {
        let topReadingMask = document.createElement('div')
        let bottomReadingMask = document.createElement('div')

        // setup class and id attributes
        topReadingMask.className = 'aceada-reading-mask'
        bottomReadingMask.className = 'aceada-reading-mask'
        topReadingMask.id = 'aceada-reading-mask-top'
        bottomReadingMask.id = 'aceada-reading-mask-bottom'

        // inject mask to body
        document.body.appendChild(topReadingMask)
        document.body.appendChild(bottomReadingMask)

        // add event listener for adjusting mask
        document.addEventListener('mousemove', adjustReadingMask)
    }
};

</script>

<template>
    <button id="aceada-reading-mask" class="aceada-adjustment-button" :class="{ 'aceada-adjustment-button-active': accessibility.orientation.readingMask }" @click="accessibility.toggleReadingMask()" v-if="! isMobile()">
        <div class="aceada-reading-orientation-selected" v-if="accessibility.orientation.readingMask">
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
            <svg width="66" height="28" viewBox="0 0 66 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_206_4327)">
                    <path d="M55.1341 0H61.17C62.8785 0 63.7327 0 64.3829 0.33776C64.9309 0.622386 65.3776 1.06915 65.6622 1.61708C66 2.26729 66 3.12153 66 4.83V23.3407C66 25.0492 66 25.9034 65.6622 26.5537C65.3776 27.1016 64.9309 27.5483 64.3829 27.833C63.7327 28.1707 62.8785 28.1707 61.17 28.1707H55.1341M10.4634 0H4.83C3.12153 0 2.26729 0 1.61708 0.33776C1.06915 0.622386 0.622386 1.06915 0.33776 1.61708C0 2.26729 0 3.12153 0 4.83V23.3407C0 25.0492 0 25.9034 0.33776 26.5537C0.622386 27.1016 1.06915 27.5483 1.61708 27.833C2.26729 28.1707 3.12153 28.1707 4.83 28.1707H10.4634" stroke="#053A37" stroke-width="2" stroke-linecap="square"/>
                    <rect x="4" y="4" width="58" height="20" rx="2" stroke="#053A37" stroke-width="2" stroke-linecap="square"/>
                </g>
                <defs>
                    <clipPath id="clip0_206_4327">
                        <rect width="66" height="28" fill="white"/>
                    </clipPath>
                </defs>
            </svg>
        </div>
        <p class="aceada-adjustment-title">Reading Mask</p>
    </button>
</template>
