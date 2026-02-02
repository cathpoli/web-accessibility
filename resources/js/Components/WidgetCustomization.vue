<script setup>
/**
 * NOTE: This component is used on both admin and client installation pages
 *      Make sure to test both after making changes on this component.
 */

import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { useForm } from "@inertiajs/inertia-vue3";
import { ref, watch, onMounted, inject } from "vue";
import { useNotificationStore } from "@dafcoe/vue-notification";
import axios from 'axios';
import 'vue3-colorpicker/style.css';
import { ColorPicker } from 'vue3-colorpicker';

const swal = inject('$swal')

const { setNotification } = useNotificationStore();

const props = defineProps({
    domainId: Number,
    domainName: String,
});

const triggerColor = ref("#101E43");
const triggerPositionX = ref("end");
const triggerPositionY = ref("end");
const triggerIcon = ref("accessibility");
const triggerIconColor = ref("#ffffff");
const widgetHeroTitleColor = ref("#ffffff");
const themeColor = ref("#101E43");
const customThemeColor = ref("#fcc02e");
const customIconColor = ref("#ffffff");

const domainBrowsershot = ref(null);

const form = useForm({
    triggerColor: triggerColor.value,
    triggerPositionX: triggerPositionX.value,
    triggerPositionY: triggerPositionY.value,
    triggerIcon: triggerIcon.value,
    triggerIconColor: triggerIconColor.value,
    widgetHeroTitleColor: widgetHeroTitleColor.value,
    themeColor: themeColor.value,
})

onMounted(() => {
    // get domain widget configuration
    axios.get(`/api/${props.domainId}/widget-configurations`)
        .then(response => {
            let config = response.data.widget_configuration

            domainBrowsershot.value = response.data.preview_img

            if (! config) {
                // reset to default values if not set yet
                triggerColor.value = "#101E43"
                triggerPositionX.value = "end"
                triggerPositionY.value = "end"
                triggerIcon.value = "accessibility"
                triggerIconColor.value = "#ffffff"
                widgetHeroTitleColor.value = "#ffffff"
                themeColor.value = "#101E43"

                return;
            }

            // set form values
            triggerColor.value = config.triggerColor
            triggerPositionX.value = config.triggerPositionX
            triggerPositionY.value = config.triggerPositionY
            triggerIcon.value = config.triggerIcon
            triggerIconColor.value = config.triggerIconColor
            widgetHeroTitleColor.value = config.widgetHeroTitleColor
            themeColor.value = config.themeColor
        })
        .catch((err) => {
            console.error(err)
        })
})

watch(triggerPositionX, (newValue) => form.triggerPositionX = newValue)

watch(triggerPositionY, (newValue) => form.triggerPositionY = newValue)

watch(triggerIcon, (newValue) => form.triggerIcon = newValue)

watch(customThemeColor, (newValue) => {
    // Whenever customThemeColor changes, update the form's themeColor
    form.triggerColor = newValue;
    form.themeColor = newValue;
    triggerColor.value = newValue;
    themeColor.value = newValue;
});

//button color
watch(themeColor, (newValue) => {

    customThemeColor.value = newValue
    triggerColor.value = newValue;
    form.triggerColor = newValue
    form.themeColor = newValue;

    /* if the triggerColor is a dark color (Dark Blue, Blue, Red, Green or Black), the button icon color will be White.
    if the selectedButton Color is light color (Light Blue, Teal, Light Pink, Light Green, or White), the button icon will be Black. */
    const colorsToUpdate = ["#BFDFF9", "#1DE8DD", "#F386AD", "#A6F6C9", "#FFFAFA"];

    if (colorsToUpdate.includes(newValue)) {
        triggerIconColor.value = "#000000";
        form.triggerIconColor = "#000000"
    } else {
        //put the dark theme colors here
        const colorsToKeepWhite = ["#101E43", "#1373BF", "#BF131D", "#13BF5F", "#000000"];

        if (colorsToKeepWhite.includes(newValue)) {
            triggerIconColor.value = "#ffffff";
            form.triggerIconColor = "#ffffff"
        }
    }
});

//icon Color
watch(customIconColor, (newValue) => {
    // Whenever customIconColor changes, update the form's triggerIconColor

    customIconColor.value = newValue;
    form.triggerIconColor = newValue;
    triggerIconColor.value = newValue;
});

