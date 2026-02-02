<script setup>
import { onMounted } from 'vue';
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'

const accessibility = useAccessibilityStore()

// enable on reload
onMounted(() => {
    if (accessibility.content.highlightLinks)
        document.body.classList.add('aceada-highlighted-link')
});

accessibility.$subscribe((mutation, state) => {
    document.body.classList.toggle('aceada-highlighted-link', state.content.highlightLinks)
})

</script>

<template>
    <div role="button" class="aceada-btn" :class="{ active: accessibility.content.highlightLinks }" @click="accessibility.toggleHighlightLinks()">
        <div class="feature-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-link" viewBox="0 0 16 16">
                <path d="M6.354 5.5H4a3 3 0 0 0 0 6h3a3 3 0 0 0 2.83-4H9c-.086 0-.17.01-.25.031A2 2 0 0 1 7 10.5H4a2 2 0 1 1 0-4h1.535c.218-.376.495-.714.82-1z"/>
                <path d="M9 5.5a3 3 0 0 0-2.83 4h1.098A2 2 0 0 1 9 6.5h3a2 2 0 1 1 0 4h-1.535a4.02 4.02 0 0 1-.82 1H12a3 3 0 1 0 0-6H9z"/>
            </svg>
        </div>
        <p class="aceada-feature-title">Highlight Links</p>
    </div>
</template>
