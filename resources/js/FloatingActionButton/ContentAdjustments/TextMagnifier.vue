<script setup>
import { onMounted } from 'vue';
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'
import { isMobile } from '@/FloatingActionButton/helpers'

const accessibility = useAccessibilityStore()

// enable on reload
onMounted(() => {
    if (isMobile()) return;
    if (accessibility.content.textMagnifier) setupTooltips()
});

accessibility.$subscribe((mutation, state) => {
    if (isMobile()) return;

    if (state.content.textMagnifier) {
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
    var invalidTagNames = ['nav', 'section', 'ul', 'div'];
    let isValidElement = !invalidTagNames.includes(target.tagName.toLowerCase());

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

    if (target.innerText && !target.closest('#aceada')) {
        if (isValidElement) {

            // target.classList.add('aceada-tooltip');

            if (target.firstChild.nodeType === Node.TEXT_NODE) {
                // target.setAttribute("aceada-aria-label", target.innerText);
                span.innerHTML = target.innerText;
                document.body.appendChild(span);
            } else {
                for (let i = 0; i < target.childNodes.length; i++) {
                    if (target.childNodes[i].nodeType === Node.TEXT_NODE) {
                        span.innerHTML = target.innerText;
                        document.body.appendChild(span);
                        // target.setAttribute("aceada-aria-label", target.innerText);
                        continue;
                    }
                }
            }
        } else {
            if (target.tagName.toLowerCase() == 'div' && target.childNodes.length == 1) {
                // target.setAttribute("aceada-aria-label", target.innerText);
                span.innerHTML = target.innerText;
                document.body.appendChild(span);
            }
        }
    }
}

const removeTooltip = (e) => {
    var tooltip = document.getElementById('aceada-tooltip');
    if (tooltip) tooltip.remove();
}

</script>

<template>
    <button id="aceada-text-magnifier" class="aceada-adjustment-button" :class="{ 'aceada-adjustment-button-active': accessibility.content.textMagnifier }" @click="accessibility.toggleTextMagnifier()" v-if="! isMobile()">
        <div class="aceada-reading-orientation-selected" v-if="accessibility.content.textMagnifier">
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
            <svg width="49" height="49" viewBox="0 0 49 49" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_206_4344)">
                    <path d="M1 13.5V11.5C1 9.17553 1 8.01329 1.23975 7.05616C1.95699 4.19276 4.19276 1.95699 7.05616 1.23975C8.01329 1 9.17553 1 11.5 1V1M1 18V33M15.5 1H31.75M36 1V1C39.7275 1 41.5913 1 43.0615 1.60896C45.0217 2.42092 46.5791 3.97831 47.391 5.93853C48 7.4087 48 9.27247 48 13V13.5M48 18V33M48 37V37C48 39.7911 48 41.1867 47.6555 42.3223C46.8799 44.8791 44.8791 46.8799 42.3223 47.6555C41.1867 48 39.7911 48 37 48H36M31.75 48H16.375M11.5 48V48C9.17553 48 8.01329 48 7.05616 47.7603C4.19276 47.043 1.95699 44.8072 1.23975 41.9438C1 40.9867 1 39.8245 1 37.5V37" stroke="#011817" stroke-width="2"/>
                    <path d="M16 20.6364V18C16 17.4477 16.4477 17 17 17H24.5M33 20.6364V18C33 17.4477 32.5523 17 32 17H24.5M24.5 17V33M19.8636 33H29.1364" stroke="#011817" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </g>
                <defs>
                    <clipPath id="clip0_206_4344">
                    <rect width="49" height="49" fill="white"/>
                    </clipPath>
                </defs>
            </svg>
        </div>
        <p class="aceada-adjustment-title">Text Magnifier</p>
    </button>
</template>
