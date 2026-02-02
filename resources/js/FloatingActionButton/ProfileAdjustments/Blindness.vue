<script setup>
import { onMounted } from 'vue'
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'

const accessibility = useAccessibilityStore();

// enable on reload
onMounted(() => {
    if (accessibility.profile.blindness) {
        document.getElementById('aceada-blindness-checkbox').checked = true;
    }
});

</script>

<template>
    <div class="aceada-user-mode">
        <div class="aceada-user-mode-title">
            <h1>Blindness Mode</h1>
            <span>Screen reader and images are described</span>
        </div>
        <div class="aceada-user-mode-toggle">
            <label class="aceada-switch">
                <input id="aceada-blindness-checkbox" :checked="accessibility.profile.blindness" type="checkbox" @change="accessibility.setBlindnessProfile($event.target.checked)">
                <span class="aceada-slider"></span>
            </label>
        </div>
    </div>
</template>
