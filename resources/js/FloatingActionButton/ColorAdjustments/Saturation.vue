<script setup>
import { onMounted } from 'vue';
import { useAccessibilityStore } from '@/FloatingActionButton/Stores/Accessibility'

const accessibility = useAccessibilityStore()
const root = document.getElementsByTagName('html')[0]

onMounted(() => {
    if (accessibility.color.saturation)
        document.body.classList.add(`aceada-${accessibility.color.saturation}-saturation`)
        //document.getElementById('aceada').classList.add('aceada-no-filter');
});

const adjustSaturation = (event) => {
    var el = event.target
    var button = el.closest('.aceada-adjustment-button')

    var level = button.getAttribute('data-aceada-saturation-level')
    var otherLevel = level === 'high' ? 'low' :'high'

    if (accessibility.color.saturation != level) {
        accessibility.setSaturation(level) // set as current

        document.body.classList.remove(`aceada-${otherLevel}-saturation`)
        // document.getElementById(`aceada-${otherLevel}-saturation`).classList.remove('active')

        document.body.classList.add(`aceada-${level}-saturation`)

        var choosePlanModal = document.getElementById('choose-plan-modal');
        if (choosePlanModal) {
            choosePlanModal.style.marginTop = '-80rem'; 
        }

        var domainModal = document.getElementById('domain-modal');
        if (domainModal) {
            domainModal.style.marginTop = '-80rem'; 
        }

    } else {
        accessibility.setSaturation(null) // reset

        // document.getElementById(`aceada-${level}-saturation`).classList.remove('active')
        document.body.classList.remove(`aceada-${level}-saturation`)

        var choosePlanModal = document.getElementById('choose-plan-modal');
        if (choosePlanModal) {
            choosePlanModal.style.marginTop = '0px'; 
        }

        var domainModal = document.getElementById('domain-modal');
        if (domainModal) {
            domainModal.style.marginTop = '0px'; 
        }
    }
};

</script>

<template>
    <button id="aceada-low-saturation" class="aceada-adjustment-button" :class="{ 'aceada-adjustment-button-active': accessibility.color.saturation == 'low' }" @click="adjustSaturation" data-aceada-saturation-level="low">
        <div class="aceada-adjustment-icon">
            <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="15" cy="15" r="15" fill="#9CD3D0"/>
            </svg>
        </div>
        <p class="aceada-adjustment-title">Low</p>
    </button>
    <button id="aceada-high-saturation" class="aceada-adjustment-button" :class="{ 'aceada-adjustment-button-active': accessibility.color.saturation == 'high' }" @click="adjustSaturation" data-aceada-saturation-level="high">
        <div class="aceada-adjustment-icon">
            <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="15" cy="15" r="15" fill="#1AD2C7"/>
            </svg>
        </div>
        <p class="aceada-adjustment-title">High</p>
    </button>
</template>
