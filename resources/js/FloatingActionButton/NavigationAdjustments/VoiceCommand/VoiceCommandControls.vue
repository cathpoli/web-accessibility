<script setup>
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'
import { onMounted, onBeforeUnmount, ref } from 'vue'
import { voiceCommand } from '@/FloatingActionButton/NavigationAdjustments/Functions/VoiceCommand'
const accessibility = useAccessibilityStore()
const tabs = ['general', 'accessibility', 'basic']
var infoShow = true

const closeVoiceCommandPopup = () => {
    document.getElementById('aceada-voice-command-pop-up').style.display = 'none'
    closeVoiceCommandPopupInfo()
}

const closeVoiceCommandPopupInfo = () => {
    document.getElementById('aceada-voice-command-info-pop-up').style.display = 'none'
    infoShow = true;
}

const toggleMic = () => {
    voiceCommand.toggleMic()
}

const toggleInfo = () => {
    document.getElementById('aceada-voice-command-accessibility-tab').classList.remove('active');
    document.getElementById('aceada-voice-command-accessibility-info').style.display = 'none' ;

    if (infoShow) {
        infoShow = false;
        window.speechSynthesis.cancel()
        document.getElementById('aceada-voice-command-info-pop-up').style.display = 'block' ;
        document.getElementById('aceada-voice-command-general-tab').classList.add('active');
        document.getElementById('aceada-voice-command-general-info').style.display = 'block' ;
        return;
    }

    infoShow = true
    document.getElementById('aceada-voice-command-info-pop-up').style.display = 'none' ;
}

const selectTab = (event) => {
    var el = event.target
    var button = el.closest('.command-tab')
    var selectedTab = button.getAttribute('data-aceada-selected-tab')

    var otherTabs = ['general', 'accessibility', 'basic'];
    const index = otherTabs.indexOf(selectedTab);

    if (index > -1) 
        otherTabs.splice(index, 1);

    //Remove the other tabs
    otherTabs.forEach(otherTab => {
        document.getElementById(`aceada-voice-command-${otherTab}-info`).style.display = 'none';
        document.getElementById(`aceada-voice-command-${otherTab}-tab`).classList.remove('active')
    });

    //Set the selected tab
    document.getElementById(`aceada-voice-command-${selectedTab}-info`).style.display = 'block';
    document.getElementById(`aceada-voice-command-${selectedTab}-tab`).classList.add('active')
};

</script>

