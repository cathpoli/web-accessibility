<script setup>
import { ref, watch } from 'vue'

const selectedSection = ref(null);
const props = defineProps({
    dom: Object,
});

const doc = props.dom;
const failedOR01 = [];
const successOR01 = [];
const failedOR02 = [];
const successOR02 = [];
const failedOR03 = [];
const successOR03 = [];
const failedOR04 = [];
const successOR04 = [];
const failedOR05 = [];
const successOR05 = [];
const failedOR06 = [];
const successOR06 = [];

const interactiveElements = doc.querySelectorAll("a, button, input, select, textarea, li");

// Interactive elements should be navigable using the keyboard - OR01
interactiveElements.forEach((element) => {
    const tabindexValue = element.getAttribute("tabindex");
    if (tabindexValue !== null && parseInt(tabindexValue) === 0) {
        successOR01.push(element);
    } else {
        failedOR01.push(element);
    }
});
var totalOR01Elements = failedOR01.length + successOR01.length;

// Keyboard focus should have a noticeable outline - OR02
interactiveElements.forEach((element) => {
    const computedStyle = window.getComputedStyle(element);
    if (computedStyle.getPropertyValue("outline") !== "none") {
        successOR02.push(element);
    } else {
        failedOR02.push(element);
    }
});
var totalOR02Elements = failedOR02.length + successOR02.length;

// Active popups should be tagged for assistive technology - OR03
const modalElements = doc.querySelectorAll(".modal");
modalElements.forEach((modal) => {
    const ariaAttribute = element.getAttribute("aria-modal");
    const roleAttributeValue = imgElement.getAttribute('role');
    if (ariaAttribute && roleAttributeValue) {
        if (ariaAttribute.toLowerCase() === 'true' || roleAttributeValue.toLowerCase() === 'dialog') {
            successOR03.push(element);
        } else {
            failedOR03.push(element);
        }
    }
});
var totalOR03Elements = failedOR03.length + successOR03.length;

// Every page should include hidden links that allow skipping blocks - OR04
const skipLinks = doc.querySelectorAll(".skip-link");
skipLinks.forEach((element) => {
    if (element) {
        successOR04.push(element);
    } else {
        failedOR04.push(element);
    }
});
var totalOR04Elements = failedOR04.length + successOR04.length;

// Manipulatively hidden interactive elements should be excluded from assistive technology - OR05
const hiddenElements = doc.querySelectorAll('*');
function isElementHidden(element) {
    const style = getComputedStyle(element);
    return style.display === 'none';
}
function isElementWrapped(element) {
    const parent = element.parentElement;
    return parent && parent.getAttribute('aria-hidden') === 'false';
}
hiddenElements.forEach(element => {
    if (isElementHidden(element)) {
        if (isElementWrapped(element)) {
            successOR05.push(element);
        } else {
            failedOR05.push(element);
        }
    }
});
var totalOR05Elements = failedOR05.length + successOR05.length;

// Manipulatively hidden interactive elements should be excluded from tabindex - OR06
const allElements = doc.querySelectorAll('*');
function hasTabIndexAttribute(element) {
    return element.hasAttribute('tabindex');
}
allElements.forEach(element => {
    if (isElementHidden(element) && hasTabIndexAttribute(element)) {
        const tabindexValue = element.getAttribute('tabindex');
        if (tabindexValue < 0) {
            successOR06.push(element);
        } else {
            failedOR06.push(element);
        }
    }
});
var totalOR06Elements = failedOR06.length + successOR06.length;

// Score computation
var totalElements = totalOR01Elements + totalOR02Elements + totalOR03Elements + totalOR04Elements + totalOR05Elements + totalOR06Elements;
var totalFailedElements = failedOR01.length + failedOR02.length + failedOR04.length + failedOR05.length + failedOR06.length;
var score = calculatePercentage((totalElements - totalFailedElements), 100);

function calculatePercentage(part, total) {
    var total = (part / total) * 100;
    return Math.round(total)+'%';
}

const focusOnSection = (sectionId) => {
    const section = document.getElementById(sectionId);
    if (section) {
        section.scrollIntoView({
            behavior: 'smooth'
        });
    }
};

watch(selectedSection, (newVal) => {
    if (newVal) {
        focusOnSection(newVal);
    }
});
</script>

