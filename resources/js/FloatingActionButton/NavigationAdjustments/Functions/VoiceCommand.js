import { onMounted, onBeforeUnmount, ref } from 'vue'
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'
import { readingGuide } from '@/FloatingActionButton/OrientationAdjustments/Functions/ReadingGuide'
import { bigCursor } from '@/FloatingActionButton/OrientationAdjustments/Functions/BigCursor'
import { stopAnimations } from '@/FloatingActionButton/OrientationAdjustments/Functions/StopAnimations'
import { hideImages } from '@/FloatingActionButton/OrientationAdjustments/Functions/HideImages'
import { monochrome } from '@/FloatingActionButton/ColorAdjustments/Functions/Monochrome'
import { fontSizing } from '@/FloatingActionButton/ContentAdjustments/Functions/FontSizing'
import { smartNavigation } from '@/FloatingActionButton/NavigationAdjustments/Functions/SmartNavigation'

const isRecording = ref(false)
var infoShow = true
var values = []
var recognition = null
var currentLinkNodeIndex = 0
var currentInputNodeIndex = 0
const Recognition = window.SpeechRecognition ||
                    window.webkitSpeechRecognition ||
                    window.mozSpeechRecognition ||
                    window.msSpeechRecognition ||
                    window.oSpeechRecognition;
if (Recognition) {
    recognition = new Recognition()
}

onBeforeUnmount(() => {
    if (recognition)
        recognition.stop()
});

