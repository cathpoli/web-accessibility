import Bowser from "bowser"

export const isMobile = () => {
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ||
        (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.platform))
}

export const isVoiceNavigationSupported = () => {
    return window.SpeechRecognition ||
            window.webkitSpeechRecognition ||
            window.mozSpeechRecognition ||
            window.msSpeechRecognition ||
            window.oSpeechRecognition
}

/**
 * References:
 * https://developer.mozilla.org/en-US/docs/Web/API/Navigator/userAgent
 * https://github.com/lancedikson/bowser
 */
export const getBrowser = () => {
    return Bowser.getParser(window.navigator.userAgent).getBrowserName(true) // NOTE: returns lowercase
}

/** Reference: https://stackoverflow.com/a/41698614 */
export const isElementVisible = (elem) => {
    if (!(elem instanceof Element)) throw Error('DomUtil: elem is not an element.');
    const style = getComputedStyle(elem);
    if (style.display === 'none') return false;
    if (style.visibility !== 'visible') return false;
    if (style.opacity < 0.1) return false;
    if (elem.offsetWidth + elem.offsetHeight + elem.getBoundingClientRect().height +
        elem.getBoundingClientRect().width === 0) {
        return false;
    }
    const elemCenter   = {
        x: elem.getBoundingClientRect().left + elem.offsetWidth / 2,
        y: elem.getBoundingClientRect().top + elem.offsetHeight / 2
    };
    if (elemCenter.x < 0) return false;
    if (elemCenter.x > document.documentElement.clientWidth) return false;
    if (elemCenter.y < 0) return false;
    // if (elemCenter.y > document.documentElement.clientHeight) return false;
    // if (isNaN(elemCenter.x)) return false;
    // if (isNaN(elemCenter.y)) return false;
    // let pointContainer = document.elementFromPoint(elemCenter.x, elemCenter.y);
    // do {
    //     if (pointContainer === elem) return true;
    // } while (pointContainer = pointContainer.parentNode);
    // return false;
    return true
}
