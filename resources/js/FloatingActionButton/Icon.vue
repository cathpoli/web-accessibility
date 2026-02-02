<script setup>
// import PopUp from '@/FloatingActionButton/PopUp.vue';
import { onMounted, defineAsyncComponent } from 'vue'

/** profile */
import Epilepsy from '@/FloatingActionButton/ProfileAdjustments/Epilepsy.vue';
import ColorBlindness from '@/FloatingActionButton/ProfileAdjustments/ColorBlindness.vue';
import LearningMode from '@/FloatingActionButton/ProfileAdjustments/LearningMode.vue';
import ADHDMode from '@/FloatingActionButton/ProfileAdjustments/ADHDMode.vue';
import Elderly from '@/FloatingActionButton/ProfileAdjustments/Elderly.vue';
import VisuallyImpaired from '@/FloatingActionButton/ProfileAdjustments/VisuallyImpaired.vue';
import MotorSkillsDisorders from '@/FloatingActionButton/ProfileAdjustments/MotorSkillsDisorders.vue';
import Blindness from '@/FloatingActionButton/ProfileAdjustments/Blindness.vue';

/** content */
import TextMagnifier from '@/FloatingActionButton/ContentAdjustments/TextMagnifier.vue';
import TextAlignment from '@/FloatingActionButton/ContentAdjustments/TextAlignment.vue';
import Highlights from '@/FloatingActionButton/ContentAdjustments/Highlights.vue';
// import HighlightLinks from '@/FloatingActionButton/ContentAdjustments/HighlightLinks.vue';
// import HighlightTitles from '@/FloatingActionButton/ContentAdjustments/HighlightTitles.vue';
// import HighlightTexts from '@/FloatingActionButton/ContentAdjustments/HighlightTexts.vue';
import FontSizing from '@/FloatingActionButton/ContentAdjustments/FontSizing.vue';
import ContentScaling from '@/FloatingActionButton/ContentAdjustments/ContentScaling.vue';
import LetterSpacing from '@/FloatingActionButton/ContentAdjustments/LetterSpacing.vue';
import ReadableFont from '@/FloatingActionButton/ContentAdjustments/ReadableFont.vue';
import LineHeightAdjustment from '@/FloatingActionButton/ContentAdjustments/LineHeightAdjustment.vue';
import ImageDescription from '@/FloatingActionButton/ContentAdjustments/ImageDescription.vue';

/** color */
import TextColor from '@/FloatingActionButton/ColorAdjustments/TextColor.vue';
import Monochrome from '@/FloatingActionButton/ColorAdjustments/Monochrome.vue';
import TitleColor from '@/FloatingActionButton/ColorAdjustments/TitleColor.vue';
import BackgroundColor from '@/FloatingActionButton/ColorAdjustments/BackgroundColor.vue';
import Saturation from '@/FloatingActionButton/ColorAdjustments/Saturation.vue';
import Contrast from '@/FloatingActionButton/ColorAdjustments/Contrast.vue';

/** orientation */
import ReadingMask from '@/FloatingActionButton/OrientationAdjustments/ReadingMask.vue';
import HideImage from '@/FloatingActionButton/OrientationAdjustments/HideImage.vue';
import StopAnimations from '@/FloatingActionButton/OrientationAdjustments/StopAnimations.vue';
import BigCursor from '@/FloatingActionButton/OrientationAdjustments/BigCursor.vue';
import ReadingGuide from '@/FloatingActionButton/OrientationAdjustments/ReadingGuide.vue';
import ImageManifier from '@/FloatingActionButton/OrientationAdjustments/ImageManifier.vue';
// import HighlightHover from '@/FloatingActionButton/OrientationAdjustments/HighlightHover.vue';
import MuteSounds from '@/FloatingActionButton/OrientationAdjustments/MuteSounds.vue';
// import ReadMode from '@/FloatingActionButton/OrientationAdjustments/ReadMode.vue';
import VirtualKeyboard from '@/FloatingActionButton/OrientationAdjustments/VirtualKeyboard.vue';
import VirtualKeyboardControl from '@/FloatingActionButton/OrientationAdjustments/VirtualKeyboardControl.vue';

