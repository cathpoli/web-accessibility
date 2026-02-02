<script setup>
const DOMEvents = ['load','mousemove', 'keypress', 'wheel', 'touchstart']
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'

const accessibility = useAccessibilityStore();
var pushState = history.pushState;
let prevUrl = undefined;
history.pushState = function () {
    pushState.apply(history, arguments);
    DOMEvents.forEach((event) => {
    window.addEventListener(event, function () {
        const currUrl = window.location.href;
        if (currUrl != prevUrl) {
            // URL changed
            prevUrl = currUrl;
            Keyboard.reset()
        }
    })
})
};

const toggleVirtualKeyboard = () => {
    Keyboard.init()
}

const reset = () => {
    let virtualKeyboardElem = document.getElementById('aceada-keyboard')

    if (virtualKeyboardElem != null)
        virtualKeyboardElem.remove()
    
};

const Keyboard = {
    elements: {
        main: null,
        keysContainer: null,
        // headerContainer: null,
        keys: [],
        actions: {
            close: null,
        },
    },

    eventHandlers: {
        oninput: null,
        onclose: null,
        onkeydown: null
    },

    properties: {
        value: "",
        capsLock: false
    },

    init() {
        document.getElementById('aceada-virtual-keyboard-pop-up').style.display = 'none'
        this.reset()

        // Create main elements
        this.elements.main = document.createElement("div");

        // Setup main elements
        this.elements.main.classList.add("aceada-keyboard");
        this.elements.main.id = 'aceada-keyboard';

        this._createActionElement()

        this._createKeyboardElement('qwerty')
    },

    _createActionElement() {
        // Setup action elements
        this.elements.actions.close = document.createElement("div");
        this.elements.actions.close.id = 'aceada-keyboard-close-btn'
        this.elements.actions.close.classList.add("aceada-keyboard-close");
        this.elements.actions.close.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16" id="IconChangeColor"> <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" id="mainIconPathAttribute"></path> </svg>'

        this.elements.actions.close.addEventListener("click", () => {
            this.close();
            this._triggerEvent("onclose");
        });

        this.elements.main.appendChild(this.elements.actions.close);
    },

    _createKeyboardElement(keyboardType) {
        this.elements.keysContainer = document.createElement("div");
        
        this.elements.keysContainer.classList.add("aceada-keyboard__keys");
        this.elements.keysContainer.id = 'aceada-keyboard-keys'
        this.elements.keysContainer.appendChild(this._createKeys(keyboardType));

        this.elements.keys = this.elements.keysContainer.querySelectorAll(".aceada-keyboard__key");

        // Add to DOM
        this.elements.main.appendChild(this.elements.keysContainer);
        document.body.appendChild(this.elements.main);

        // Automatically use keyboard for elements with .use-keyboard-input
        document.querySelectorAll("input, textarea").forEach(element => {
            if (!element.closest('#aceada')) {
                if (element === document.activeElement) {
                    this.open(element.value, key => {
                        this._setNewValue(key, element);
                    });
                }

                element.addEventListener("keydown", (evt) => {
                    this.open(element.value, key => {
                        this._setNewValue(key, element);
                    });
                    this._triggerEvent("onkeydown");
                });

                element.addEventListener("click", (evt) => {
                    this.open(element.value, key => {
                       this._setNewValue(key, element);
                    });
                });
            }
        });
    },

    _setNewValue(key, element) {
        var currentValue = element.value
        var selectionStart = element.selectionStart ? element.selectionStart : currentValue.length
        var selectionEnd = element.selectionEnd ? element.selectionEnd : currentValue.length
        var startingValue = currentValue.substr(0, selectionEnd)
        var endingValue = currentValue.substr(selectionEnd, selectionEnd.length)
        var newValue = startingValue + key + endingValue;
        switch (key) {
            case "backspace":
                if (selectionStart === selectionEnd) {
                    if (window.getSelection().toString()) {
                        var selection = window.getSelection().toString();
                        element.value = currentValue.replace(selection, '');
                        selectionEnd = selectionStart
                    } else {
                        element.value = startingValue.substring(0, startingValue.length - 1) + endingValue
                        selectionStart -= 1
                        selectionEnd -= 1
                    }
                } else {
                    var toRemove = selectionEnd - selectionStart
                    element.value = startingValue.substring(0, startingValue.length - toRemove) + endingValue
                    selectionEnd = selectionStart
                }

                break;

            case "caps":
            case "done":
            case "!@#":
            case "abc":

                break;

            default:
                element.value = newValue;
                selectionStart += 1
                selectionEnd += 1

                break;
        }
        if (element.type !== 'email') {
            element.setSelectionRange(selectionStart, selectionEnd);
        }
        element.focus();
        element.dispatchEvent(new Event('input'));
    },

    _createKeys(keyboardType) {
        const fragment = document.createDocumentFragment();

        var keyLayout = [
            "1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "backspace",
            "q", "w", "e", "r", "t", "y", "u", "i", "o", "p",
            "caps", "a", "s", "d", "f", "g", "h", "j", "k", "l", "enter",
            "z", "x", "c", "v", "b", "n", "m", ",", ".", "?",
            "!@#", "space", "done"
        ];

        if (keyboardType === 'special') {
            keyLayout = [
                "@", "#", "&", "_", "-", "(", ")", "=", "backspace",
                "%", "\"", "*", "'", ":", "/", "!", "?",
                "+", "[", "]", "{", "}", "<", ">", "^", "enter",
                 "`", ";", "\\", "|", ",", ".", "?",
                "abc", "space", "done"
            ];
        }

        // Creates HTML for an icon
        const createIconHTML = (icon_name) => {
            if (icon_name == 'backspace') {
               return '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-backspace-fill" viewBox="0 0 16 16" id="IconChangeColor"><path d="M15.683 3a2 2 0 0 0-2-2h-7.08a2 2 0 0 0-1.519.698L.241 7.35a1 1 0 0 0 0 1.302l4.843 5.65A2 2 0 0 0 6.603 15h7.08a2 2 0 0 0 2-2V3zM5.829 5.854a.5.5 0 1 1 .707-.708l2.147 2.147 2.146-2.147a.5.5 0 1 1 .707.708L9.39 8l2.146 2.146a.5.5 0 0 1-.707.708L8.683 8.707l-2.147 2.147a.5.5 0 0 1-.707-.708L7.976 8 5.829 5.854z" id="mainIconPathAttribute" fill="#ffffff"></path> </svg>'
            }

            if (icon_name == 'keyboard_capslock') {
               return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" id="IconChangeColor" height="20" width="20"><path d="M12 8.41L16.59 13 18 11.59l-6-6-6 6L7.41 13 12 8.41zM6 18h12v-2H6v2z" id="mainIconPathAttribute" fill="#ffffff"></path></svg>'
            }

            if (icon_name == 'check_circle') {
               return '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16" id="IconChangeColor"> <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" id="mainIconPathAttribute" fill="#ffffff"></path> </svg>'
            }

            if (icon_name == 'keyboard_return') {
               return '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-return-left" viewBox="0 0 16 16" id="IconChangeColor"> <path fill-rule="evenodd" d="M14.5 1.5a.5.5 0 0 1 .5.5v4.8a2.5 2.5 0 0 1-2.5 2.5H2.707l3.347 3.346a.5.5 0 0 1-.708.708l-4.2-4.2a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 8.3H12.5A1.5 1.5 0 0 0 14 6.8V2a.5.5 0 0 1 .5-.5z" id="mainIconPathAttribute" fill="#ffffff"></path> </svg>'
            }

            if (icon_name == 'space_bar') {
               return '<svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24" id="IconChangeColor" height="20" width="20"><path d="M21,9c-0.6,0-1,0.4-1,1v3H4v-3c0-0.6-0.4-1-1-1s-1,0.4-1,1v4c0,0.6,0.4,1,1,1h18c0.6,0,1-0.4,1-1v-4C22,9.4,21.6,9,21,9z" id="mainIconPathAttribute" fill="#ffffff"></path></svg>'
            }
        };

        keyLayout.forEach(key => {
            const keyElement = document.createElement("button");
            const insertLineBreak = ["backspace", "p", "enter", "?"].indexOf(key) !== -1;

            // Add attributes/classes
            keyElement.setAttribute("type", "button");
            keyElement.classList.add("aceada-keyboard__key");
            keyElement.id = `ace-ada-key-${key}`

            switch (key) {
                case "backspace":
                    keyElement.classList.add("aceada-keyboard__key--wide");
                    keyElement.innerHTML = createIconHTML("backspace");

                    keyElement.addEventListener("click", () => {
                        this.properties.value = 'backspace';
                        this._triggerEvent("oninput");
                    });

                    break;

                case "caps":
                    keyElement.classList.add("aceada-keyboard__key--wide", "aceada-keyboard__key--activatable");
                    keyElement.innerHTML = createIconHTML("keyboard_capslock");

                    keyElement.addEventListener("click", () => {
                        this._toggleCapsLock();
                        keyElement.classList.toggle("aceada-keyboard__key--active", this.properties.capsLock);
                    });

                    break;

                case "enter":
                    keyElement.classList.add("aceada-keyboard__key--wide");
                    keyElement.innerHTML = createIconHTML("keyboard_return");

                    keyElement.addEventListener("click", () => {
                        this.properties.value = "\n";
                        this._triggerEvent("oninput");
                    });

                    break;

                case "space":
                    keyElement.classList.add("aceada-keyboard__key--extra-wide");
                    keyElement.innerHTML = createIconHTML("space_bar");

                    keyElement.addEventListener("click", () => {
                        this.properties.value = " ";
                        this._triggerEvent("oninput");
                    });

                    break;

                case "done":
                    keyElement.classList.add("aceada-keyboard__key--wide", "aceada-keyboard__key--dark");
                    keyElement.innerHTML = createIconHTML("check_circle");

                    keyElement.addEventListener("click", () => {
                        this.close();
                        this._triggerEvent("onclose");
                    });

                    break;
                case "!@#":
                    keyElement.textContent = key;

                    keyElement.addEventListener("click", () => {
                        let keyboardElement = document.getElementById('aceada-keyboard-keys')
                        keyboardElement.remove()
                        this._createKeyboardElement('special')
                    });

                    break;
                case "abc":
                    keyElement.textContent = key;

                    keyElement.addEventListener("click", () => {
                        let keyboardElement = document.getElementById('aceada-keyboard-keys')
                        keyboardElement.remove()
                        this._createKeyboardElement('qwerty')
                    });

                    break;

                default:
                    keyElement.textContent = key.toLowerCase();

                    keyElement.addEventListener("click", () => {
                        this.properties.value = this.properties.capsLock ? key.toUpperCase() : key.toLowerCase();
                        this._triggerEvent("oninput");
                    });

                    break;
            }

            fragment.appendChild(keyElement);

            if (insertLineBreak) {
                fragment.appendChild(document.createElement("br"));
            }
        });

        return fragment;
    },

    _triggerEvent(handlerName) {
        if (typeof this.eventHandlers[handlerName] == "function") {
            this.eventHandlers[handlerName](this.properties.value);
        }
    },

    _toggleCapsLock() {
        this.properties.capsLock = !this.properties.capsLock;

        for (const key of this.elements.keys) {
            if (key.childElementCount === 0) {
                key.textContent = this.properties.capsLock ? key.textContent.toUpperCase() : key.textContent.toLowerCase();
            }
        }
    },

    open(initialValue, oninput, onclose, onkeydown) {
        this.properties.value = initialValue || "";
        this.eventHandlers.oninput = oninput;
        this.eventHandlers.onclose = onclose;
        this.eventHandlers.onkeydown = onkeydown;
        this.elements.main.classList.remove("aceada-keyboard--hidden");

        document.getElementById('aceada-virtual-keyboard-pop-up').style.display = 'none'
    },

    close() {
        this.properties.value = "";
        this.eventHandlers.oninput = oninput;
        this.eventHandlers.onclose = onclose;
        this.eventHandlers.onkeydown = onkeydown;
        this.elements.main.classList.add("aceada-keyboard--hidden");

        document.getElementById('aceada-virtual-keyboard-pop-up').style.display = 'inline-flex'
    },
    reset() {
        let virtualKeyboardElem = document.getElementById('aceada-keyboard')

        if (virtualKeyboardElem != null)
            virtualKeyboardElem.remove()
    }
};
</script>