watch(triggerIconColor, (newValue) => {
    // Whenever customIconColor changes, update the form's triggerIconColor

    customIconColor.value = newValue;
    form.triggerIconColor = newValue;
    triggerIconColor.value = newValue;
});

// triggerIcon
// each icon is resized on a 55x55 transparent frame and put to center so it will fit inside the trigger button

const accessibilityWidget =
    "M27.2576 15.75C29.6717 15.75 31.6326 13.7891 31.6326 11.375C31.6326 8.96089 29.6717 7 27.2576 7C24.8435 7 22.8826 8.96089 22.8826 11.375C22.8826 13.7891 24.8435 15.75 27.2576 15.75Z M41.0078 15.8123L40.9687 15.8202L40.9374 15.828C40.8593 15.8514 40.7812 15.8749 40.7031 15.8983C39.25 16.328 32.1952 18.3123 27.2187 18.3123C22.6016 18.3123 16.1797 16.5937 14.125 16.0077C13.9219 15.9296 13.7109 15.8592 13.5 15.8046C12.0156 15.4139 11 16.9218 11 18.2968C11 19.6639 12.2266 20.3123 13.4609 20.7811V20.8046L20.8984 23.1249C21.6562 23.4139 21.8593 23.7108 21.9609 23.9687C22.2812 24.7968 22.0234 26.4373 21.9374 27.0077L21.4843 30.5233L18.9766 44.2968C18.9687 44.3358 18.9609 44.3749 18.9531 44.4139L18.9374 44.5154C18.7578 45.7733 19.6797 46.9999 21.4374 46.9999C22.9687 46.9999 23.6484 45.9452 23.9374 44.5077C23.9374 44.5077 26.125 32.1952 27.2187 32.1952C28.3124 32.1952 30.5624 44.5077 30.5624 44.5077C30.8593 45.9452 31.5312 46.9999 33.0624 46.9999C34.8202 46.9999 35.75 45.7733 35.5624 44.5077C35.5469 44.3983 35.5234 44.2968 35.5 44.1873L32.9609 30.5389L32.5078 27.0233C32.1797 24.9764 32.4452 24.2968 32.5312 24.1404C32.5312 24.1404 32.539 24.1327 32.539 24.1249C32.625 23.9687 33.0078 23.6171 33.9062 23.2811L40.8828 20.8437C40.9219 20.8358 40.9687 20.8202 41.0078 20.8046C42.2578 20.3358 43.5078 19.6873 43.5078 18.3123C43.5078 16.9373 42.4921 15.4218 41.0078 15.8123Z";
const aceadaLogo =
    "M22.9153 33.2566C25.9316 33.2566 28.791 33.2566 31.7975 33.2566C30.3052 30.6554 28.8652 28.1449 27.3567 25.5152C25.8572 28.1286 24.4177 30.6383 22.9153 33.2566ZM15.1737 46.9185C13.6265 46.3253 11.9931 45.8749 10.5699 45.0578C8.05856 43.6158 7.0192 41.2089 7.00016 38.3672C6.98801 36.5486 7.67057 34.8833 8.40211 33.2587C10.6321 28.3061 13.4652 23.6876 16.3586 19.1157C18.9061 15.0905 25.867 14.0988 28.8715 19.4573C29.8061 21.124 30.7773 22.7698 31.7291 24.4267C33.3025 27.1648 34.875 29.9032 36.4449 32.6434C36.6175 32.9443 36.7274 33.2491 37.2107 33.1047C38.4655 32.7301 39.401 31.6818 39.1895 30.3898C38.9927 29.1885 38.5683 27.9747 38.0019 26.8948C36.7453 24.4998 35.3531 22.1757 33.9858 19.8409C32.6291 17.5238 31.2308 15.2288 29.0919 13.5244C27.2592 12.0638 25.1803 11.4274 22.8576 11.8501C21.6836 12.0638 20.5346 12.4173 19.3743 12.7076C20.1748 11.6999 21.0494 10.7387 22.0624 9.95899C25.8918 7.01123 30.161 7.41775 33.4009 10.9921C34.0048 11.6585 34.6298 12.3344 35.0874 13.1007C37.7788 17.6076 40.468 22.1171 43.0671 26.6782C44.611 29.3874 44.1289 32.9528 42.0765 35.3139C40.6414 36.9647 38.7861 37.6652 36.6481 37.6687C31.5162 37.6768 26.3838 37.68 21.2519 37.6528C20.6053 37.6493 20.3609 37.8607 20.3104 38.4902C20.1993 39.8768 20.7325 40.9035 22.0333 41.3901C22.765 41.6635 23.5662 41.8616 24.3421 41.8824C26.8795 41.9503 29.4209 41.9686 31.9585 41.9149C34.1756 41.8679 36.4109 41.8503 38.6014 41.5501C43.0625 40.9392 45.5407 38.5458 46.4963 34.2225C46.5961 33.7707 46.6677 33.3123 46.7819 32.6982C46.9539 32.9893 47.0809 33.1267 47.1218 33.2868C47.3861 34.3124 47.7021 35.3316 47.8709 36.374C48.6092 40.9363 46.1327 44.7107 41.6836 45.7822C40.532 46.0594 39.3398 46.2884 38.1617 46.3122C33.2191 46.4122 28.2742 46.3992 23.3311 46.4792C18.2289 46.5616 13.3651 40.7597 16.8788 35.0153C19.4947 30.7386 21.889 26.3254 24.4113 21.9901C24.7362 21.4317 24.6324 21.1545 24.1205 20.8601C23.1277 20.2895 22.1279 20.244 21.2158 20.9326C20.6131 21.3879 20.0291 21.9385 19.6196 22.5672C18.7089 23.9654 17.8772 25.4192 17.0785 26.8861C15.5574 29.6797 13.8806 32.4111 12.6344 35.3264C10.7379 39.7634 11.7491 43.2496 15.4168 46.3522C15.6078 46.5137 15.7897 46.6863 16.1388 47C15.6706 46.9652 15.3983 47.0046 15.1737 46.9185Z";