/** accesibility */
import ResetFeatures from '@/FloatingActionButton/AccessibilityAdjustments/ResetFeatures.vue';
import Feedback from '@/FloatingActionButton/AccessibilityAdjustments/Feedback.vue';
import Statement from '@/FloatingActionButton/AccessibilityAdjustments/Statement.vue';
import StatementModal from '@/FloatingActionButton/AccessibilityAdjustments/StatementModal.vue';

/** navigation */
import TextReader from '@/FloatingActionButton/NavigationAdjustments/TextReader/TextReader.vue';
import TextReaderControls from '@/FloatingActionButton/NavigationAdjustments/TextReader/TextReaderControls.vue';
import SmartNavigation from '@/FloatingActionButton/NavigationAdjustments/SmartNavigation/SmartNavigation.vue';
import SmartNavigationHelp from '@/FloatingActionButton/NavigationAdjustments/SmartNavigation/SmartNavigationHelp.vue';
import SmartNavigationScreen from '@/FloatingActionButton/NavigationAdjustments/SmartNavigation/SmartNavigationScreen.vue';
import VoiceCommand from '@/FloatingActionButton/NavigationAdjustments/VoiceCommand/VoiceCommand.vue';
import VoiceCommandControls from '@/FloatingActionButton/NavigationAdjustments/VoiceCommand/VoiceCommandControls.vue';
import KeyboardNavigation from '@/FloatingActionButton/NavigationAdjustments/KeyboardNavigation.vue';

onMounted(() => {
    var aceada = document.getElementById("aceada")
    var trigger = document.getElementById("aceada-trigger-checkbox")
    var widget = document.getElementById("aceada-widget")

    trigger.addEventListener('change', (event) => {
        /** Fix for adjustment options still clickable when popup is closed */
        widget.style.pointerEvents = event.target.checked ? 'all' : 'none'

        /** on first open, remove display none which is fix for the widget flicker */
        if (event.target.checked && window.getComputedStyle(widget, null).display === 'none') {
            document.getElementById("aceada-widget").style.display = null
            document.getElementById("aceada-widget").style.display = ''

            setNavigationAdjustmentsBorderRadius()
        }
    })

    /** Close popup when user clicked outside */
    document.addEventListener('click', (event) => {
        var outsideElement = ! aceada.contains(event.target)

        // check if user clicked outside the widget while widget is active
        if (outsideElement && trigger.checked) document.getElementById("aceada-trigger-checkbox").click()
    })
});

/**
 * This function is mainly for setting the border radius for the navigation adjustment buttons dynamically
 * since we are not showing some options on mobile, the border radius need to be dynamic based on the number of buttons
 *
 * Note: Feel free to improve this code or if possible do it by only using css
 */
const setNavigationAdjustmentsBorderRadius = () => {
    var navigationAdjustments = document.querySelectorAll('#aceada-navigation-adjustments .aceada-adjustment-button')

    // set the first and last buttons' border radius
    navigationAdjustments[0].classList.add('aceada-navigation-adjustments-top-left')
    navigationAdjustments[navigationAdjustments.length - 1].classList.add('aceada-navigation-adjustments-bottom-right')

    // set the borders if buttons only have 1 row
    if (navigationAdjustments.length <= 3) {
        navigationAdjustments[0].classList.add('aceada-navigation-adjustments-bottom-left')
        navigationAdjustments[navigationAdjustments.length - 1].classList.add('aceada-navigation-adjustments-top-right')
    }

    // set the borders if buttons have more than 1 row
    if (navigationAdjustments.length > 3) {
        navigationAdjustments[2].classList.add('aceada-navigation-adjustments-top-right')

        for (let index = 0; index < navigationAdjustments.length; index++) {
            // set the button to have bottom left radius
            if (index % 3 === 0 && navigationAdjustments.length - index < 3)
                navigationAdjustments[index].classList.add('aceada-navigation-adjustments-bottom-left')
        }
    }
}

const closePopUp = () => {
    /** programmatically click checkbox so the event is triggered */
    document.getElementById("aceada-trigger-checkbox").click()
};

const closeTextReaderPopup = () => {
    document.getElementById("aceada-text-reader-pop-up").style.display = "none";
};

