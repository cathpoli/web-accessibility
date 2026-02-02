
<script setup>
import { onMounted } from 'vue'
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'

const accessibility = useAccessibilityStore()

// enable on reload
window.addEventListener('load', function () {
    if (accessibility.orientation.muteSounds) muteSounds(true);
});

/**
 * Workaround:
 * For SPAs, the window is not reloading so we're gonna need to know if the url was changed for muting sounds
 *
 */
let prevUrl = undefined;
setInterval(() => {
  const currUrl = window.location.href;
  if (currUrl != prevUrl) {
    // URL changed
    prevUrl = currUrl;
    if (accessibility.orientation.muteSounds) {
        muteSounds(true)
    }
  }
}, 60);

const toggleMuteSounds = () => {
    accessibility.toggleMuteSounds()

    if (accessibility.orientation.muteSounds) {
        muteSounds(false);
    } else {
        unmuteSounds();
    }
};

const muteSounds = (onLoad) => {
    var elems = document.querySelectorAll("video, audio, iframe")
    //Check if the elems variable is not empty
    if (elems.length !== 0) {
        elems.forEach(element => {
            if (element.tagName.toLowerCase() == 'iframe') {
                // let src = element.getAttribute('src') + '?mute=1';
                var originalURL = element.getAttribute('src');
                if (originalURL !== null) {
                    let src = removeParam(['mute', 'autoplay'], originalURL);
                    var srcParams = src.split('?')[1];
                    if (srcParams != null) {
                        src = src + '&mute=1';
                    } else {
                        src = src + '?mute=1';
                    }

                    if (!onLoad) {
                        src = src + '&autoplay=1'
                    }
                    element.setAttribute('src', src);
                }
            } else {
                element.muted = true;
            }
        });
    }
};

const unmuteSounds = () => {
    var elems = document.querySelectorAll("video, audio, iframe")
    //Check if the elems variable is not empty
    if (elems.length !== 0) {
        elems.forEach(element => {
            if (element.tagName.toLowerCase() == 'iframe') {
                var originalURL = element.getAttribute('src');
                if (originalURL !== null) {
                    let src = removeParam(['mute'], originalURL);
                    element.setAttribute('src', src);
                }
            } else {
                element.muted = false;
            }
        });
    }
};

const removeParam = (keys, sourceURL) => {
    var rtn = sourceURL.split("?")[0],
        param,
        params_arr = [],
        queryString = (sourceURL.indexOf("?") !== -1) ? sourceURL.split("?")[1] : "";
    if (queryString !== "") {
        params_arr = queryString.split("&");
        for (var i = params_arr.length - 1; i >= 0; i -= 1) {
            param = params_arr[i].split("=")[0];
            keys.forEach(key => {
                if (param === key) {
                    params_arr.splice(i, 1);
                }
            });
        }
        if (params_arr.length) rtn = rtn + "?" + params_arr.join("&");
    }
    return rtn;
}

</script>

<template>
    <button id="aceada-mute-sounds" class="aceada-adjustment-button" :class="{ 'aceada-adjustment-button-active': accessibility.orientation.muteSounds }" @click="toggleMuteSounds">
        <div class="aceada-adjustment-icon">
            <svg width="59" height="59" viewBox="0 0 59 59" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="30" cy="30" r="24" stroke="black" stroke-width="2"/>
                <path d="M21 26.7147V33.2853C21 33.7389 21.3731 34.1066 21.8333 34.1066H27.6667L33.1548 39.5156C34.2048 40.5504 36 39.8175 36 38.3541V21.6459C36 20.1825 34.2048 19.4496 33.1548 20.4844L27.6667 25.8934H21.8333C21.3731 25.8934 21 26.2611 21 26.7147Z" stroke="#011817" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <p class="aceada-adjustment-title">Mute Sounds</p>
    </button>
</template>