<template>
    <!-- Voice Command Pop-up -->
    <div id="aceada-voice-command">
        <div id="aceada-voice-command-pop-up" :style="{ display: accessibility.navigation.voiceCommand ? 'block' : 'none', bottom: accessibility.navigation.voiceCommandPosition }">
            <div id="aceada-voice-command-pop-up-minimize" role="button" @click="closeVoiceCommandPopup">
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-dash" viewBox="0 0 16 16"> 
                        <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z"/> 
                    </svg>
                </span>
            </div>
            <div class="aceada-voice-command-pop-up-icons">
                <!-- AUDIO ICON -->
                <div id="aceada-voice-command-mic" role="button" @click="toggleMic">
                    <div id="aceada-voice-command-unmuted">
                        <span class="aceada-seizures-profile">Listening...</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" width="35" height="35">
                            <rect width="256" height="256" fill="none"/><path d="M128,176a48,48,0,0,0,48-48V64a48,48,0,0,0-96,0v64A48,48,0,0,0,128,176Z"/>
                            <path d="M200.4,128.1a8,8,0,0,0-8.8,7,64,64,0,0,1-127.2,0,8,8,0,0,0-8.8-7,7.9,7.9,0,0,0-7.1,8.8A79.7,79.7,0,0,0,120,207.3V232a8,8,0,0,0,16,0V207.3a79.7,79.7,0,0,0,71.5-70.4A7.9,7.9,0,0,0,200.4,128.1Z"/>
                        </svg>
                    </div>
                    <div id="aceada-voice-command-muted">
                        <span class="aceada-seizures-profile">Muted</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="35" height="35">
                            <path d="M383.1 464l-39.1-.0001v-33.77c20.6-2.824 39.98-9.402 57.69-18.72l-43.26-33.91c-14.66 4.65-30.28 7.179-46.68 6.144C245.7 379.6 191.1 317.1 191.1 250.9V247.2L143.1 209.5l.0001 38.61c0 89.65 63.97 169.6 151.1 181.7v34.15l-40 .0001c-17.67 0-31.1 14.33-31.1 31.1C223.1 504.8 231.2 512 239.1 512h159.1c8.838 0 15.1-7.164 15.1-15.1C415.1 478.3 401.7 464 383.1 464zM630.8 469.1l-159.3-124.9c15.37-25.94 24.53-55.91 24.53-88.21V216c0-13.25-10.75-24-23.1-24c-13.25 0-24 10.75-24 24l-.0001 39.1c0 21.12-5.559 40.77-14.77 58.24l-25.72-20.16c5.234-11.68 8.493-24.42 8.493-38.08l-.001-155.1c0-52.57-40.52-98.41-93.07-99.97c-54.37-1.617-98.93 41.95-98.93 95.95l0 54.25L38.81 5.111C34.41 1.673 29.19 0 24.03 0C16.91 0 9.839 3.158 5.12 9.189c-8.187 10.44-6.37 25.53 4.068 33.7l591.1 463.1c10.5 8.203 25.57 6.328 33.69-4.078C643.1 492.4 641.2 477.3 630.8 469.1z"/>
                        </svg>
                    </div>
                </div>
                <div id="aceada-voice-command-message" role="button">
                    <span id="aceada-voice-command-message-container" class="aceada-seizures-profile">Not Listening...</span>
                </div>
                <div id="aceada-voice-command-info" role="button" @click="toggleInfo">
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16"> 
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/> 
                        <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/> 
                    </svg>
                </div>
            </div>
        </div>
        <div id="aceada-voice-command-info-pop-up" :style="{ bottom: accessibility.navigation.voiceCommandInfoPosition }">
            <div id="aceada-voice-command-pop-up-minimize" role="button" @click="closeVoiceCommandPopupInfo">
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-dash" viewBox="0 0 16 16"> 
                        <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z"/> 
                    </svg>
                </span>
            </div>
            <span class="aceada-seizures-profile">
                Command Legend
            </span>
            <div class="aceada-voice-command-pop-up-tabs">
                <div role="button" id="aceada-voice-command-general-tab" class="command-tab aceada-btn" @click="selectTab" data-aceada-selected-tab="general">
                    <span class="aceada-seizures-profile">
                        General
                    </span>
                </div>
                <div role="button" id="aceada-voice-command-accessibility-tab" class="command-tab aceada-btn" @click="selectTab" data-aceada-selected-tab="accessibility">
                    <span class="aceada-seizures-profile">
                        Accessibility
                    </span>
                </div>
                <div role="button" id="aceada-voice-command-basic-tab" class="command-tab aceada-btn" @click="selectTab" data-aceada-selected-tab="basic">
                    <span class="aceada-seizures-profile">
                        Basic Commands
                    </span>
                </div>
                <!-- <div role="button" id="aceada-voice-command-punctation-tab" class="command-tab aceada-btn" @click="selectTab" data-aceada-selected-tab="punctation">
                    <span class="aceada-seizures-profile">
                        Punctations Marks
                    </span>
                </div> -->
                <!-- <div role="button" id="basic-tab" class="command-tab aceada-btn" @click="selectTab" data-aceada-selected-tab="basic">
                    <span class="aceada-seizures-profile">
                        Basic
                    </span>
                </div> -->
            </div>

            <div class="aceada-voice-command-legend">
                <!-- GENERAL -->
                <div id="aceada-voice-command-general-info">
                    <table>
                        <tr>
                            <th class="aceada-legend-command">Command</th>
                            <th class="aceada-legend-explanation">Explanation</th>
                        </tr>
                        <!-- <tr>
                            <td class="aceada-legend-command">
                                <strong>Next</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "next" to move the focus to the next element.
                            </td>
                        </tr>
                        <tr>
                            <td class="aceada-legend-command">
                                <strong>Previous</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "previous" to move the focus to the previous element.
                            </td>
                        </tr> -->
                        <tr>
                            <td class="aceada-legend-command">
                                <strong>Go</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "focus" and then the name of the text of the desired element.
                            </td>
                        </tr>
                        <tr>
                            <td class="aceada-legend-command">
                                <strong>Scroll</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "scroll" and then "down" or "up" (e.g. : "Scroll down").
                            </td>
                        </tr>
                        <tr>
                            <td class="aceada-legend-command">
                                <strong>Click</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "click" to activate the link or button that is focused.
                            </td>
                        </tr>
                        <!-- <tr>
                            <td class="aceada-legend-command">
                                <strong>Input</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "input" and then the name of the form element.
                            </td>
                        </tr> -->
                        <tr>
                            <td class="aceada-legend-command">
                                <strong>Mute</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "mute" to mute the voice recognition ("Muted" state).
                            </td>
                        </tr>
                        <tr>
                            <td class="aceada-legend-command">
                                <strong>Stop</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "stop" to turn off the Voice Command function.
                            </td>
                        </tr>
                        <!-- <tr>
                            <td class="aceada-legend-command">
                                <strong>Number</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "number" and the number's value to focus on it, in "Smart Navigation".
                            </td>
                        </tr> -->
                    </table>
                </div>

                <!-- ACCESSIBILITY TAB -->
                <div id="aceada-voice-command-accessibility-info">
                    <table>
                        <tr>
                            <th class="aceada-legend-command">Command</th>
                            <th class="aceada-legend-explanation">Explanation</th>
                        </tr>
                        <tr>
                            <td class="aceada-legend-command">
                                <strong>Text</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "run text reader" to run the function.
                            </td>
                        </tr>
                        <tr>
                            <td class="aceada-legend-command">
                                <strong>Smart Navigation</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "run smart navigation" to run the function.
                            </td>
                        </tr>
                        <tr>
                            <td class="aceada-legend-command">
                                <strong>Keyboard</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "run keyboard navigation" to run the function.
                            </td>
                        </tr>
                        <!-- <tr>
                            <td class="aceada-legend-command">
                                <strong>Smart</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "turn / run / set smart navigation" to run the function.
                            </td>
                        </tr> -->
                        <!-- <tr>
                            <td class="aceada-legend-command">
                                <strong>Contrast</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "run contrast mode" to run the function.
                            </td>
                        </tr> -->
                        <tr>
                            <td class="aceada-legend-command">
                                <strong>Highlight</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "run highlight links" to run the function.
                            </td>
                        </tr>
                        <tr>
                            <td class="aceada-legend-command">
                                <strong>Highlight</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "run highlight headers" to run the function.
                            </td>
                        </tr>
                        <tr>
                            <td class="aceada-legend-command">
                                <strong>Large Black Cursor</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "run large black cursor" to run the function.
                            </td>
                        </tr>
                        <tr>
                            <td class="aceada-legend-command">
                                <strong>Large White Cursor</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "run large white cursor" to run the function.
                            </td>
                        </tr>
                        <tr>
                            <td class="aceada-legend-command">
                                <strong>Reading Guide</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "run reading guide" to run the function.
                            </td>
                        </tr>
                        <tr>
                            <td class="aceada-legend-command">
                                <strong>Stop Animations</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "run stop animations" to run the function.
                            </td>
                        </tr>
                        <tr>
                            <td class="aceada-legend-command">
                                <strong>Magnifier</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "run magnifier" to run the function.
                            </td>
                        </tr>
                        <tr>
                            <td class="aceada-legend-command">
                                <strong>Hide Images</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "run hide images" to run the function.
                            </td>
                        </tr>
                        <tr>
                            <td class="aceada-legend-command">
                                <strong>Monochrome</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "run monochrome" to run the function.
                            </td>
                        </tr>
                        <tr>
                            <td class="aceada-legend-command">
                                <strong>Increase Font Size</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "run increase font size" to run the function.
                            </td>
                        </tr>
                        <tr>
                            <td class="aceada-legend-command">
                                <strong>Decrease Font Size</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "run decrease font size" to run the function.
                            </td>
                        </tr>
                        <tr>
                            <td class="aceada-legend-command">
                                <strong>Font</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "run readable font" to run the function.
                            </td>
                        </tr>
                        <tr>
                            <td class="aceada-legend-command">
                                <strong>High Contrast</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "run high contrast" to run the function.
                            </td>
                        </tr>
                        <tr>
                            <td class="aceada-legend-command">
                                <strong>Dark Contrast</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "run dark contrast" to run the function.
                            </td>
                        </tr>
                        <tr>
                            <td class="aceada-legend-command">
                                <strong>Reset</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "run reset" to  clear all the selected features except for the voice command.
                            </td>
                        </tr>
                    </table>
                </div>
                <div id="aceada-voice-command-basic-info">
                    <table>
                        <tr>
                            <th class="aceada-legend-command">Command</th>
                            <th class="aceada-legend-explanation">Explanation</th>
                        </tr>
                        <tr>
                            <td class="aceada-legend-command">
                                <strong>Delete</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "input delete" to delete the last written word in the field content.
                            </td>
                        </tr>
                        <tr>
                            <td class="aceada-legend-command">
                                <strong>Move the Cursor</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "input move cursor" to move the cursor to the start of the text element.
                            </td>
                        </tr>
                        <tr>
                            <td class="aceada-legend-command">
                                <strong>Space ()</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "input space" to add space to your text content
                            </td>
                        </tr>
                        <tr>
                            <td class="aceada-legend-command">
                                <strong>Tab ()</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "input tab" to add tab to your text content
                            </td>
                        </tr>
                        <tr>
                            <td class="aceada-legend-command">
                                <strong>At sign @</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "input at sign" to add @ to your text content
                            </td>
                        </tr>
                        <tr>
                            <td class="aceada-legend-command">
                                <strong>Period .</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "input period" to add period to your text content
                            </td>
                        </tr>
                        <tr>
                            <td class="aceada-legend-command">
                                <strong>Question Mark ?</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "input question mark" to add question mark to your text content
                            </td>
                        </tr>
                        <tr>
                            <td class="aceada-legend-command">
                                <strong>Exclamation !</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "input exclamation" to add exclamation point to your text content
                            </td>
                        </tr>
                        <tr>
                            <td class="aceada-legend-command">
                                <strong>Pound Sign #</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "input pound sign/hashtag/number sign" to add # to your text content
                            </td>
                        </tr>
                        <tr>
                            <td class="aceada-legend-command">
                                <strong>Plus Sign +</strong>
                            </td>
                            <td class="aceada-legend-explanation">
                                Say "input plus sign" to add plus sign to your text content
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
