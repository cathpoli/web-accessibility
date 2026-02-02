<script setup>
import { onMounted } from 'vue';
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'

const accessibility = useAccessibilityStore()

// enable on reload
onMounted(() => {
    if (accessibility.content.highlightTexts)
        document.body.classList.add('aceada-highlighted-texts')

    if (accessibility.content.highlightTitles)
        document.body.classList.add('aceada-highlighted-title')

    if (accessibility.content.highlightLinks)
        document.body.classList.add('aceada-highlighted-link')
});

accessibility.$subscribe((mutation, state) => {
    document.body.classList.toggle('aceada-highlighted-texts', state.content.highlightTexts);

    document.body.classList.toggle('aceada-highlighted-title', state.content.highlightTitles)

    document.body.classList.toggle('aceada-highlighted-link', state.content.highlightLinks)
})
</script>

<template>
    <div>
        <p class="aceada-group-title">Highlights</p>
        <div id="aceada-highlights">
            <button id="aceada-highlight-texts" class="aceada-adjustment-button aceada-highlights-button" :class="{ 'aceada-adjustment-button-active': accessibility.content.highlightTexts }" @click="accessibility.toggleHighlightTexts()">
                <svg width="28" height="24" viewBox="0 0 28 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1.36847 8.63156H26.6316" stroke="#011817" stroke-width="2" stroke-linecap="round"/>
                    <path d="M1.36847 1.89471H26.6316" stroke="#011817" stroke-width="2" stroke-linecap="round"/>
                    <path d="M1.36847 15.3684H26.6316" stroke="#011817" stroke-width="2" stroke-linecap="round"/>
                    <path d="M1.36847 22.1052H26.6316" stroke="#011817" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <p class="aceada-adjustment-title">Texts</p>
            </button>
            <button id="aceada-highlight-titles" class="aceada-adjustment-button aceada-highlights-button" :class="{ 'aceada-adjustment-button-active': accessibility.content.highlightTitles }" @click="accessibility.toggleHighlightTitles()">
                <svg width="30" height="20" viewBox="0 0 30 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1.36841 9.63147H26.6316" stroke="#011817" stroke-width="2" stroke-linecap="round"/>
                    <path d="M11.3684 2.89465H26.6316" stroke="#011817" stroke-width="2" stroke-linecap="round"/>
                    <path d="M1.36841 16.3683H16.6316" stroke="#011817" stroke-width="2" stroke-linecap="round"/>
                    <path d="M24.363 15H26.0278L25.1321 20H23L24.363 15ZM28.3255 15H30L29.1335 20H27.0014L28.3255 15Z" fill="#011817"/>
                    <path d="M2.363 0H4.02782L3.13213 5H1L2.363 0ZM6.32545 0H8L7.13352 5H5.00139L6.32545 0Z" fill="#011817"/>
                </svg>
                <p class="aceada-adjustment-title">Titles</p>
            </button>
            <button id="aceada-highlight-links" class="aceada-adjustment-button aceada-highlights-button" :class="{ 'aceada-adjustment-button-active': accessibility.content.highlightLinks }" @click="accessibility.toggleHighlightLinks()">
                <svg width="51" height="25" viewBox="0 0 51 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M34.0625 18H9.5C4.80558 18 1 14.1944 1 9.5V9.5C1 4.80558 4.80558 1 9.5 1H38.5C43.1944 1 47 4.80558 47 9.5V9.5" stroke="black" stroke-width="2"/>
                    <path d="M14.0464 8.90021H14.0851L15.2634 13H16.4889L18.1358 7H16.7899L15.8095 11.3451H15.7751L14.614 7H13.5175L12.3565 11.3451H12.3221L11.3459 7H10L11.6426 13H12.8682L14.0464 8.90021Z" fill="#011817"/>
                    <path d="M23.9785 8.90021H24.0172L25.1954 13H26.421L28.0679 7H26.722L25.7416 11.3451H25.7072L24.5461 7H23.4496L22.2885 11.3451H22.2541L21.278 7H19.9321L21.5747 13H22.8003L23.9785 8.90021Z" fill="#011817"/>
                    <path d="M33.9106 8.90021H33.9493L35.1275 13H36.3531L38 7H36.6541L35.6736 11.3451H35.6392L34.4782 7H33.3817L32.2206 11.3451H32.1862L31.2101 7H29.8642L31.5068 13H32.7323L33.9106 8.90021Z" fill="#011817"/>
                    <path d="M49.4096 18.9429L49.4082 18.9434L47.4172 19.614C47.4165 19.6142 47.4159 19.6145 47.4152 19.6147C46.577 19.8943 45.8957 20.5492 45.6095 21.4196L44.938 23.4063L44.9368 23.4097C44.6727 24.2006 43.5564 24.1986 43.3038 23.3919C43.3037 23.3918 43.3037 23.3916 43.3036 23.3915L41.0419 16.1298C41.0417 16.1291 41.0415 16.1284 41.0412 16.1277C40.8321 15.4379 41.4655 14.8394 42.1093 15.0427L42.1144 15.0442L49.4036 17.3029C49.404 17.3031 49.4045 17.3032 49.4049 17.3033C50.1858 17.5496 50.2093 18.6749 49.4096 18.9429Z" stroke="black" stroke-width="2"/>
                </svg>
                <p class="aceada-adjustment-title">Links</p>
            </button>
        </div>
    </div>
</template>