const humanGreeting =
    "M27 8C29.2 8 31 9.8 31 12C31 14.2 29.2 16 27 16C24.8 16 23 14.2 23 12C23 9.8 24.8 8 27 8ZM34.8 20.2C34 19.4 32.6 18 30 18H25C19.4 18 15 13.6 15 8H11C11 14.4 15.2 19.6 21 21.4V48H25V36H29V48H33V24.2L41 32L43.8 29.2L34.8 20.2Z";
const tuneUp =
    "M19.6667 29.4583C16.0242 29.4583 12.9887 31.965 12.1075 35.3333H7.91666V39.25H12.1075C12.9887 42.6183 16.0242 45.125 19.6667 45.125C23.3092 45.125 26.3446 42.6183 27.2258 39.25H47.0833V35.3333H27.2258C26.3446 31.965 23.3092 29.4583 19.6667 29.4583ZM19.6667 41.2083C17.5125 41.2083 15.75 39.4458 15.75 37.2917C15.75 35.1375 17.5125 33.375 19.6667 33.375C21.8208 33.375 23.5833 35.1375 23.5833 37.2917C23.5833 39.4458 21.8208 41.2083 19.6667 41.2083ZM42.8925 15.75C42.0112 12.3817 38.9758 9.875 35.3333 9.875C31.6908 9.875 28.6554 12.3817 27.7742 15.75H7.91666V19.6667H27.7742C28.6554 23.035 31.6908 25.5417 35.3333 25.5417C38.9758 25.5417 42.0112 23.035 42.8925 19.6667H47.0833V15.75H42.8925ZM35.3333 21.625C33.1792 21.625 31.4167 19.8625 31.4167 17.7083C31.4167 15.5542 33.1792 13.7917 35.3333 13.7917C37.4875 13.7917 39.25 15.5542 39.25 17.7083C39.25 19.8625 37.4875 21.625 35.3333 21.625Z";

const submit = () => {
    form.post(`/api/${props.domainId}/widget-configurations`, {
        onSuccess: () => {
            let domainName = props.domainName

            setNotification({
                type: "success",
                message: `Successfully saved widget configuration ${domainName ? `for ${domainName}` : ''} .`,
            });
        },
        onError: (err) => {
            console.error(err)
        },
    });
};

const setToDefault = () => {
    swal.fire({
        title: '',
        text: 'Are you sure u want to reset to default?',
        icon: 'warning',
        iconColor: '#1ad2c7',
        showCancelButton: true,
        confirmButtonText: 'Yes, reset it!',
        cancelButtonText: 'Cancel',
        confirmButtonColor:"#1ad2c7",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            triggerColor.value = "#101E43"
            triggerPositionX.value = "end"
            triggerPositionY.value = "end"
            triggerIcon.value = "accessibility"
            triggerIconColor.value = "#ffffff"
            widgetHeroTitleColor.value = "#ffffff"
            themeColor.value = "#101E43"
        }
    })
};