// const url = import.meta.env.VITE_APP_URL
// const env = import.meta.env.VITE_APP_ENV
// const filename = import.meta.env.VITE_CSS_FILENAME ?? 'fab'
// const styles = document.createElement('link')

// /** inject css */
// if (env !== 'local') {
//     styles.setAttribute('rel', 'stylesheet')
//     styles.setAttribute('href', `${url}/dist/${filename}.css`)
//     document.head.appendChild(styles)
// }
</script>

<template>
    <div id="aceada-container">
        <!-- trigger -->
        <input id="aceada-trigger-checkbox" type="checkbox" class="aceada-trigger-checkbox" />
        <label id="aceada-trigger" for="aceada-trigger-checkbox">
        <!-- replace the fill with the selectedIconColor. path d value for the buttonIcon -->
            <svg width="55" height="55" viewBox="0 0 55 55" fill="white" xmlns="http://www.w3.org/2000/svg">
                <path d="M27.2576 15.75C29.6717 15.75 31.6326 13.7891 31.6326 11.375C31.6326 8.96089 29.6717 7 27.2576 7C24.8435 7 22.8826 8.96089 22.8826 11.375C22.8826 13.7891 24.8435 15.75 27.2576 15.75Z M41.0078 15.8123L40.9687 15.8202L40.9374 15.828C40.8593 15.8514 40.7812 15.8749 40.7031 15.8983C39.25 16.328 32.1952 18.3123 27.2187 18.3123C22.6016 18.3123 16.1797 16.5937 14.125 16.0077C13.9219 15.9296 13.7109 15.8592 13.5 15.8046C12.0156 15.4139 11 16.9218 11 18.2968C11 19.6639 12.2266 20.3123 13.4609 20.7811V20.8046L20.8984 23.1249C21.6562 23.4139 21.8593 23.7108 21.9609 23.9687C22.2812 24.7968 22.0234 26.4373 21.9374 27.0077L21.4843 30.5233L18.9766 44.2968C18.9687 44.3358 18.9609 44.3749 18.9531 44.4139L18.9374 44.5154C18.7578 45.7733 19.6797 46.9999 21.4374 46.9999C22.9687 46.9999 23.6484 45.9452 23.9374 44.5077C23.9374 44.5077 26.125 32.1952 27.2187 32.1952C28.3124 32.1952 30.5624 44.5077 30.5624 44.5077C30.8593 45.9452 31.5312 46.9999 33.0624 46.9999C34.8202 46.9999 35.75 45.7733 35.5624 44.5077C35.5469 44.3983 35.5234 44.2968 35.5 44.1873L32.9609 30.5389L32.5078 27.0233C32.1797 24.9764 32.4452 24.2968 32.5312 24.1404C32.5312 24.1404 32.539 24.1327 32.539 24.1249C32.625 23.9687 33.0078 23.6171 33.9062 23.2811L40.8828 20.8437C40.9219 20.8358 40.9687 20.8202 41.0078 20.8046C42.2578 20.3358 43.5078 19.6873 43.5078 18.3123C43.5078 16.9373 42.4921 15.4218 41.0078 15.8123Z"/>
            </svg>
        </label>

        <!-- widget -->
        <!-- Note: display none here is important for avoiding widget flicker on load -->
        <div id="aceada-widget" style="display: none">
            <div class="aceada-widget-main">
                <div id="aceada-header">
                    <div role="button" @click="closePopUp" class="aceada-close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                        </svg>
                    </div>
                </div>
                <div class="aceada-hero">
                    <span class="aceada-hero-title">Accessibility Adjustments</span>
                    <div class="aceada-hero-actions">
                        <!-- <ResetFeatures></ResetFeatures> -->
                        <Statement></Statement>
                        <Feedback></Feedback>
                    </div>
                </div>
                <div class="aceada-widget-body">
                    <div class="aceada-adjustments">
                        <!-- <div id="accessibility-adjustments" class="aceada-adjustments-section">
                            <div class="accessibility">
                                <ResetFeatures></ResetFeatures>
                                <Feedback></Feedback>
                            </div>
                        </div> -->
                        <!-- user modes -->
                        <div id="aceada-user-modes-section" class="aceada-adjustments-section">
                            <p class="aceada-section-title">User Modes</p>
                            <div id="aceada-user-modes">
                                <ADHDMode></ADHDMode>
                                <MotorSkillsDisorders></MotorSkillsDisorders>
                                <ColorBlindness></ColorBlindness>
                                <VisuallyImpaired></VisuallyImpaired>
                                <Epilepsy></Epilepsy>
                                <Elderly></Elderly>
                                <LearningMode></LearningMode>
                                <Blindness></Blindness>
                            </div>
                        </div>
                        <!-- website content adjustments -->
                        <div id="aceada-content-adjustments" class="aceada-adjustments-section">
                            <p class="aceada-section-title">Website Content Adjustment</p>
                            <div class="">
                                <p class="aceada-group-title">Reading Orientation</p>
                                <div id="aceada-reading-orientation">
                                    <ReadingGuide></ReadingGuide>
                                    <ReadingMask></ReadingMask>
                                    <ReadableFont></ReadableFont>
                                    <TextMagnifier></TextMagnifier>
                                    <ImageDescription></ImageDescription>
                                </div>
                            </div>
                            <div id="aceada-text-adjustments">
                                <div id="aceada-alignments-highlights">
                                    <TextAlignment></TextAlignment>
                                    <Highlights></Highlights>
                                    <!-- <HighlightLinks></HighlightLinks>
                                    <HighlightTitles></HighlightTitles>
                                    <HighlightTexts></HighlightTexts> -->
                                </div>
                                <div id="aceada-text-settings">
                                    <div>
                                        <p class="aceada-group-title">Text Settings</p>
                                        <div>
                                            <LineHeightAdjustment></LineHeightAdjustment>
                                            <ContentScaling></ContentScaling>
                                            <LetterSpacing></LetterSpacing>
                                            <FontSizing></FontSizing>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- content orientation adjustments -->
                        <div class="aceada-adjustments-section">
                            <p class="aceada-section-title">Content Orientation Adjustment</p>
                            <div id="aceada-orientation-adjustments">
                                <div id="aceada-media-adjustments">
                                    <p class="aceada-group-title">Media Adjustment</p>
                                    <HideImage></HideImage>
                                    <MuteSounds></MuteSounds>
                                    <StopAnimations></StopAnimations>
                                </div>
                                <div>
                                    <div id="aceada-cursor-adjustments">
                                        <p class="aceada-group-title">Cursor Adjustment</p>
                                        <div>
                                            <BigCursor></BigCursor>
                                            <ImageManifier></ImageManifier>
                                        </div>
                                    </div>
                                    <div id="aceada-navigation-adjustments">
                                        <p class="aceada-group-title">Navigation</p>
                                        <div>
                                            <KeyboardNavigation></KeyboardNavigation>
                                            <SmartNavigation></SmartNavigation>
                                            <VoiceCommand></VoiceCommand>
                                            <VirtualKeyboard></VirtualKeyboard>
                                            <TextReader></TextReader>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- color setting -->
                        <div class="aceada-adjustments-section">
                            <p class="aceada-section-title">Color Setting</p>
                            <div id="aceada-color-settings">
                                <div id="aceada-color-adjustments">
                                    <p class="aceada-group-title">Color Adjustment</p>
                                    <BackgroundColor></BackgroundColor>
                                    <TitleColor></TitleColor>
                                    <TextColor></TextColor>
                                </div>
                                <div>
                                    <div id="aceada-saturation-adjustments">
                                        <p class="aceada-group-title">Saturation</p>
                                        <div>
                                            <Saturation></Saturation>
                                        </div>
                                    </div>
                                    <div id="aceada-contrast-adjustments">
                                        <p class="aceada-group-title">Contrast</p>
                                        <div>
                                            <Contrast></Contrast>
                                        </div>
                                        <div>
                                            <Monochrome></Monochrome>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <ResetFeatures></ResetFeatures>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <TextReaderControls></TextReaderControls>
    <SmartNavigationHelp></SmartNavigationHelp>
    <SmartNavigationScreen></SmartNavigationScreen>
    <VoiceCommandControls></VoiceCommandControls>
    <StatementModal></StatementModal>
    <VirtualKeyboardControl></VirtualKeyboardControl>
</template>
