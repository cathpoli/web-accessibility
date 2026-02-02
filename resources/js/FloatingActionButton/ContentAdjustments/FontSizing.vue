<script setup>
import { ref, onMounted } from 'vue';
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'

const accessibility = useAccessibilityStore()
const tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'a', 'span', 'li', 'table', 'label', 'button', 'th', 'td', 'tr', 'input', 'div']
// const fontSizingLabel = ref('Default')
const fontSizingLabel = ref(0)

// enable on reload
let prevUrl = undefined;
setInterval(() => {
    const currUrl = window.location.href;
    if (currUrl != prevUrl) {
        // URL changed
        prevUrl = currUrl;
        if (accessibility.content.fontSize) {
            //Set the font attribute on page load
            setFontAttribute()
            
            // increase or decrease by size saved on session
            adjustFontSizing(accessibility.content.fontSize);
        }
    }
}, 1000);

// accessibility.$subscribe((mutation, state) => {
//     adjustFontSizing(state.content.fontSize)
// })

window.addEventListener('load', function () {
    //Set the font attribute on page load
    setFontAttribute()
    
    const rangeInput = document.getElementById('aceada-font-sizing-range');

    // set event listener for input slider
    rangeInput.addEventListener('change', (event) => {
        const value = Number(event.target.value)
        accessibility.setFontSize(value)
        adjustFontSizing(value)
    })
})

onMounted(() => {
    //Set the font attribute on page load
    setFontAttribute()

    const rangeInput = document.getElementById('aceada-font-sizing-range');

    // set event listener for input slider
    rangeInput.addEventListener('change', (event) => {
        const value = Number(event.target.value)
        accessibility.setFontSize(value)
        adjustFontSizing(value)
    })
});

const setFontAttribute = () => {
    tags.forEach(tag => {
        var elements = document.querySelectorAll(`${tag}:not(#aceada)`);
        for(let i = 0; i < elements.length; i++) {
            var currentSize = window.getComputedStyle(elements[i], null).getPropertyValue('font-size');
            elements[i].setAttribute('data-original-font-size', currentSize);
        }
    });
}

const increaseFont = () => {
    accessibility.setFontSize(parseInt(accessibility.content.fontSize) + 1)

    // increase by 1 size
    adjustFontSizing(accessibility.content.fontSize);
}

const decreaseFont = () => {
    accessibility.setFontSize(parseInt(accessibility.content.fontSize) - 1)

    // increase by 1 size
    adjustFontSizing(accessibility.content.fontSize);
}

const adjustFontSizing = (fontSize) => {
    tags.forEach(tag => {
        var elements = document.querySelectorAll(`${tag}:not(#aceada *)`);

        for(let i = 0; i < elements.length; i++) {
            var currentSize = elements[i].getAttribute('data-original-font-size');

            if (currentSize) {
                var operation = `${Number(currentSize.replace('px','')) + fontSize}px`;
                elements[i].style.fontSize = operation;
            }
        }

        const aceadaDiv = document.getElementById('aceada');
        aceadaDiv.style.font = '';
    });

    // fontSizingLabel.value = accessibility.content.fontSize === 0 ? 'Default' : (accessibility.content.fontSize * 10) + '%'
    fontSizingLabel.value = accessibility.content.fontSize * 10
}
</script>

<template>
    <div id="aceada-font-sizing-adjustment">
        <div class="aceada-range-input">
            <p class="aceada-group-title">Font Size</p>
            <input id="aceada-font-sizing-range" name="aceada-font-sizing-range" type="range" min="-10" max="10" step="1" v-model="accessibility.content.fontSize" />
        </div>
        <div class="aceada-button-group">
            <div class="aceada-range-value">
                <span>{{ fontSizingLabel + '%' }}</span>
            </div>
            <div class="aceada-range-actions">
                <button id="aceada-increase-font-sizing" class="aceada-increase-range" value="Increase Value" @click="increaseFont()">
                    <svg xmlns="http://www.w3.org/2000/svg" height="12" viewBox="0 0 320 512">
                        <path d="M182.6 137.4c-12.5-12.5-32.8-12.5-45.3 0l-128 128c-9.2 9.2-11.9 22.9-6.9 34.9s16.6 19.8 29.6 19.8H288c12.9 0 24.6-7.8 29.6-19.8s2.2-25.7-6.9-34.9l-128-128z"/>
                    </svg>
                </button>
                <button id="aceada-decrease-font-sizing" class="aceada-decrease-range" value="Decrease Value" @click="decreaseFont()">
                    <svg xmlns="http://www.w3.org/2000/svg" height="12" viewBox="0 0 320 512">
                        <path d="M137.4 374.6c12.5 12.5 32.8 12.5 45.3 0l128-128c9.2-9.2 11.9-22.9 6.9-34.9s-16.6-19.8-29.6-19.8L32 192c-12.9 0-24.6 7.8-29.6 19.8s-2.2 25.7 6.9 34.9l128 128z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>
