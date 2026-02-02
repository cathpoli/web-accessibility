<script setup>
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'
import { isMobile, isVoiceNavigationSupported } from '@/FloatingActionButton/helpers'

const accessibility = useAccessibilityStore()

</script>

<template>
    <div class="aceada-user-mode">
        <div class="aceada-user-mode-title">
            <h1>Motor Skills Disorder</h1>
            <span v-if="isMobile()">Enable voice command</span>
            <span v-if="! isMobile()">Voice command with smart or keyboard navigation</span>
        </div>
        <div class="aceada-user-mode-toggle">
            <label class="aceada-switch">
                <input :checked="accessibility.profile.motorSkillsDisorders" type="checkbox" @change="accessibility.setMotorSkillsDisordersProfile($event.target.checked)">
                <span class="aceada-slider"></span>
            </label>
        </div>
    </div>

    <div id="motor-skills-disorders-adjustments" class="aceada-user-mode-sub-adjustments" v-if="accessibility.profile.motorSkillsDisorders">
        <button id="aceada-keyboard-navigation" class="aceada-adjustment-button" :class="{ 'aceada-adjustment-button-active': accessibility.navigation.keyboardNavigation }" @click="accessibility.toggleKeyboardNavigation()" v-if="! isMobile()">
            <div class="aceada-adjustment-icon">
                <svg width="46" height="22" viewBox="0 0 46 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15.1742 10.8696L7.49048 10.8696M10.8804 6.95652L7.00621 10.8696L10.8804 14.7826M30.8258 10.8696L38.5095 10.8696M35.1196 14.7826L38.9938 10.8696L35.1196 6.95652M20.1805 18.6957L20.1805 3.30435C20.1805 2.58398 19.5906 2 18.863 2L3.31742 2C2.58983 2 2 2.58398 2 3.30435L2 18.6957C2 19.416 2.58983 20 3.31742 20L18.863 20C19.5906 20 20.1805 19.416 20.1805 18.6957ZM44 18.6957L44 3.30435C44 2.58397 43.4102 2 42.6826 2L27.137 2C26.4094 2 25.8195 2.58398 25.8195 3.30435L25.8195 18.6957C25.8195 19.416 26.4094 20 27.137 20L42.6826 20C43.4102 20 44 19.416 44 18.6957Z" stroke="#011817" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <p class="aceada-adjustment-title">Keyboard Navigation</p>
        </button>
        <button id="aceada-smart-navigation" class="aceada-adjustment-button" :class="{ 'aceada-adjustment-button-active': accessibility.navigation.smartNavigation }" @click="accessibility.toggleSmartNavigation()" v-if="! isMobile()">
            <div class="aceada-adjustment-icon">
                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_206_4188)">
                        <mask id="path-1-inside-1_206_4188" fill="white">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M19.4444 2C19.4444 0.895431 18.5489 0 17.4444 0H9.77772C8.67315 0 7.77772 0.895431 7.77772 2V6.16667C7.77772 7.27124 6.88229 8.16667 5.77772 8.16667H2C0.89543 8.16667 -4.82823e-08 9.06211 0 10.1667L3.35119e-07 17.8333C3.83401e-07 18.9379 0.89543 19.8333 2 19.8333H5.77772C6.88229 19.8333 7.77772 20.7287 7.77772 21.8333V25.9999C7.77772 27.1045 8.67315 27.9999 9.77772 27.9999H17.4444C18.5489 27.9999 19.4444 27.1045 19.4444 25.9999V21.8333C19.4444 20.7287 20.3398 19.8333 21.4444 19.8333H25.9999C27.1045 19.8333 27.9999 18.9379 27.9999 17.8333V10.1667C27.9999 9.0621 27.1045 8.16667 25.9999 8.16667H21.4444C20.3398 8.16667 19.4444 7.27124 19.4444 6.16667V2Z"/>
                        </mask>
                        <path d="M9.77772 2H17.4444V-2H9.77772V2ZM9.77772 6.16667V2H5.77772V6.16667H9.77772ZM2 10.1667H5.77772V6.16667H2V10.1667ZM2 17.8333L2 10.1667H-2L-2 17.8333H2ZM5.77772 17.8333H2V21.8333H5.77772V17.8333ZM9.77772 25.9999V21.8333H5.77772V25.9999H9.77772ZM17.4444 25.9999H9.77772V29.9999H17.4444V25.9999ZM17.4444 21.8333V25.9999H21.4444V21.8333H17.4444ZM25.9999 17.8333H21.4444V21.8333H25.9999V17.8333ZM25.9999 10.1667V17.8333H29.9999V10.1667H25.9999ZM21.4444 10.1667H25.9999V6.16667H21.4444V10.1667ZM17.4444 2V6.16667H21.4444V2H17.4444ZM21.4444 6.16667H17.4444C17.4444 8.37581 19.2352 10.1667 21.4444 10.1667V6.16667ZM29.9999 10.1667C29.9999 7.95753 28.209 6.16667 25.9999 6.16667V10.1667H29.9999ZM25.9999 21.8333C28.209 21.8333 29.9999 20.0424 29.9999 17.8333H25.9999V21.8333ZM21.4444 21.8333V17.8333C19.2352 17.8333 17.4444 19.6242 17.4444 21.8333H21.4444ZM17.4444 29.9999C19.6535 29.9999 21.4444 28.209 21.4444 25.9999H17.4444V29.9999ZM5.77772 25.9999C5.77772 28.209 7.56859 29.9999 9.77772 29.9999V25.9999H9.77772H5.77772ZM5.77772 21.8333V21.8333H9.77772C9.77772 19.6242 7.98686 17.8333 5.77772 17.8333V21.8333ZM-2 17.8333C-2 20.0424 -0.20914 21.8333 2 21.8333V17.8333H2H-2ZM2 6.16667C-0.20914 6.16667 -2 7.95754 -2 10.1667H2H2V6.16667ZM5.77772 6.16667H5.77772V10.1667C7.98686 10.1667 9.77772 8.37581 9.77772 6.16667H5.77772ZM17.4444 2H21.4444C21.4444 -0.20914 19.6535 -2 17.4444 -2V2ZM9.77772 -2C7.56858 -2 5.77772 -0.209138 5.77772 2H9.77772V2V-2Z" fill="#011817" mask="url(#path-1-inside-1_206_4188)"/>
                    </g>
                    <defs>
                        <clipPath id="clip0_206_4188">
                            <rect width="28" height="28" fill="white"/>
                        </clipPath>
                    </defs>
                </svg>
            </div>
            <p class="aceada-adjustment-title">Smart Navigation</p>
        </button>
        <button id="aceada-voice-navigation" class="aceada-adjustment-button" :class="{ 'aceada-adjustment-button-active': accessibility.navigation.voiceCommand }" @click="accessibility.toggleVoiceCommand()" v-if="isVoiceNavigationSupported()">
            <div class="aceada-adjustment-icon">
                <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9.33331 26.8333H18.6666M14 22.1667V26.8333M14 22.1667C16.1659 22.1667 18.2431 21.3063 19.7747 19.7747C21.3062 18.2432 22.1666 16.1659 22.1666 14V11.6667M14 22.1667C11.834 22.1667 9.75682 21.3063 8.22527 19.7747C6.69373 18.2432 5.83331 16.1659 5.83331 14V11.6667M14 1.16667C13.0717 1.16667 12.1815 1.53542 11.5251 2.19179C10.8687 2.84817 10.5 3.73841 10.5 4.66667V14C10.5 14.9283 10.8687 15.8185 11.5251 16.4749C12.1815 17.1313 13.0717 17.5 14 17.5C14.9282 17.5 15.8185 17.1313 16.4749 16.4749C17.1312 15.8185 17.5 14.9283 17.5 14V4.66667C17.5 3.73841 17.1312 2.84817 16.4749 2.19179C15.8185 1.53542 14.9282 1.16667 14 1.16667Z" stroke="#011817" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <p class="aceada-adjustment-title">Voice Navigation</p>
        </button>
    </div>
</template>
