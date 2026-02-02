export const virtualKeyboard = {
    _elements: {
        main: null,
        keysContainer: null,
        // headerContainer: null,
        keys: [],
        actions: {
            close: null,
        },
    },
    _eventHandlers: {
        oninput: null,
        onclose: null,
        onkeydown: null
    },
    _properties: {
        value: "",
        capsLock: false
    },
    _init() {
        this._remove()

        document.getElementById('aceada-virtual-keyboard-pop-up').style.display = 'none'

        // Create main elements
        this._elements.main = document.createElement("div")

        // Setup main elements
        this._elements.main.classList.add("aceada-keyboard")
        this._elements.main.id = 'aceada-keyboard'

        this._createActionElement()

        this._createKeyboardElement('qwerty')
    },
    _createActionElement() {
        // Setup action elements
        this._elements.actions.close = document.createElement("div");
        this._elements.actions.close.id = 'aceada-keyboard-close-btn'
        this._elements.actions.close.classList.add("aceada-keyboard-close");
        this._elements.actions.close.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16" id="IconChangeColor"> <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" id="mainIconPathAttribute"></path> </svg>'
        
        this._elements.actions.close.addEventListener("click", () => {
            this._close();
            this._triggerEvent("onclose");
        });

        this._elements.main.appendChild(this._elements.actions.close);
    },
    _createKeyboardElement(keyboardType) {
        this._elements.keysContainer = document.createElement("div");

        this._elements.keysContainer.classList.add("aceada-keyboard__keys");
        this._elements.keysContainer.id = 'aceada-keyboard-keys'
        this._elements.keysContainer.appendChild(this._createKeys(keyboardType));

        this._elements.keys = this._elements.keysContainer.querySelectorAll(".aceada-keyboard__key");

        // Add to DOM
        this._elements.main.appendChild(this._elements.keysContainer);
        document.body.appendChild(this._elements.main);

        // Automatically use keyboard for elements with .use-keyboard-input
        document.querySelectorAll("input, textarea").forEach(element => {
            if (!element.closest('#aceada')) {
                if (element === document.activeElement) {
                    this._open(element.value, key => {
                        this._setNewValue(key, element);
                    });
                }

                element.addEventListener("keydown", (evt) => {
                    this._open(element.value, key => {
                        this._setNewValue(key, element);
                    });
                    this._triggerEvent("onkeydown");
                });

                element.addEventListener("click", (evt) => {
                    this._open(element.value, key => {
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
               return '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-backspace-fill" viewBox="0 0 16 16" id="IconChangeColor"> <path d="M15.683 3a2 2 0 0 0-2-2h-7.08a2 2 0 0 0-1.519.698L.241 7.35a1 1 0 0 0 0 1.302l4.843 5.65A2 2 0 0 0 6.603 15h7.08a2 2 0 0 0 2-2V3zM5.829 5.854a.5.5 0 1 1 .707-.708l2.147 2.147 2.146-2.147a.5.5 0 1 1 .707.708L9.39 8l2.146 2.146a.5.5 0 0 1-.707.708L8.683 8.707l-2.147 2.147a.5.5 0 0 1-.707-.708L7.976 8 5.829 5.854z" id="mainIconPathAttribute" fill="#ffffff"></path> </svg>'
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
                        this._properties.value = 'backspace';
                        this._triggerEvent("oninput");
                    });

                    break;

                case "caps":
                    keyElement.classList.add("aceada-keyboard__key--wide", "aceada-keyboard__key--activatable");
                    keyElement.innerHTML = createIconHTML("keyboard_capslock");

                    keyElement.addEventListener("click", () => {
                        this._toggleCapsLock();
                        keyElement.classList.toggle("aceada-keyboard__key--active", this._properties.capsLock);
                        keyElement.classList.toggle("aceada-keyboard__key--dark", this._properties.capsLock);
                    });

                    break;

                case "enter":
                    keyElement.classList.add("aceada-keyboard__key--wide");
                    keyElement.innerHTML = createIconHTML("keyboard_return");

                    keyElement.addEventListener("click", () => {
                        this._properties.value = "\n";
                        this._triggerEvent("oninput");
                    });

                    break;

                case "space":
                    keyElement.classList.add("aceada-keyboard__key--extra-wide");
                    keyElement.innerHTML = createIconHTML("space_bar");

                    keyElement.addEventListener("click", () => {
                        this._properties.value = " ";
                        this._triggerEvent("oninput");
                    });

                    break;

                case "done":
                    keyElement.classList.add("aceada-keyboard__key--wide", "aceada-keyboard__key--dark");
                    keyElement.innerHTML = createIconHTML("check_circle");

                    keyElement.addEventListener("click", () => {
                        this._close();
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
                        this._properties.value = this._properties.capsLock ? key.toUpperCase() : key.toLowerCase();
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
        if (typeof this._eventHandlers[handlerName] == "function") {
            this._eventHandlers[handlerName](this._properties.value);
        }
    },
    _toggleCapsLock() {
        this._properties.capsLock = !this._properties.capsLock;

        for (const key of this._elements.keys) {
            if (key.childElementCount === 0) {
                key.textContent = this._properties.capsLock ? key.textContent.toUpperCase() : key.textContent.toLowerCase();
            }
        }
    },
    _open(initialValue, oninput, onclose, onkeydown) {
        this._properties.value = initialValue || "";
        this._eventHandlers.oninput = oninput;
        this._eventHandlers.onclose = onclose;
        this._eventHandlers.onkeydown = onkeydown;
        this._elements.main.classList.remove("aceada-keyboard--hidden");
        document.getElementById('aceada-virtual-keyboard-pop-up').style.display = 'none'
    },
    _close() {
        this._properties.value = "";
        this._eventHandlers.oninput = oninput;
        this._eventHandlers.onclose = onclose;
        this._eventHandlers.onkeydown = onkeydown;
        this._elements.main.classList.add("aceada-keyboard--hidden");

        document.getElementById('aceada-virtual-keyboard-pop-up').style.display = 'inline-flex'
    },
    _remove() {
        let virtualKeyboardElem = document.getElementById('aceada-keyboard')

        if (virtualKeyboardElem != null)
            virtualKeyboardElem.remove()
    }
}