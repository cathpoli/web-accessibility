<script setup>

const displayAccessibilityStatement = (event) => {
    document.getElementById("aceada-statement").style.display = 'block'
    document.getElementById("aceada-trigger-checkbox").click()
}
</script>

<template>
    <button class="aceada-action" @click="displayAccessibilityStatement">
        <!-- <svg height="21" viewBox="0 0 21 21" width="21" xmlns="http://www.w3.org/2000/svg" id="IconChangeColor">
            <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" transform="translate(4 3)">
                <path d="m12.5 12.5v-10c0-1.1045695-.8954305-2-2-2h-8c-1.1045695 0-2 .8954305-2 2v10c0 1.1045695.8954305 2 2 2h8c1.1045695 0 2-.8954305 2-2z" id="mainIconPathAttribute"></path>
                <path d="m5.5 4.5h5" id="mainIconPathAttribute"></path>
                <path d="m2.5 4.5h1" id="mainIconPathAttribute"></path>
                <path d="m5.5 7.5h5" id="mainIconPathAttribute"></path>
                <path d="m2.5 7.5h1" id="mainIconPathAttribute"></path>
                <path d="m5.5 10.5h5" id="mainIconPathAttribute"></path>
                <path d="m2.5 10.5h1" id="mainIconPathAttribute"></path>
            </g>
        </svg> -->
        <span class="aceada-feature-title">Accessibility Statement</span>
    </button>
</template>
