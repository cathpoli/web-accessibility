<script setup>
import { onMounted } from 'vue';
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'

const DOMEvents = ['load','mousemove', 'keypress', 'wheel', 'touchstart']
const accessibility = useAccessibilityStore()

/** Injecting class for our frontend */
onMounted(() => {
    if (accessibility.orientation.highlightHover) document.body.classList.add('aceada-highlight-hover')
});

/** For any other site that is an SPA */
if (accessibility.orientation.highlightHover) {
    DOMEvents.forEach((event) => {
        window.addEventListener(event, function () {
            /** check if body already has the class first */
            if (! document.body.classList.contains('aceada-highlight-hover')) {
                document.body.classList.add('aceada-highlight-hover')
            }
        })
    })
}

accessibility.$subscribe((mutation, state) => {
    document.body.classList.toggle('aceada-highlight-hover', state.orientation.highlightHover)
})
</script>

<template>
    <div role="button" id="highlight-hover" class="option aceada-btn" :class="{ 'active': accessibility.orientation.highlightHover }" @click="accessibility.toggleHighlightHover()">
        <div class="feature-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 448 512" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path d="M384 32C419.3 32 448 60.65 448 96V416C448 451.3 419.3 480 384 480H64C28.65 480 0 451.3 0 416V96C0 60.65 28.65 32 64 32H384zM384 80H64C55.16 80 48 87.16 48 96V416C48 424.8 55.16 432 64 432H384C392.8 432 400 424.8 400 416V96C400 87.16 392.8 80 384 80z"/>
            </svg>
        </div>
        <p class="aceada-feature-title">Hightlight Hover</p>
    </div>
</template>
