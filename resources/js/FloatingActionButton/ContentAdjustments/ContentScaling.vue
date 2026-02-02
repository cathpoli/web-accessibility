<script setup>
import { onMounted, ref } from 'vue';
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'
import { getBrowser, isMobile } from '../helpers'

const accessibility = useAccessibilityStore()
const contentScalingLabel = ref(0)
const browser = getBrowser()

onMounted(() => {
    if (accessibility.content.zoomContent) {
        setZoomContent(accessibility.content.zoomContent)
    }

    const rangeInput = document.getElementById('aceada-content-scaling-range');

    // set event listener for input slider
    rangeInput.addEventListener('change', (event) => {
        setZoomContent(accessibility.content.zoomContent)
    })
});

window.addEventListener('load', function () {
    const rangeInput = document.getElementById('aceada-content-scaling-range');

    // set event listener for input slider
    rangeInput.addEventListener('change', (event) => {
        setZoomContent(accessibility.content.zoomContent)
    })
})

accessibility.$subscribe((mutation, state) => {
    if (state.content.zoomContent) {
        setZoomContent(state.content.zoomContent)
        return;
    }

    const wrapper = document.getElementById('aceada-content-scaling-wrapper')

    if (wrapper) {
        wrapper.style.transformOrigin = 'center top 0px'
        wrapper.style.margin = 'auto'
        wrapper.style.width = `100%`
        wrapper.style.transform = `scale(1)`
    }

    var elements = document.querySelectorAll('body > *:not(#aceada)')

    for(let i = 0; i < elements.length; i++) {
        elements[i].style.zoom = null;
    }

    contentScalingLabel.value = 0
})

const setZoomContent = (zoomContent) => {
    zoomContent = parseInt(zoomContent)

    if (browser === 'firefox') {
        moveBodyElementsInsideWrapperOnFirefox()
        setWrapperStylesOnFirefox(zoomContent)
    } else {
        var elements = document.querySelectorAll('body > *:not(#aceada)')

        for(let i = 0; i < elements.length; i++) {
            elements[i].style.zoom = `${zoomContent + 100}%`;
        }
    }

    // contentScalingLabel.value = zoomContent == 100 ? 'Default' : (zoomContent - 100) * 10 + '%'
    contentScalingLabel.value = zoomContent * 10

    /** specific fix for aceada.com where header for mobile is showing */
    const aceadaHeader = document.getElementById('brxe-fknxkx');
    if (! isMobile() && aceadaHeader) {
        aceadaHeader.style.display = 'none'
    }
}

const zoomInContent = () => {
    const zoomContent = parseInt(accessibility.content.zoomContent)
    accessibility.setZoomContent(zoomContent + 1);
}

const zoomOutContent = () => {
    const zoomContent = parseInt(accessibility.content.zoomContent)
    accessibility.setZoomContent(zoomContent - 1);
}

const moveBodyElementsInsideWrapperOnFirefox = () => {
    if (document.getElementById('aceada-content-scaling-wrapper')) return;

    const wrapper = document.createElement('div')
    wrapper.setAttribute('id', 'aceada-content-scaling-wrapper')
    document.body.appendChild(wrapper)

    const elements = document.querySelectorAll('body > *:not(#aceada):not(#aceada-content-scaling-wrapper)')
    var html = Array.prototype.reduce.call(elements, function(html, node) {
        return html + ( node.outerHTML || node.nodeValue );
    }, "");

    wrapper.innerHTML = html
    elements.forEach(element => {
        element.remove()
    });
}


const setWrapperStylesOnFirefox = (zoomContent) => {
    const width = 100 - (zoomContent * 1.53)
    const transform = 1 + (zoomContent * 0.016)

    const wrapper = document.getElementById('aceada-content-scaling-wrapper')
    wrapper.style.transformOrigin = 'center top 0px'
    wrapper.style.margin = 'auto'
    wrapper.style.width = `${zoomContent <= 0 ? 100 : width}%`
    wrapper.style.transform = `scale(${transform})`
}

</script>

<template>
    <div id="aceada-content-scaling-adjustment">
        <div class="aceada-range-input">
            <p class="aceada-group-title">Content Scaling</p>
            <input id="aceada-content-scaling-range" name="aceada-content-scaling-range" type="range" min="-10" max="10" step="1" v-model="accessibility.content.zoomContent" />
        </div>
        <div class="aceada-button-group">
            <div class="aceada-range-value">
                <span>{{ contentScalingLabel + '%' }}</span>
            </div>
            <div class="aceada-range-actions">
                <button id="aceada-content-zoom-in" class="aceada-increase-range" value="Increase Value" @click="zoomInContent()">
                    <svg xmlns="http://www.w3.org/2000/svg" height="12" viewBox="0 0 320 512">
                        <path d="M182.6 137.4c-12.5-12.5-32.8-12.5-45.3 0l-128 128c-9.2 9.2-11.9 22.9-6.9 34.9s16.6 19.8 29.6 19.8H288c12.9 0 24.6-7.8 29.6-19.8s2.2-25.7-6.9-34.9l-128-128z"/>
                    </svg>
                </button>
                <button id="aceada-content-zoom-out" class="aceada-decrease-range" value="Decrease Value" @click="zoomOutContent()">
                    <svg xmlns="http://www.w3.org/2000/svg" height="12" viewBox="0 0 320 512">
                        <path d="M137.4 374.6c12.5 12.5 32.8 12.5 45.3 0l128-128c9.2-9.2 11.9-22.9 6.9-34.9s-16.6-19.8-29.6-19.8L32 192c-12.9 0-24.6 7.8-29.6 19.8s-2.2 25.7 6.9 34.9l128 128z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>