<template>
    <div class="bg-white rounded-2xl blue-shadow">
        <label for="acc-close" class="flex justify-between items-center p-5">
            <h2 class="flex text-3xl font-semibold text-gray-90">Orientation</h2>
            <span class="base-badge non_compliant-badge" v-if="Number(score.replace(/%/g, '')) < 60 && totalFailedElements !== 0 && totalElements !== 0"> Score: {{ score.replace(/%/g, '') }} </span>
            <span class="base-badge semi_compliant-badge" v-if="Number(score.replace(/%/g, '')) < 80 && Number(score.replace(/%/g, '')) >= 60"> Score: {{ score.replace(/%/g, '') }} </span>
            <span class="base-badge compliant-badge" v-if="Number(score.replace(/%/g, '')) >= 80"> Score: {{ score.replace(/%/g, '') }} </span>
            <span class="base-badge neutral-badge" v-if="totalFailedElements === 0 && totalElements === 0"> Neutral </span>
        </label>
        <div class="accordion arrows">
            <!-- OR01 -->
            <input type="radio" name="accordion" id="or01" :value="'box-or01'" v-model="selectedSection" />
            <section class="box" id="box-or01">
                <label class="box-title" for="or01">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successOR01.length > failedOR01.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedOR01.length > successOR01.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedOR01.length == 0 && successOR01.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Interactive elements should be navigable using the keyboard</h3>
                    </div>
                    <div class="affected-icons hidden lg:flex">
                        <svg width="24" height="24">
                            <use xlink:href="#motor_impaired-icon"></use>
                        </svg>

                        <svg width="24" height="24">
                            <use xlink:href="#vision_impaired-icon"></use>
                        </svg>

                        <svg width="24" height="24">
                            <use xlink:href="#cognitive_disability-icon"></use>
                        </svg>
                    </div>
                </label>
                <label class="box-close" for="acc-close"></label>
                <div class="box-content">
                    <div class="flex flex-col-reverse lg:flex-row">
                        <div class="w-full lg:w-3/4 bg-white rounded border border-gray-200 mt-4 lg:mt-0 p-5">
                            <h3 class="font-bold">Requirement:</h3>
                            <p>
                                Interactive elements such as links, buttons and form fields should all be navigable using
                                the keyboard by either using a focusable element (a, button, input, etc.) or including the
                                "tabindex" attribute that equals to "0".
                            </p>

                            <div v-if="failedOR01 !== null && failedOR01.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedOR01.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedOR01" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successOR01 !== null && successOR01.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successOR01.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successOR01" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <p class="mt-4">
                                Want to see all the elements and full details? <span class="font-bold">Get the free PDF
                                    report to your email.</span>
                            </p>
                        </div>
                        <div class="w-full lg:w-1/4 bg-white rounded border border-gray-200 ml-0 lg:ml-4 p-5">
                            <div class="border-b border-gray-200 pb-4" v-if="failedOR01.length == 0 && successOR01.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedOR01.length != 0 || successOR01.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalOR01Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedOR01.length, totalOR01Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedOR01.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successOR01.length, totalOR01Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successOR01.length }} </span>
                                    </div>
                                </div>
                            </div>
                            <h3 class="font-bold pt-4">Who is affected by these issues?</h3>
                            <div class="affected-icons space-y-1 pt-4">
                                <div class="flex items-center">
                                    <svg width="24" height="24">
                                        <use xlink:href="#motor_impaired-icon"></use>
                                    </svg>
                                    Motor Impaired
                                </div>
                                <div class="flex items-center">
                                    <svg width="24" height="24">
                                        <use xlink:href="#vision_impaired-icon"></use>
                                    </svg>
                                    Vision Impaired
                                </div>
                                <div class="flex items-center">
                                    <svg width="24" height="24">
                                        <use xlink:href="#cognitive_disability-icon"></use>
                                    </svg>
                                    Cognitive Disability
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- OR02 -->
            <input type="radio" name="accordion" id="or02" :value="'box-or02'" v-model="selectedSection" />
            <section class="box" id="box-or02">
                <label class="box-title" for="or02">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successOR02.length > failedOR02.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedOR02.length > successOR02.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedOR02.length == 0 && successOR02.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Keyboard focus should have a noticeable outline</h3>
                    </div>
                    <div class="affected-icons hidden lg:flex">
                        <svg width="24" height="24">
                            <use xlink:href="#vision_impaired-icon"></use>
                        </svg>

                        <svg width="24" height="24">
                            <use xlink:href="#cognitive_disability-icon"></use>
                        </svg>
                    </div>
                </label>
                <label class="box-close" for="acc-close"></label>
                <div class="box-content">
                    <div class="flex flex-col-reverse lg:flex-row">
                        <div class="w-full lg:w-3/4 bg-white rounded border border-gray-200 mt-4 lg:mt-0 p-5">
                            <h3 class="font-bold">Requirement:</h3>
                            <p>
                                Interactive elements that can be navigated using the keyboard should be surrounded by a
                                visual outline whenever they are focused.
                            </p>

                            <div v-if="failedOR02 !== null && failedOR02.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedOR02.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedOR02" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successOR02 !== null && successOR02.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successOR02.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successOR02" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <p class="mt-4">
                                Want to see all the elements and full details? <span class="font-bold">Get the free PDF
                                    report to your email.</span>
                            </p>
                        </div>
                        <div class="w-full lg:w-1/4 bg-white rounded border border-gray-200 ml-0 lg:ml-4 p-5">
                            <div class="border-b border-gray-200 pb-4" v-if="failedOR02.length == 0 && successOR02.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedOR02.length != 0 || successOR02.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalOR02Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedOR02.length, totalOR02Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedOR02.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successOR02.length, totalOR02Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successOR02.length }} </span>
                                    </div>
                                </div>
                            </div>
                            <h3 class="font-bold pt-4">Who is affected by these issues?</h3>
                            <div class="affected-icons space-y-1 pt-4">
                                <div class="flex items-center">
                                    <svg width="24" height="24">
                                        <use xlink:href="#vision_impaired-icon"></use>
                                    </svg>
                                    Vision Impaired
                                </div>
                                <div class="flex items-center">
                                    <svg width="24" height="24">
                                        <use xlink:href="#cognitive_disability-icon"></use>
                                    </svg>
                                    Cognitive Disability
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- OR03 -->
            <input type="radio" name="accordion" id="or03" :value="'box-or03'" v-model="selectedSection" />
            <section class="box" id="box-or03">
                <label class="box-title" for="or03">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successOR03.length > failedOR03.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedOR03.length > successOR03.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedOR03.length == 0 && successOR03.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Active popups should be tagged for assistive technology</h3>
                    </div>
                    <div class="affected-icons hidden lg:flex">
                        <svg width="24" height="24">
                            <use xlink:href="#vision_impaired-icon"></use>
                        </svg>

                        <svg width="24" height="24">
                            <use xlink:href="#cognitive_disability-icon"></use>
                        </svg>
                    </div>
                </label>
                <label class="box-close" for="acc-close"></label>
                <div class="box-content">
                    <div class="flex flex-col-reverse lg:flex-row">
                        <div class="w-full lg:w-3/4 bg-white rounded border border-gray-200 mt-4 lg:mt-0 p-5">
                            <h3 class="font-bold">Requirement:</h3>
                            <p>
                                Active popups should include an "aria-modal" attribute that equals to "true", and a "role"
                                attribute that equals to "dialog", so screen-reader users know how to navigate within it.
                            </p>

                            <div v-if="failedOR03 !== null && failedOR03.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedOR03.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedOR03" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successOR03 !== null && successOR03.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successOR03.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successOR03" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <p class="mt-4">
                                Want to see all the elements and full details? <span class="font-bold">Get the free PDF
                                    report to your email.</span>
                            </p>
                        </div>
                        <div class="w-full lg:w-1/4 bg-white rounded border border-gray-200 ml-0 lg:ml-4 p-5">
                            <div class="border-b border-gray-200 pb-4" v-if="failedOR03.length == 0 && successOR03.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedOR03.length != 0 || successOR03.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalOR03Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedOR03.length, totalOR03Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedOR03.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successOR03.length, totalOR03Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successOR03.length }} </span>
                                    </div>
                                </div>
                            </div>
                            <h3 class="font-bold pt-4">Who is affected by these issues?</h3>
                            <div class="affected-icons space-y-1 pt-4">
                                <div class="flex items-center">
                                    <svg width="24" height="24">
                                        <use xlink:href="#vision_impaired-icon"></use>
                                    </svg>
                                    Vision Impaired
                                </div>
                                <div class="flex items-center">
                                    <svg width="24" height="24">
                                        <use xlink:href="#cognitive_disability-icon"></use>
                                    </svg>
                                    Cognitive Disability
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- OR04 -->
            <input type="radio" name="accordion" id="or04" :value="'box-or04'" v-model="selectedSection" />
            <section class="box" id="box-or04">
                <label class="box-title" for="or04">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successOR04.length > failedOR04.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedOR04.length > successOR04.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedOR04.length == 0 && successOR04.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Every page should include hidden links that allow skipping blocks</h3>
                    </div>
                    <div class="affected-icons hidden lg:flex">

                        <svg width="24" height="24">
                            <use xlink:href="#motor_impaired-icon"></use>
                        </svg>

                        <svg width="24" height="24">
                            <use xlink:href="#vision_impaired-icon"></use>
                        </svg>

                        <svg width="24" height="24">
                            <use xlink:href="#cognitive_disability-icon"></use>
                        </svg>
                    </div>
                </label>
                <label class="box-close" for="acc-close"></label>
                <div class="box-content">
                    <div class="flex flex-col-reverse lg:flex-row">
                        <div class="w-full lg:w-3/4 bg-white rounded border border-gray-200 mt-4 lg:mt-0 p-5">
                            <h3 class="font-bold">Requirement:</h3>
                            <p>
                                Every page should include hidden links that by clicking on them (either using keyboard
                                navigation or a screen-reader), the user will "skip" certain blocks directly to main
                                landmarks such as main content, menu or footer.
                            </p>

                            <div v-if="failedOR04 !== null && failedOR04.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedOR04.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedOR04" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successOR04 !== null && successOR04.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successOR04.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successOR04" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <p class="mt-4">
                                Want to see all the elements and full details? <span class="font-bold">Get the free PDF
                                    report to your email.</span>
                            </p>
                        </div>
                        <div class="w-full lg:w-1/4 bg-white rounded border border-gray-200 ml-0 lg:ml-4 p-5">
                            <div class="border-b border-gray-200 pb-4" v-if="failedOR04.length == 0 && successOR04.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedOR04.length != 0 || successOR04.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalOR04Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedOR04.length, totalOR04Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedOR04.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successOR04.length, totalOR04Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successOR04.length }} </span>
                                    </div>
                                </div>
                            </div>
                            <h3 class="font-bold pt-4">Who is affected by these issues?</h3>
                            <div class="affected-icons space-y-1 pt-4">
                                <div class="flex items-center">
                                    <svg width="24" height="24">
                                        <use xlink:href="#motor_impaired-icon"></use>
                                    </svg>
                                    Motor Impaired
                                </div>
                                <div class="flex items-center">
                                    <svg width="24" height="24">
                                        <use xlink:href="#vision_impaired-icon"></use>
                                    </svg>
                                    Vision Impaired
                                </div>
                                <div class="flex items-center">
                                    <svg width="24" height="24">
                                        <use xlink:href="#cognitive_disability-icon"></use>
                                    </svg>
                                    Cognitive Disability
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- OR05 -->
            <input type="radio" name="accordion" id="or05" :value="'box-or05'" v-model="selectedSection" />
            <section class="box" id="box-or05">
                <label class="box-title" for="or05">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successOR05.length > failedOR05.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedOR05.length > successOR05.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedOR05.length == 0 && successOR05.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Manipulatively hidden interactive elements should be excluded from assistive
                            technology</h3>
                    </div>
                    <div class="affected-icons hidden lg:flex">
                        <svg width="24" height="24">
                            <use xlink:href="#vision_impaired-icon"></use>
                        </svg>

                        <svg width="24" height="24">
                            <use xlink:href="#cognitive_disability-icon"></use>
                        </svg>
                    </div>
                </label>
                <label class="box-close" for="acc-close"></label>
                <div class="box-content">
                    <div class="flex flex-col-reverse lg:flex-row">
                        <div class="w-full lg:w-3/4 bg-white rounded border border-gray-200 mt-4 lg:mt-0 p-5">
                            <h3 class="font-bold">Requirement:</h3>
                            <p>
                                Elements that are hidden using CSS manipulations (opacity, height, text-indent, off-canvas,
                                etc.) should be wrapped inside an element with an "aria-hidden" attribute that equals to
                                "false", or include it directly, and have it dynamically changed to "true" when they become
                                visible.
                            </p>

                            <div v-if="failedOR05 !== null && failedOR05.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedOR05.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedOR05" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successOR05 !== null && successOR05.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successOR05.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successOR05" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <p class="mt-4">
                                Want to see all the elements and full details? <span class="font-bold">Get the free PDF
                                    report to your email.</span>
                            </p>
                        </div>
                        <div class="w-full lg:w-1/4 bg-white rounded border border-gray-200 ml-0 lg:ml-4 p-5">
                            <div class="border-b border-gray-200 pb-4" v-if="failedOR05.length == 0 && successOR05.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedOR05.length != 0 || successOR05.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalOR05Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedOR05.length, totalOR05Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedOR05.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successOR05.length, totalOR05Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successOR05.length }} </span>
                                    </div>
                                </div>
                            </div>
                            <h3 class="font-bold pt-4">Who is affected by these issues?</h3>
                            <div class="affected-icons space-y-1 pt-4">
                                <div class="flex items-center">
                                    <svg width="24" height="24">
                                        <use xlink:href="#vision_impaired-icon"></use>
                                    </svg>
                                    Vision Impaired
                                </div>
                                <div class="flex items-center">
                                    <svg width="24" height="24">
                                        <use xlink:href="#cognitive_disability-icon"></use>
                                    </svg>
                                    Cognitive Disability
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- OR05 -->
            <input type="radio" name="accordion" id="or05" :value="'box-or05'" v-model="selectedSection" />
            <section class="box" id="box-or05">
                <label class="box-title" for="or05">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successOR06.length > failedOR06.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedOR06.length > successOR06.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedOR06.length == 0 && successOR06.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Manipulatively hidden interactive elements should be excluded from tabindex</h3>
                    </div>
                    <div class="affected-icons hidden lg:flex">

                        <svg width="24" height="24">
                            <use xlink:href="#motor_impaired-icon"></use>
                        </svg>

                        <svg width="24" height="24">
                            <use xlink:href="#vision_impaired-icon"></use>
                        </svg>

                        <svg width="24" height="24">
                            <use xlink:href="#cognitive_disability-icon"></use>
                        </svg>
                    </div>
                </label>
                <label class="box-close" for="acc-close"></label>
                <div class="box-content">
                    <div class="flex flex-col-reverse lg:flex-row">
                        <div class="w-full lg:w-3/4 bg-white rounded border border-gray-200 mt-4 lg:mt-0 p-5">
                            <h3 class="font-bold">Requirement:</h3>
                            <p>
                                Elements that are hidden using CSS manipulations (opacity, height, text-indent, off-canvas,
                                etc.) should include a "tabindex" attribute that is below 0, and have it dynamically changed
                                to 0 when they become visible.
                            </p>

                            <div v-if="failedOR06 !== null && failedOR06.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedOR06.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedOR06" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successOR06 !== null && successOR06.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successOR06.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successOR06" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <p class="mt-4">
                                Want to see all the elements and full details? <span class="font-bold">Get the free PDF
                                    report to your email.</span>
                            </p>
                        </div>
                        <div class="w-full lg:w-1/4 bg-white rounded border border-gray-200 ml-0 lg:ml-4 p-5">
                            <div class="border-b border-gray-200 pb-4" v-if="failedOR06.length == 0 && successOR06.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedOR06.length != 0 || successOR06.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalOR06Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedOR06.length, totalOR06Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedOR06.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successOR06.length, totalOR06Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successOR06.length }} </span>
                                    </div>
                                </div>
                            </div>
                            <h3 class="font-bold pt-4">Who is affected by these issues?</h3>
                            <div class="affected-icons space-y-1 pt-4">
                                <div class="flex items-center">
                                    <svg width="24" height="24">
                                        <use xlink:href="#motor_impaired-icon"></use>
                                    </svg>
                                    Motor Impaired
                                </div>
                                <div class="flex items-center">
                                    <svg width="24" height="24">
                                    <use xlink:href="#vision_impaired-icon"></use>
                                </svg>
                                Vision Impaired
                            </div>
                            <div class="flex items-center">
                                <svg width="24" height="24">
                                    <use xlink:href="#cognitive_disability-icon"></use>
                                </svg>
                                Cognitive Disability
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <input type="radio" name="accordion" id="acc-close" />
    </div>
</div></template>