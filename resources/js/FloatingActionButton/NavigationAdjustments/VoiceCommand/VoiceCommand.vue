<script setup>
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'
import { voiceCommand } from '@/FloatingActionButton/NavigationAdjustments/Functions/VoiceCommand'
import { isVoiceNavigationSupported } from '@/FloatingActionButton/helpers'

const accessibility = useAccessibilityStore()

window.addEventListener('load', () => {
    if (accessibility.navigation.voiceCommand && isVoiceNavigationSupported()) {
        document.getElementById("aceada-voice-command-pop-up").style.display = 'block'
        voiceCommand.setUp()
    }
})

var pushState = history.pushState;
let prevUrl = undefined;
history.pushState = function () {
    pushState.apply(history, arguments);
    
    if (accessibility.navigation.voiceCommand) {
        setInterval(() => {
        const currUrl = window.location.href;
        if (currUrl != prevUrl) {
            // URL changed
            prevUrl = currUrl
            if (accessibility.navigation.voiceCommand && isVoiceNavigationSupported()) {
                document.getElementById("aceada-voice-command-pop-up").style.display = 'block'
                voiceCommand.setUp()
            }
        }
        }, 1000);
    }
};

accessibility.$subscribe((mutation, state) => {
    if (isVoiceNavigationSupported()) {
        document.getElementById("aceada-voice-command-pop-up").style.display = state.navigation.voiceCommand ? 'block' : 'none'
            !state.navigation.voiceCommand
                document.getElementById("aceada-voice-command-info-pop-up").style.display = 'none'
    
        voiceCommand.setUp()
    }
})

</script>

<template>
    <button id="aceada-voice-navigation" class="aceada-adjustment-button" :class="{ 'aceada-adjustment-button-active': accessibility.navigation.voiceCommand }" @click="accessibility.toggleVoiceCommand()" v-if="isVoiceNavigationSupported()">
        <div class="aceada-adjustment-icon">
            <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9.33331 26.8333H18.6666M14 22.1667V26.8333M14 22.1667C16.1659 22.1667 18.2431 21.3063 19.7747 19.7747C21.3062 18.2432 22.1666 16.1659 22.1666 14V11.6667M14 22.1667C11.834 22.1667 9.75682 21.3063 8.22527 19.7747C6.69373 18.2432 5.83331 16.1659 5.83331 14V11.6667M14 1.16667C13.0717 1.16667 12.1815 1.53542 11.5251 2.19179C10.8687 2.84817 10.5 3.73841 10.5 4.66667V14C10.5 14.9283 10.8687 15.8185 11.5251 16.4749C12.1815 17.1313 13.0717 17.5 14 17.5C14.9282 17.5 15.8185 17.1313 16.4749 16.4749C17.1312 15.8185 17.5 14.9283 17.5 14V4.66667C17.5 3.73841 17.1312 2.84817 16.4749 2.19179C15.8185 1.53542 14.9282 1.16667 14 1.16667Z" stroke="#011817" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <p class="aceada-adjustment-title">Voice Navigation</p>
    </button>
</template>
