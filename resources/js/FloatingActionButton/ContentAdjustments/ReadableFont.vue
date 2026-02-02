<script setup>
import { onMounted } from 'vue';
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'

const accessibility = useAccessibilityStore()

// enable on reload
onMounted(() => {
    if (accessibility.content.readableFont)
        document.body.classList.add('aceada-readable-font')
});

accessibility.$subscribe((mutation, state) => {
    document.body.classList.toggle('aceada-readable-font', state.content.readableFont)
})

</script>

<template>
    <button id="aceada-readable-font" class="aceada-adjustment-button" :class="{ 'aceada-adjustment-button-active': accessibility.content.readableFont }" @click="accessibility.toggleReadableFont()">
        <div class="aceada-reading-orientation-selected" v-if="accessibility.content.readableFont">
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
            <svg width="45" height="26" viewBox="0 0 45 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_206_4334)">
                    <path d="M23.5348 5.03357H44.5012" stroke="#011817" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M26.0508 14.2588L44.5012 14.2588" stroke="#011817" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M29.4054 22.6454L44.5012 22.6454" stroke="#011817" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M19.2973 26L16.4144 18.036H5.4955L2.61261 26H0L9.72973 0H12.1802L21.8919 26H19.2973ZM10.9009 3.04505L6.23423 15.9099H15.6757L11.009 3.04505H10.9009Z" fill="#011817"/>
                </g>
                <defs>
                    <clipPath id="clip0_206_4334">
                        <rect width="45" height="26" fill="white"/>
                    </clipPath>
                </defs>
            </svg>
        </div>
        <p class="aceada-adjustment-title">Readable Font</p>
    </button>
</template>