<template>
    <div id="aceada-virtual-keyboard-pop-up"  :style="{ display: accessibility.orientation.virtualKeyboard ? 'inline-flex' : 'none' }">
        <button @click="toggleVirtualKeyboard">
            <div class="aceada-adjustment-icon row">
                <div class="column">
                    <svg width="35" height="25" viewBox="0 0 43 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.52212 10.069H9.93805M12.6991 10.25H15.115M17.7035 10.25H20.1195M22.8805 10.25H25.2965M28.0575 10.25H30.3009M33.0619 10.25H35.3053M7.52212 15.1379H9.93805M12.6991 15.319H15.115M17.7035 15.319H20.1195M22.8805 15.319H25.2965M28.0575 15.319H30.3009M33.0619 15.319H35.3053M7.52212 20.3879H9.93805M12.6991 20.569H30.3009M33.0619 20.569H35.3053M4.0708 26H38.9292C40.0729 26 41 25.0274 41 23.8276V7.17241C41 5.97262 40.0729 5 38.9292 5H4.0708C2.92713 5 2 5.97262 2 7.17241V23.8276C2 25.0274 2.92713 26 4.0708 26Z" stroke="#1ad2c7" stroke-width="2"/>
                    </svg>
                </div>
                <div class="column">
                    <p class="aceada-adjustment-title"><strong>Virtual Keyboard</strong></p>
                </div>
            </div>
        </button>
    </div>
</template>