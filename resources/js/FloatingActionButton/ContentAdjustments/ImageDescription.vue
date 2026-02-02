<script setup>
import { onMounted } from 'vue';
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'
import { isMobile } from '@/FloatingActionButton/helpers'

const accessibility = useAccessibilityStore()

// enable on reload
onMounted(() => {
    if (isMobile()) return;
    if (accessibility.content.imageDescription) setupTooltips()
});

accessibility.$subscribe((mutation, state) => {
    if (isMobile()) return;

    if (state.content.imageDescription) {
        setupTooltips()
        return
    }

    // remove event listeners
    document.body.removeEventListener('mouseover', showTooltip);
    document.body.removeEventListener('mouseleave', removeTooltip);
})

const setupTooltips = () => {
    document.body.addEventListener('mouseover', showTooltip);
    document.body.addEventListener('mouseleave', removeTooltip);
}

const showTooltip = (e) => {
    let target = e.target;

    var tooltip = document.querySelectorAll("span[id^='aceada-tooltip']");
    if (tooltip.length > 0) {
        tooltip[0].remove();
    }

    // target.classList.remove('aceada-tooltip');
    var span = document.createElement('span');
    span.classList.add('aceada-tooltip')
    var mousePos = { x: e.clientX, y: e.clientY };

    const localX = e.target.localX;
    const localY = e.target.localY

    span.id = 'aceada-tooltip';
    span.style.left = mousePos.x + 'px';
    span.style.top = mousePos.y + 'px';

    if (target.tagName == 'IMG') {
        span.innerHTML = target.alt;
        document.body.appendChild(span);
    }
}

const removeTooltip = (e) => {
    var tooltip = document.getElementById('aceada-tooltip');
    if (tooltip) tooltip.remove();
}

</script>

<template>
    <button id="aceada-image-description" class="aceada-adjustment-button" :class="{ 'aceada-adjustment-button-active': accessibility.content.imageDescription }" @click="accessibility.toggleImageDescription()">
        <div class="aceada-reading-orientation-selected" v-if="accessibility.content.imageDescription">
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
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" id="IconChangeColor" height="49" width="49">
                <path d="M19,13a1,1,0,0,0-1,1v.39l-1.48-1.48a2.77,2.77,0,0,0-3.93,0l-.7.7L9.41,11.12a2.86,2.86,0,0,0-3.93,0L4,12.6V7A1,1,0,0,1,5,6h8a1,1,0,0,0,0-2H5A3,3,0,0,0,2,7V19a3,3,0,0,0,3,3H17a3,3,0,0,0,3-3V14A1,1,0,0,0,19,13ZM5,20a1,1,0,0,1-1-1V15.43l2.9-2.9a.79.79,0,0,1,1.09,0l3.17,3.17,0,0L15.45,20Zm13-1a1,1,0,0,1-.18.53L13.31,15l.7-.7a.78.78,0,0,1,1.1,0L18,17.22ZM19,2a3,3,0,0,0-2.6,1.5,1,1,0,0,0,.37,1.37,1,1,0,0,0,1.36-.37A1,1,0,0,1,20,5a1,1,0,0,1-1,1,1,1,0,0,0,0,2,3,3,0,0,0,0-6Zm.38,7.08A1,1,0,0,0,18.8,9l-.18.06-.18.09-.15.13A1,1,0,0,0,18,10a1,1,0,0,0,.29.71,1,1,0,0,0,1.42,0A1,1,0,0,0,20,10a1,1,0,0,0-.62-.92Z" id="mainIconPathAttribute"></path>
            </svg>
        </div>
        <p class="aceada-adjustment-title">Image Description</p>
    </button>
</template>
