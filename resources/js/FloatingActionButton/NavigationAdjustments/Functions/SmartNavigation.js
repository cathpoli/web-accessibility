import { ref } from 'vue';

var showNavigationHelp = ref(true);
export const smartNavigation = {
    navigationControls(accessibility) {
    
        this.removeNavigations()
    
        var inputKey = '';
        document.getElementById("aceada-smart-navigation-help-pop-up").style.display = accessibility.navigation.smartNavigation ? 'block' : 'none'
        if (accessibility.navigation.smartNavigation) {
            var elements = document.querySelectorAll(['a', 'button'])
            var navigationKey = 1
            var body = document.body
            elements.forEach(element => {
                if (!element.closest('#aceada') && !element.closest('#aceada-keyboard-keys')) {
                    let rect = element.getBoundingClientRect()
                    element.classList.add(`aceada-smart-navigation-key-${navigationKey}`)
                    let spanElement = document.createElement('text')
    
                    spanElement.innerText = navigationKey
                    spanElement.classList.add('aceada-smart-navigation-number')
                    spanElement.id = `aceada-smart-navigation-number-${navigationKey}`
    
                    var els = [];
                    var position = 'absolute'
                    var isFixed = false
                    var isStatic = false
                    var isHidden = false
                    var offsetWidth = element.offsetWidth
                    var offsetHeight = element.offsetHeight
                    var target = element
                    while (target) {
                        target = target.parentNode
                        if (target.tagName.toLowerCase() == 'body') {
                            break
                        }
    
                        const style = getComputedStyle(target);
                        if (style.visibility == 'hidden') {
                            isHidden = true
                        }
    
                        if (style.position == 'fixed') {
                            isFixed = true
                        }
                    }
    
                    spanElement.style.position = position
    
                    if (isFixed) {
                        spanElement.style.position = 'fixed'
                    }
    
                    if (!isHidden) {
                        if (rect.x <= screen.availWidth && rect.x > -5 && rect.y > -15) {
                            if (offsetWidth != 0 && offsetHeight != 0) {
                                spanElement.style.top = (rect.y - 15 <= 0  ) ? (rect.y) + 'px' : (rect.y - 15) + 'px'
                                spanElement.style.left = (rect.x - 5) + 'px'
            
                                body.append(spanElement)
                                navigationKey++
                            }
                        }
                    }
                }
            });
    
            document.addEventListener('keydown', (event) => {
                if (event.target.nodeName.toLowerCase() !== 'input' && event.target.nodeName.toLowerCase() !== 'textarea') {
                    if (event.shiftKey && event.code == 'Digit8') {
                        showNavigationHelp = !showNavigationHelp
                        document.getElementById("aceada-smart-navigation-help-pop-up").style.display = showNavigationHelp ? 'block' : 'none'
                    }
    
                    if (event.key >= 0 || event.key <= 9) {
                        inputKey += event.key
                        var navigationScreen = document.getElementById("aceada-smart-navigation-screen");
                        var navigationScreenNum = document.getElementById("aceada-smart-navigation-screen-number");
                        navigationScreen.style.display = 'flex'
                        navigationScreenNum.innerHTML = inputKey;
                        var timer = window.setTimeout(function(){
                            navigationScreen.style.display = 'block'
                            var el = document.getElementsByClassName(`aceada-smart-navigation-key-${inputKey}`)
                            if (el.length > 0) {
                                el[0].click()
                            }
                            navigationScreenNum.innerHTML = '';
                            inputKey = ''
                            window.clearTimeout(timer);
                        }, 2000);
                    }
                }
            })
        }
    },

    removeNavigations() {
        var navigations = document.querySelectorAll('.aceada-smart-navigation-number')
        if (navigations.length > 0) {
            navigations.forEach(element => {
                element.remove()
            })
        }
    },

    dragNavigationHelp() {
        var divElement =  document.getElementById("aceada-smart-navigation-help-pop-up");
        var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
        divElement.onmousedown = dragMouseDown;

        function dragMouseDown(e) {
            e = e || window.event;
            e.preventDefault();
            // get the mouse cursor position at startup:
            pos3 = e.clientX;
            pos4 = e.clientY;
            document.onmouseup = closeDragElement;
            // call a function whenever the cursor moves:
            document.onmousemove = elementDrag;
        }

        function elementDrag(e) {
            e = e || window.event;
            e.preventDefault();
            // calculate the new cursor position:
            pos1 = pos3 - e.clientX;
            pos2 = pos4 - e.clientY;
            pos3 = e.clientX;
            pos4 = e.clientY;
            // set the element's new position:
            divElement.style.top = (divElement.offsetTop - pos2) + "px";
            divElement.style.left = (divElement.offsetLeft - pos1) + "px";
        }

        function closeDragElement() {
            // stop moving when mouse button is released:
            document.onmouseup = null;
            document.onmousemove = null;
        }
    },
}