import { defineStore } from 'pinia'

export const useAccessibilityStore = defineStore('accessibility', {
    persist: true,
    state: () => {
        let config = JSON.parse(localStorage.getItem('accessibility'))

        return config ?? {
            profile: {
                epilepsy: false,
                colorBlindness: false,
                learningMode: false,
                elderly: false,
                motorSkillsDisorders: false,
                adhdMode: false,
                visuallyImpaired: false,
                blindness: false,
            },
            color: {
                backgroundColor: null, // blue, purple, red, orange, teal, green, white, black
                textColor: null, // blue, purple, red, orange, teal, green, white, black
                titleColor: null, // blue, purple, red, orange, teal, green, white, black
                monochrome: false,
                saturation: null, // high, low
                contrast: null, // dark, light, high
            },
            content: {
                highlightLinks: false,
                highlightTexts: false,
                highlightTitles: false,
                textAlignment: null, // left, center, middle
                textMagnifier: false,
                zoomContent: 0, // integer
                fontSize: 0,  // integer
                letterSpacing: 0, // integer
                lineHeightAdjustment: 0, // integer
                readableFont: false,
                imageDescription: false,
            },
            orientation: {
                readingMask: false,
                hideImage: false,
                stopAnimations: false,
                cursorColor: null, // white, black
                readingGuide: false,
                magnifier: false, // overlaps zoomContent; !important is used on css
                highlightHover: false,
                muteSounds: false,
                virtualKeyboard: false,
                readableMode: false
            },
            navigation: {
                textReader: false,
                textReaderContinuous: false,
                smartNavigation: false,
                keyboardNavigation: false,
                voiceCommandPosition: '80px',
                voiceCommandInfoPosition: '175px'
            }
        }
    },
    /**
     * TODO: Refactor
     */
    actions: {
        /** accessibility adjustments */
        resetSettings() {
            /** reset profile adjustments */
            this.profile.epilepsy = false
            this.profile.colorBlindness = false
            this.profile.learningMode = false
            this.profile.elderly = false
            this.profile.motorSkillsDisorders = false
            this.profile.adhdMode = false
            this.profile.visuallyImpaired = false
            this.profile.blindness = false

            /** reset color adjustments */
            this.color.backgroundColor = null
            this.color.textColor = null
            this.color.titleColor = null
            this.color.monochrome = false
            this.color.saturation = null
            this.color.contrast = null

            /** reset content adjustments */
            this.content.highlightLinks = false
            this.content.highlightTexts = false
            this.content.highlightTitles = false
            this.content.textAlignment = null
            this.content.textMagnifier = false
            this.content.zoomContent = 0
            this.content.fontSize = 0
            this.content.letterSpacing = 0
            this.content.lineHeightAdjustment = 0
            this.content.readableFont = false
            this.content.imageDescription = false

            /** reset orientation adjustments */
            this.orientation.readingMask = false
            this.orientation.hideImage = false
            this.orientation.stopAnimations = false
            this.orientation.cursorColor = null
            this.orientation.readingGuide = false
            this.orientation.magnifier = false
            this.orientation.highlightHover = false
            this.orientation.muteSounds = false
            this.orientation.virtualKeyboard = false
            this.orientation.readableMode = false

            /** reset navigation adjustments */
            this.navigation.textReader = false
            this.navigation.smartNavigation = false
            this.navigation.voiceCommand = false
            this.navigation.keyboardNavigation = false

            /** comment this so we can use the reset settings for the single selection of the user profiles */
            //location.reload()
        },
        /** profile adjustments */
        toggleEpilepsyProfile() {

            /** single selection */
            if (!this.profile.epilepsy) {
                this.resetSettings()
            }

            this.profile.epilepsy = !this.profile.epilepsy
            this.orientation.stopAnimations = this.profile.epilepsy
        },
        toggleColorBlindnessProfile() {

            /** single selection */
            if (!this.profile.colorBlindness) {
                this.resetSettings()
            }

            this.profile.colorBlindness = !this.profile.colorBlindness
        },
        toggleLearningModeProfile() {

            /** single selection */
            if (!this.profile.learningMode) {
                this.resetSettings()
            }

            this.profile.learningMode = !this.profile.learningMode
            this.content.highlightLinks = this.profile.learningMode
            this.content.highlightTitles = this.profile.learningMode
        },
        setADHDModeProfile(toggle) {

            /** single selection */
            this.resetSettings()

            this.profile.adhdMode = toggle

            this.color.contrast = toggle ? 'high' : null
            this.content.zoomContent = toggle ? 3 : 0 // 3 == 30%
            this.orientation.readingMask = toggle
            this.orientation.stopAnimations = toggle
        },
        setElderlyProfile(toggle) {

            /** single selection */
            this.resetSettings()

            this.profile.elderly = toggle // toggle is true or false

            this.navigation.textReader = toggle
            this.content.textMagnifier = toggle
            this.orientation.magnifier = toggle
        },
        setVisuallyImpairedProfile(toggle) {

            /** single selection */
            this.resetSettings()

            this.profile.visuallyImpaired = toggle // true or false

            // high contrast - optional
            // image desc - we don't have this yet?
            this.color.contrast = toggle === true ? 'dark' : null
            this.navigation.textReader = toggle
            this.orientation.magnifier = toggle
            this.content.readableFont = toggle
            this.content.highlightLinks = toggle
            this.content.highlightTitles = toggle
            this.content.textMagnifier = toggle

            // selects the voice command pop-up and command legend
            const voiceCommand = document.getElementById('aceada-voice-command-pop-up');
            const commandLegend = document.getElementById('aceada-voice-command-info-pop-up');

            if (toggle && this.navigation.voiceCommand && this.navigation.textReader) {
                // when all of them are toggled on
                voiceCommand.style.bottom = '160px';
                commandLegend.style.bottom = '255px';
            } else if (toggle && this.navigation.voiceCommand){
                voiceCommand.style.bottom = '160px';
                commandLegend.style.bottom = '255px';
            } else if (!toggle) {
                voiceCommand.style.bottom = '80px';
                commandLegend.style.bottom = '175px';
            } else {
                /* reset the position of the voice command
                to its normal position when it is the only one toggled on,
                motor skills disorders are toggled on,
                or visually impaired is not toggled on */
                voiceCommand.style.bottom = '80px';
                commandLegend.style.bottom = '175px';
            }
        },
        setMotorSkillsDisordersProfile(toggle) {

            /** single selection */
            this.resetSettings()

            this.profile.motorSkillsDisorders = toggle // toggle is true or false

            // activated by default
            this.navigation.voiceCommand = toggle
            this.navigation.keyboardNavigation = toggle

            // prevent smart navigation and keyboard navigation from activation at the same time
            this.navigation.smartNavigation = false

            /* repeated the voice command positions here
            so that they won't get affected
            by turning the toggle on and off*/

            const voiceCommand = document.getElementById('aceada-voice-command-pop-up');
            const commandLegend = document.getElementById('aceada-voice-command-info-pop-up');

            if (toggle && this.navigation.voiceCommand && this.navigation.textReader) {
                voiceCommand.style.bottom = '160px';
                commandLegend.style.bottom = '255px';
            } else if (toggle && this.navigation.textReader) {
                voiceCommand.style.bottom = '160px';
                commandLegend.style.bottom = '255px';
            } else if (!toggle) {
                voiceCommand.style.bottom = '80px';
                commandLegend.style.bottom = '175px';
            } else {
                voiceCommand.style.bottom = '80px';
                commandLegend.style.bottom = '175px';
            }
        },
        setBlindnessProfile(toggle) {

            /** single selection */
            this.resetSettings()

            this.profile.blindness = toggle // toggle is true or false

            this.navigation.textReader = toggle
            this.content.imageDescription = toggle
        },
        /** color adjustments */
        setBackgroundColor(color) {
            this.color.backgroundColor = color
        },
        setTextColor(color) {
            this.color.textColor = color
        },
        setTitleColor(color) {
            this.color.titleColor = color
        },
        setSaturation(level) {
            this.color.saturation = level
        },
        setContrast(level) {
            // set visuallyImpaired to false if it is active and use disselects one it its adjustments
            if (this.profile.visuallyImpaired && level === null) this.profile.visuallyImpaired = false

            this.color.contrast = level
        },
        toggleMonochrome() {
            this.color.monochrome = !this.color.monochrome
        },
        /** content adjustments */
        toggleHighlightLinks() {
            // set visuallyImpaired to false if it is active and use disselects one it its adjustments
            if (this.profile.visuallyImpaired && this.content.highlightLinks) this.profile.visuallyImpaired = false

            this.content.highlightLinks = !this.content.highlightLinks
        },
        toggleHighlightTexts() {
            this.content.highlightTexts = !this.content.highlightTexts
        },
        toggleHighlightTitles() {
            // set visuallyImpaired to false if it is active and use disselects one it its adjustments
            if (this.profile.visuallyImpaired && this.content.highlightTitles) this.profile.visuallyImpaired = false

            this.content.highlightTitles = !this.content.highlightTitles
        },
        setTextAlignment(alignment) {
            this.content.textAlignment = alignment
        },
        toggleTextMagnifier() {
            // set visuallyImpaired to false if it is active and use disselects one it its adjustments
            if (this.profile.visuallyImpaired && this.content.textMagnifier) this.profile.visuallyImpaired = false

            this.content.textMagnifier = !this.content.textMagnifier
        },
        setZoomContent(value) {
            this.content.zoomContent = value
        },
        setFontSize(value) {
            this.content.fontSize = value
        },
        setLetterSpacing(value) {
            this.content.letterSpacing = value
        },
        setLineHeightAdjustment(value) {
            this.content.lineHeightAdjustment = value // increases/decreases by 10
        },
        toggleReadableFont() {
            // set visuallyImpaired to false if it is active and use disselects one it its adjustments
            if (this.profile.visuallyImpaired && this.content.readableFont) this.profile.visuallyImpaired = false

            this.content.readableFont = !this.content.readableFont
        },
        toggleImageDescription() {
            this.content.imageDescription = !this.content.imageDescription
        },
        /** orientation adjustments */
        toggleReadingMask() {
            this.orientation.readingMask = !this.orientation.readingMask
        },
        toggleHideImage() {
            this.orientation.hideImage = !this.orientation.hideImage
        },
        toggleStopAnimations() {
            this.orientation.stopAnimations = !this.orientation.stopAnimations
        },
        setCursorColor(color) {
            this.orientation.cursorColor = color
        },
        toggleReadingGuide() {
            this.orientation.readingGuide = !this.orientation.readingGuide
        },
        toggleMagnifier() {
            // set visuallyImpaired to false if it is active and use disselects one it its adjustments
            if (this.profile.visuallyImpaired && this.orientation.magnifier) this.profile.visuallyImpaired = false

            this.orientation.magnifier = !this.orientation.magnifier
            this.content.zoomContent = this.orientation.magnifier ? 3 : 0 // 3 == 30%
        },
        toggleHighlightHover() {
            this.orientation.highlightHover = !this.orientation.highlightHover
        },
        toggleMuteSounds() {
            this.orientation.muteSounds = !this.orientation.muteSounds
        },
        toggleVirtualKeyboard() {
            this.orientation.virtualKeyboard = !this.orientation.virtualKeyboard

            const virtualKeyboard = document.getElementById("aceada-virtual-keyboard-pop-up");

            virtualKeyboard.style.display = this.orientation.virtualKeyboard ? 'inline-flex' : 'none';
        },
        toggleReadableMode() {
            this.orientation.readableMode = !this.orientation.readableMode
        },
        /** Navigation adjustments */
        toggleTextReader() {
            // set visuallyImpaired to false if it is active and use disselects one it its adjustments
            if (this.profile.visuallyImpaired && this.navigation.textReader) this.profile.visuallyImpaired = false

            this.navigation.textReader = !this.navigation.textReader

            // selects the voice command pop-up and command legend
            const voiceCommand = document.getElementById('aceada-voice-command-pop-up');
            const commandLegend = document.getElementById('aceada-voice-command-info-pop-up');
            const textReaderPopup = document.getElementById('aceada-text-reader-pop-up');

            if (this.navigation.textReader && this.navigation.voiceCommand && this.profile.visuallyImpaired) {
                // When all of them are toggled on
                voiceCommand.style.bottom = '160px';
                commandLegend.style.bottom = '255px';
            } else if (this.navigation.textReader && this.navigation.voiceCommand) {
                // When both of them are toggled on
                voiceCommand.style.bottom = '160px';
                commandLegend.style.bottom = '255px';
            } else if (!this.navigation.textReader) {
                voiceCommand.style.bottom = '80px';
                commandLegend.style.bottom = '175px';
            } else {
                /* Reset the position of the voice command to its normal position when it is the only one toggled on,
                   motor skills disorders are toggled on, or visually impaired is not toggled on */
                voiceCommand.style.bottom = '80px';
                commandLegend.style.bottom = '175px';
            }

            this.navigation.voiceCommandPosition = voiceCommand.style.bottom;
            this.navigation.voiceCommandInfoPosition = commandLegend.style.bottom;

            textReaderPopup.style.display = this.navigation.textReader ? 'block' : 'none';

            /*** NOTE: Commented out temporarily ***/
            // Store the updated navigation object in localStorage
            // localStorage.setItem('accessibility', JSON.stringify(this.state()));
        },
        setTextReaderContinuous(toggle) {
            this.navigation.textReaderContinuous = toggle // true or false
        },
        toggleSmartNavigation() {
            this.navigation.smartNavigation = !this.navigation.smartNavigation

            // smart and keyboard navigation cannot be used together
            if (this.navigation.keyboardNavigation) this.navigation.keyboardNavigation = false
        },
        toggleVoiceCommand() {
            this.navigation.voiceCommand = !this.navigation.voiceCommand

            /* repeated the voice command positions here
            so that they won't get affected
            by turning the toggle on and off*/

            const voiceCommand = document.getElementById('aceada-voice-command-pop-up');
            const commandLegend = document.getElementById('aceada-voice-command-info-pop-up');

            if (this.navigation.textReader && this.navigation.voiceCommand && this.profile.visuallyImpaired) {
                voiceCommand.style.bottom = '160px';
                commandLegend.style.bottom = '255px';
            } else if (this.navigation.textReader && this.navigation.voiceCommand) {
                voiceCommand.style.bottom = '160px';
                commandLegend.style.bottom = '255px';
            } else if (this.navigation.voiceCommand && !this.navigation.textReader) {
                voiceCommand.style.bottom = '80px';
                commandLegend.style.bottom = '175px';
            } else {
                voiceCommand.style.bottom = '80px';
                commandLegend.style.bottom = '175px';
            }

            voiceCommand.style.display = this.navigation.voiceCommand ? 'block' : 'none';

            this.navigation.voiceCommandPosition = voiceCommand.style.bottom;
            this.navigation.voiceCommandInfoPosition = commandLegend.style.bottom;

            // Store the updated navigation object in localStorage
            // localStorage.setItem('accessibility', JSON.stringify(this.state()));

        },
        toggleKeyboardNavigation() {
            this.navigation.keyboardNavigation = !this.navigation.keyboardNavigation

            // smart and keyboard navigation cannot be used together
            if (this.navigation.smartNavigation) this.navigation.smartNavigation = false
        },
    },
})