const takedomainBrowsershot = (e) => {
    axios.post(`/api/${props.domainId}/widget-configurations/take-domain-browsershot`)
        .then((response) => {
            setNotification({type: "info", message: `Domain browsershot has been scheduled, this action may take a few minutes.`});
        })
        .catch((error) => {
            setNotification({type: "error", message: `Something went wrong.`});
        })
}

</script>

<template>
    <form @submit.prevent="submit">
        <div>
            <div class="p-3 bg-white shadow-sm sm:rounded-lg my-5 md:p-6">
                <h1 class="pb-0 font-bold text-xl">Button and Widget Customization</h1>
                Click <a href="https://www.loom.com/share/5bcf8dc99923489bba99be0732f5c7a4?sid=ecb62c1e-0701-4024-8ad9-2d54f860ce33" target="_blank"
                                style="color: blue; background-color: transparent;">here</a> for preview presentation of customization
                <div class="grid grid-cols-2 md:grid-cols-2 gap-3">
                    <div class="pt-3 col-span-2">
                        <h2 class="font-semibold mb-2">
                            Color Theme
                        </h2>
                        <div class="flex flex-wrap gap-5">
                            <label
                                for="default-theme"
                                class="cursor-pointed rounded p-3 w-20 default-theme"
                                :class="{
                                    'shadow-xl':
                                        themeColor === '#101E43',
                                }">
                                <input
                                    type="radio"
                                    id="default-theme"
                                    value="#101E43"
                                    class="w-3 h-3 rounded-full"
                                    v-model="themeColor"
                                />
                            </label>
                            <label
                                for="blue-theme"
                                class="cursor-pointer rounded p-3 w-20 blue-theme"
                                :class="{
                                    'shadow-xl':
                                        themeColor === '#1373BF',
                                }">
                                <input
                                    type="radio"
                                    id="blue-theme"
                                    value="#1373BF"
                                    class="w-3 h-3 rounded-full"
                                    v-model="themeColor"
                                />
                            </label>
                            <label
                                for="red-theme"
                                class="cursor-pointer rounded p-3 w-20 red-theme"
                                :class="{
                                    'shadow-xl':
                                        themeColor === '#BF131D',
                                }">
                                <input
                                    type="radio"
                                    id="red-theme"
                                    value="#BF131D"
                                    class="w-3 h-3 rounded-full"
                                    v-model="themeColor"
                                />
                            </label>
                            <label
                                for="green-theme"
                                class="cursor-pointer rounded p-3 w-20 green-theme"
                                :class="{
                                    'shadow-xl':
                                        themeColor === '#13BF5F',
                                }">
                                <input
                                    type="radio"
                                    id="green-theme"
                                    value="#13BF5F"
                                    class="w-3 h-3 rounded-full"
                                    v-model="themeColor"
                                />
                            </label>
                            <label
                                for="black-theme"
                                class="cursor-pointer rounded p-3 w-20 black-theme"
                                :class="{
                                    'shadow-xl':
                                        themeColor === '#000000',
                                }">
                                <input
                                    type="radio"
                                    id="black-theme"
                                    value="#000000"
                                    class="w-3 h-3 rounded-full"
                                    v-model="themeColor"
                                />
                            </label>
                            <label
                                for="light-blue-theme"
                                class="cursor-pointer rounded p-3 w-20 light-blue-theme"
                                :class="{
                                    'shadow-xl':
                                        themeColor === '#BFDFF9',
                                }">
                                <input
                                    type="radio"
                                    id="light-blue-theme"
                                    value="#BFDFF9"
                                    class="w-3 h-3 rounded-full"
                                    v-model="themeColor"
                                />
                            </label>
                            <label
                                for="teal-theme"
                                class="cursor-pointer rounded p-3 w-20 teal-theme"
                                :class="{
                                    'shadow-xl':
                                        themeColor === '#1DE8DD',
                                }">
                                <input
                                    type="radio"
                                    id="teal-theme"
                                    value="#1DE8DD"
                                    class="w-3 h-3 rounded-full"
                                    v-model="themeColor"
                                />
                            </label>
                            <label
                                for="light-pink-theme"
                                class="cursor-pointer rounded p-3 w-20 light-pink-theme"
                                :class="{
                                    'shadow-xl':
                                        themeColor === '#F386AD',
                                }">
                                <input
                                    type="radio"
                                    id="light-pink-theme"
                                    value="#F386AD"
                                    class="w-3 h-3 rounded-full"
                                    v-model="themeColor"
                                />
                            </label>
                            <label
                                for="light-green-theme"
                                class="cursor-pointer rounded p-3 w-20 light-green-theme"
                                :class="{
                                    'shadow-xl':
                                        themeColor === '#A6F6C9',
                                }">
                                <input
                                    type="radio"
                                    id="light-green-theme"
                                    value="#A6F6C9"
                                    class="w-3 h-3 rounded-full"
                                    v-model="themeColor"
                                />
                            </label>
                            <label
                                for="white-theme"
                                class="cursor-pointer rounded p-3 w-20 white-theme"
                                :class="{
                                    'shadow-xl':
                                        themeColor === '#FFFAFA',
                                }">
                                <input
                                    type="radio"
                                    id="white-theme"
                                    value="#FFFAFA"
                                    class="w-3 h-3 rounded-full"
                                    v-model="themeColor"
                                />
                            </label>
                        </div>
                    </div>

                    <div class="pt-3">
                        <h2 class="font-semibold mb-2">Custom Button Color</h2>
                        <input
                            type="radio"
                            id="custom-theme"
                            :value="customThemeColor"
                            class="w-3 h-3 rounded-full"
                            v-model="themeColor"
                        />
                        <ColorPicker
                            v-model:pureColor="customThemeColor"
                            format="hex"
                        />
                    </div>

                    <div class="pt-3">
                        <h2 class="font-semibold mb-2">Custom Icon Color</h2>
                        <input
                            type="radio"
                            id="custom-icon-color"
                            :value="customIconColor"
                            class="w-3 h-3 rounded-full"
                            v-model="triggerIconColor"
                        />
                        <ColorPicker
                            v-model:pureColor="customIconColor"
                            format="hex"
                        />
                    </div>

                    <div class="pt-3">
                        <h2 class="font-semibold mb-2">
                            Horizontal Position
                        </h2>
                        <select
                            class="select teal-border w-full"
                            v-model="triggerPositionX"
                        >
                            <option :value="'start'">Left</option>
                            <!-- <option :value="'center'">Center</option> -->
                            <option :value="'end'">Right</option>
                        </select>
                    </div>

                    <div class="pt-3">
                        <h2 class="font-semibold mb-2">
                            Vertical Position
                        </h2>
                        <select
                            class="select teal-border w-full"
                            v-model="triggerPositionY"
                        >
                            <option :value="'start'">Top</option>
                            <option :value="'top-middle'">Top - Middle</option>
                            <option :value="'center'">Center</option>
                            <option :value="'bottom-middle'">Bottom - Middle</option>
                            <option :value="'end'">Bottom</option>
                        </select>
                    </div>
                </div>

                <div class="pt-3">
                    <h2 class="font-semibold mb-2">Button Icon</h2>

                    <div class="flex gap-5">
                        <div class="flex flex-wrap gap-5">
                            <label
                                for="accessbility-widget"
                                class="cursor-pointer bg-gray-100 rounded p-3 hover:bg-gray-200 w-20"
                                :class="{
                                    'teal-border shadow-lg':
                                        triggerIcon === 'accessibility',
                                }"
                            >
                                <input
                                    type="radio"
                                    id="accessbility-widget"
                                    value="accessibility"
                                    class="w-3 h-3 rounded-full"
                                    v-model="triggerIcon"
                                />


                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 55 55"
                                    class="mx-auto"
                                >
                                    <path :d="accessibilityWidget" />
                                </svg>
                            </label>
                            <label
                                for="aceada-icon"
                                class="cursor-pointer bg-gray-100 rounded p-3 hover:bg-gray-200 w-20"
                                :class="{
                                    'teal-border shadow-lg':
                                        triggerIcon === 'aceada',
                                }"
                            >
                                <input
                                    type="radio"
                                    id="aceada-icon"
                                    value="aceada"
                                    class="w-3 h-3 rounded-full"
                                    v-model="triggerIcon"
                                />

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 55 55"
                                    class="mx-auto"
                                >
                                    <path :d="aceadaLogo" />
                                </svg>
                            </label>

                            <label
                                for="human-greeting"
                                class="cursor-pointer bg-gray-100 rounded p-3 hover:bg-gray-200 w-20"
                                :class="{
                                    'teal-border shadow-md':
                                        triggerIcon === 'human',
                                }"
                            >
                                <input
                                    type="radio"
                                    id="human-greeting"
                                    value="human"
                                    class="w-3 h-3 rounded-full"
                                    v-model="triggerIcon"
                                />

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 55 55"
                                    class="mx-auto"
                                >
                                    <path :d="humanGreeting" />
                                </svg>
                            </label>

                            <label
                                for="tuneup-icon"
                                class="cursor-pointer bg-gray-100 rounded p-3 hover:bg-gray-200 w-20"
                                :class="{
                                    'teal-border shadow-lg':
                                        triggerIcon === 'tuneup',
                                }"
                            >
                                <input
                                    type="radio"
                                    id="tuneup-icon"
                                    value="tuneup"
                                    class="w-3 h-3 rounded-full"
                                    v-model="triggerIcon"
                                />

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 55 55"
                                    class="mx-auto"
                                >
                                    <path :d="tuneUp" />
                                </svg>
                            </label>
                        </div>
                    </div>

                    <div class="pt-3">
                        <h2 class="font-semibold mb-2">Preview</h2>
                        <div
                            id="preview"
                            class="border teal-border rounded grid p-5"
                            :style="{
                                justifyItems: triggerPositionX,
                                alignItems: triggerPositionY === 'start' || triggerPositionY === 'center' || triggerPositionY === 'end' ? triggerPositionY: triggerPositionY === 'bottom-middle' ? 'end' : null,
                                backgroundImage: `url(${domainBrowsershot === null ? '/assets/images/preview/aceada-preview-background.png' : domainBrowsershot})`
                            }"
                        >
                            <div
                                class="preview-button rounded-full flex justify-center items-center"
                                :style="{
                                    backgroundColor: themeColor,
                                    position: triggerPositionY === 'top-middle' || triggerPositionY === 'bottom-middle' ? 'relative' : null,
                                    top: triggerPositionY === 'top-middle' ? '5rem' : null,
                                    bottom: triggerPositionY === 'bottom-middle' ? '5rem' : null,
                                }"
                            >
                                <div
                                    :style="{
                                        color: triggerIconColor,
                                    }"
                                >
                                    <template
                                        v-if="triggerIcon === 'accessibility'"
                                    >
                                        <svg
                                            width="50"
                                            height="50"
                                            viewBox="0 0 55 55"
                                            fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                :d="accessibilityWidget"
                                                fill="currentColor"
                                            />
                                        </svg>
                                    </template>

                                    <template
                                        v-if="triggerIcon === 'human'"
                                    >
                                        <svg
                                            width="50"
                                            height="50"
                                            viewBox="0 0 55 55"
                                            fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                :d="humanGreeting"
                                                fill="currentColor"
                                            />
                                        </svg>
                                    </template>

                                    <template
                                        v-if="triggerIcon === 'tuneup'"
                                    >
                                        <svg
                                            width="50"
                                            height="50"
                                            viewBox="0 0 55 55"
                                            fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                :d="tuneUp"
                                                fill="currentColor"
                                            />
                                        </svg>
                                    </template>

                                    <template
                                        v-if="triggerIcon === 'aceada'"
                                    >
                                        <svg
                                            width="50"
                                            height="50"
                                            viewBox="0 0 55 55"
                                            fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                :d="aceadaLogo"
                                                fill="currentColor"
                                            />
                                        </svg>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between pt-3">
                    <div>
                        <PrimaryButton class="btn teal-btn capitalize font-extrabold" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Save Settings
                        </PrimaryButton>
                        <button type="button" class="btn btn-outline capitalize ml-2" @click="setToDefault">
                            Reset to default
                        </button>
                    </div>
                    <button type="button" class="btn btn-ghost capitalize ml-2" @click.prevent="takedomainBrowsershot()">
                        Update Domain Browsershot
                    </button>
                </div>
            </div>
        </div>
    </form>
</template>

<style>
    #preview {
        height: 600px;
        width: 100%;
        background-size: cover;
    }

    .preview-button {
        height: 50px;
        width: 50px;
        padding-left: 2px;
    }

    .vc-color-wrap {
        width: 80px !important;
        height: 48px !important;
        border-radius: .25em;
        margin-left: 5px;
    }
</style>