export const voiceCommand = {    
    setUp() {
        this.setElements()

        if (recognition) {
            recognition.continuous = true
            recognition.interimResults = false
            recognition.lang = 'English'

            recognition.onstart = () => {
                document.getElementById('aceada-voice-command-message-container').innerText = 'Listening...'
                document.querySelector('#aceada-voice-command-unmuted').style.display = 'block'
                document.querySelector('#aceada-voice-command-muted').style.display ='none'
            }

            recognition.onend = () => {
                document.getElementById('aceada-voice-command-message-container').innerText = 'Not Listening...'
                document.querySelector('#aceada-voice-command-unmuted').style.display = 'none';
                document.querySelector('#aceada-voice-command-muted').style.display = 'block';
            }

            recognition.onerror = (evt) => {
                document.getElementById('aceada-voice-command-message-container').innerText = `Error: ${evt.error}`
            }

            recognition.onresult = (evt) => {
                var finalResult = {};
                for (let i = 0; i < evt.results.length; i++) {
                    const result = evt.results[i]

                    if (result.isFinal) {
                        finalResult = result
                    }
                }

                this.executeCommand(finalResult)
            };
        }
    },

    setElements() {
        this.setLinkElements()
        this.setInputElements()
    },

    setLinkElements() {
        var elements = document.querySelectorAll(['a', 'button'])
        var nodeIndex = 0
        elements.forEach(element => {
            var value = element.innerText.trim().toLowerCase().replace(/[^a-zA-Z0-9 ]/g, '').replace(/\s/g, '')
            if (value) {
                element.setAttribute('aceada-voice-command-link', value)
                element.setAttribute('aceada-voice-command-link-index', `voice-command-link-index-${nodeIndex}`)
                values.push(value)
                nodeIndex++
            }
        });
    },

    setInputElements() {
        var elements = document.querySelectorAll(['textarea', 'input'])
        var nodeIndex = 0
        elements.forEach(element => {
            element.setAttribute('aceada-voice-command-input-index', `voice-command-input-index-${nodeIndex}`)
            element.addEventListener('focusout', e => {
                currentInputNodeIndex = 0
            });
            nodeIndex++
        });
    },

    toggleMic() {
        if (recognition) {
            isRecording.value = !isRecording.value

            isRecording.value ? recognition.start() : recognition.stop()

        } else {
            window.alert('Oops! There\'s a compatibility issue between your browser and voice command. Please try another browser.')
        }
    },

    executeCommand(result) {
        var element = ''
        const transcript = result[0].transcript.trim();

        var messageContainer = document.getElementById('aceada-voice-command-message-container')
        messageContainer.innerText = transcript

        var index = transcript.indexOf(' ');  // Gets the first index where a space occours
        var command = index < 0 ? transcript : transcript.substr(0, index).toLocaleLowerCase(); // Gets the first part
        var text = transcript.substr(index + 1).toLowerCase();  // Gets the text part)

        switch (command) {
            case 'next':
                currentLinkNodeIndex++
                element = document.querySelector(`[aceada-voice-command-link-index=voice-command-link-index-${currentLinkNodeIndex}]`)
                element.focus()

                break;
            case 'previous':
                currentLinkNodeIndex--
                element = document.querySelector(`[aceada-voice-command-link-index=voice-command-link-index-${currentLinkNodeIndex}]`)
                element.focus()

                break;
            case 'focus':
                text = text.replace(/\s/g, '')
                if (values.includes(text)) {
                    element = text ? document.querySelector(`[aceada-voice-command-link=${text}]`) : ''
                    if (element) {
                        element.focus()
                        currentLinkNodeIndex = values.indexOf(text)
                    }
                }
                break;
            case 'scroll':
                var positionY = window.scrollY
                const sbHeight = window.innerHeight * (window.innerHeight / document.body.offsetHeight);
                if (text == 'up') {
                   positionY -= sbHeight
                   window.scrollTo(0, positionY);
                }
                if (text == 'down') {
                   positionY += sbHeight
                   window.scrollTo(0, positionY);
                }
                break;
            case 'click':
                const activeElement = document.activeElement
                activeElement.click()
                break;
            case 'input':
                const textboxes = document.querySelectorAll('input[type="text"], input[type="email"], input[type="url"], input[type="number"], input[type="password"], textarea');
                
                // delete
                if (text == 'delete') {
                    for(let i = 0; i < textboxes.length; i++) {
                        if (textboxes[i] === document.activeElement) {
                            let myString = textboxes[i].value;
                            let words = myString.split(' ');
                            words.pop();
                            myString = words.join(' ');
                            textboxes[i].value = myString;
                        } 
                    }
                }

                // move cursor
                if (text == 'move cursor') {
                    for(let i = 0; i < textboxes.length; i++) {
                        if (textboxes[i] === document.activeElement) {
                            if (textboxes[i].type === 'email') {
                                setTimeout(function () {
                                    textboxes[i].type = 'text';
                                    textboxes[i].setSelectionRange(0, 0);
                                    textboxes[i].type = 'email';
                                }, 100);
                            } else {
                                textboxes[i].focus();
                                textboxes[i].setSelectionRange(0, 0);
                            }
                        } 
                    }
                }

                // space
                if (text == 'space') {
                    for(let i = 0; i < textboxes.length; i++) {
                        if (textboxes[i] === document.activeElement) {
                            textboxes[i].value += ' ';
                        } 
                    }
                }

                // tab
                if (text == 'tab') {
                    if (document.hasFocus()) {
                        let activeElement = document.activeElement;
                        if (['input', 'textare'].includes(activeElement.tagName.toLowerCase())) {
                            let attribute = activeElement.getAttribute('aceada-voice-command-input-index')
                            currentInputNodeIndex = Number(attribute.replace('voice-command-input-index-',''));
                            currentInputNodeIndex++
                        }
                    }

                    element = document.querySelector(`[aceada-voice-command-input-index=voice-command-input-index-${currentInputNodeIndex}]`)
                    if (element != null) {
                        element.focus()
                        currentInputNodeIndex++
                    }
                }

                // @ sign
                if (text == 'at sign') {
                    for(let i = 0; i < textboxes.length; i++) {
                        if (textboxes[i] === document.activeElement) {
                            textboxes[i].value += '@';
                        } 
                    }
                }

                // period
                if (text == 'period') {
                    for(let i = 0; i < textboxes.length; i++) {
                        if (textboxes[i] === document.activeElement) {
                            textboxes[i].value += '.';
                        } 
                    }
                }

                // question mark
                if (text == 'question mark') {
                    for(let i = 0; i < textboxes.length; i++) {
                        if (textboxes[i] === document.activeElement) {
                            textboxes[i].value += '?';
                        } 
                    }
                }

                // exclamation
                if (text == 'exclamation') {
                    for(let i = 0; i < textboxes.length; i++) {
                        if (textboxes[i] === document.activeElement) {
                            textboxes[i].value += '!';
                        } 
                    }
                }

                // pound sign / hashtag / number sign
                if (text == 'pound sign' || text == 'hashtag' || text == 'number sign') {
                    for(let i = 0; i < textboxes.length; i++) {
                        if (textboxes[i] === document.activeElement) {
                            textboxes[i].value += '#';
                        } 
                    }
                }

                // plus sign
                if (text == 'plus sign') {
                    for(let i = 0; i < textboxes.length; i++) {
                        if (textboxes[i] === document.activeElement) {
                            textboxes[i].value += '+';
                        } 
                    }
                }

                break;
            case 'mute':
                isRecording.value = false
                recognition.stop()
                break;
            case 'stop':
                recognition.stop()
                isRecording.value = false
                document.querySelector('#aceada-voice-command-info-pop-up').style.display = 'none'
                document.getElementById('aceada-voice-command-pop-up').style.display = 'none'
                document.getElementById('voice-command').click()

                break;
            case 'number':
                break;
            case 'run':
                const accessibility = useAccessibilityStore()
                // highlight links
                if (text == 'highlight links') {
                    accessibility.toggleHighlightLinks()
                    if (accessibility.content.highlightLinks) {
                        document.body.classList.add('aceada-highlighted-link')
                    }
                }

                // big cursor
                if (text == 'large black cursor') {
                    accessibility.setCursorColor('black')
                    bigCursor.setCursor(accessibility)
                } 
                if (text == 'large white cursor') {
                    accessibility.setCursorColor('white')
                    bigCursor.setCursor(accessibility)
                }

                // highlight headers
                if (text == 'highlight headers') {
                    accessibility.toggleHighlightTitles()
                    if (accessibility.content.highlightTitles) {
                        document.body.classList.toggle('aceada-highlighted-title')
                    }
                }

                // reading guide
                if (text == 'reading guide') {
                    accessibility.toggleReadingGuide()
                    readingGuide.toggle(accessibility)
                }

                // stop animations
                if (text == 'stop animations') {
                    accessibility.toggleStopAnimations()
                    stopAnimations.toggle()
                }

                // stop animations
                if (text == 'hide images') {
                    accessibility.toggleHideImage()
                    hideImages.toggle()
                }

                // monochrome
                if (text == 'monochrome') {
                    accessibility.toggleMonochrome()
                    monochrome.toggle(accessibility)
                }

                // fonsizing
                if (text == 'increase font size') {
                    fontSizing.increaseFont(accessibility)
                }
                if (text == 'decrease font size') {
                    fontSizing.decreaseFont(accessibility)
                }

                // magnifier
                if (text == 'magnifier') {
                    accessibility.toggleMagnifier()
                    if (accessibility.orientation.magnifier) {
                        document.body.classList.toggle('aceada-image-magnifier')
                    }
                }

                // text reader
                if (text == 'text reader') {
                    accessibility.toggleTextReader()
                }

                // smart navigation
                if (text == 'smart navigation') {
                    accessibility.toggleSmartNavigation()
                    smartNavigation.navigationControls(accessibility)
                }

                // readable font
                if (text == 'readable font') {
                    accessibility.toggleReadableFont()
                    if (accessibility.content.readableFont) {
                        document.body.classList.add('aceada-readable-font')
                    }
                }

                // high contrast
                if (text == 'high contrast') {
                    accessibility.setContrast('high')
                }

                // dark contrast
                if (text == 'dark contrast') {
                    accessibility.setContrast('dark')
                }

                // keyboard navigation
                if (text == 'keyboard navigation') {
                    accessibility.toggleKeyboardNavigation()
                }

                // Reset
                if (text == 'reset') {
                    accessibility.toggleMagnifier()
                    accessibility.resetSettings()
                    location.reload();
                    accessibility.toggleVoiceCommand()
                }

                break;
            default:
                break;

        }
    }
}
