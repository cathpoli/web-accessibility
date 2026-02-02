<script setup>
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'
import { smartNavigation } from '@/FloatingActionButton/NavigationAdjustments/Functions/SmartNavigation'
const accessibility = useAccessibilityStore()

window.addEventListener('load', () => {
    if (accessibility.navigation.smartNavigation) {
        smartNavigation.dragNavigationHelp()
    }
})

</script>

<template>
    <!-- Smart Navigation Help Pop up -->
    <div id="aceada-smart-navigation-help-pop-up">
        Use asterisk (*) to hide/show help panel <br>
        Use numbers to navigate
    </div>
</template>
