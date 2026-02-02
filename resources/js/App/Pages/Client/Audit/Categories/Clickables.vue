<script setup>

import { ref, watch } from 'vue';

const props = defineProps({
    dom: Object,
});
const doc = props.dom;
const failedClickableTags = [];
const successClickableTags = [];
const emptyButtons = [];
const notEmptyButtons = [];
const emptyLinks = [];
const notEmptyLinks = [];
const failedLinks = [];
const successLinks = [];

var buttonLikeElements = doc.querySelectorAll('span, div, a, li, i');
var buttons = doc.querySelectorAll("button");
var anchorElements = doc.querySelectorAll('a');
var linksOpenInNewWindow = doc.querySelectorAll("a[target='_blank']");

const selectedSection = ref(null);

// Elements with button functionality should tagged assertive technology
buttonLikeElements.forEach(function (element) {
    if ((element.getAttribute('onclick') != null) || (element.getAttribute('href') != null) || (element.hasAttribute('role'))) {
        if (element.getAttribute('role') === 'button') {
            successClickableTags.push(element);
        } else {
            if ((element.getAttribute('href') == '#') || (element.getAttribute('href') == 'javascript:void(0)')) {
                failedClickableTags.push(element);
            }
        }
    }
});

buttons.forEach(function (button) {
    if (button.hasAttribute("type")) {
        successClickableTags.push(button);
    }
});

var totalClickableElements = failedClickableTags.length + successClickableTags.length;

// Buttons cannot be empty
buttons.forEach(function (button) {
    if (button.textContent.trim() === "") {
        if (button.getAttribute("aria-label") !== null) {
            notEmptyButtons.push(button);
        } else {
            emptyButtons.push(button);
        }
    } else {
        notEmptyButtons.push(button);
    }
});

buttonLikeElements.forEach(function (element) {
    if ((element.getAttribute('onclick') != null) || (element.getAttribute('href') != null) || (element.hasAttribute('role'))) {
        if (element.getAttribute('role') === 'button') {
            if (element.getAttribute("aria-label") !== null) {
                notEmptyButtons.push(element);
            } else {
                emptyButtons.push(element);
            }
        }
    }
});

var totalBtnEmpty = notEmptyButtons.length + emptyButtons.length;

// Links cannot be empty
anchorElements.forEach(function (element) {
    if (element.textContent.trim() === "") {
        if (element.getAttribute("aria-label") !== null) {
            notEmptyLinks.push(element);
        } else {
            emptyLinks.push(element);
        }
    } else {
        notEmptyLinks.push(element);
    }
});

var totalLinkEmpty = notEmptyLinks.length + emptyLinks.length;

// Links that open in new tabs/windows should tagges as asssertive technology

linksOpenInNewWindow.forEach(function (element) {
    if (element.textContent.trim() === "") {
        if (element.getAttribute("aria-label") !== null) {
            successLinks.push(element);
        } else {
            failedLinks.push(element);
        }
    } else {
        successLinks.push(element);
    }
});

var totalLiksOpenInNewWindow = successLinks.length + failedLinks.length;

var totalElements = totalClickableElements + totalBtnEmpty + totalLinkEmpty + totalLiksOpenInNewWindow;
var totalFailedElements = failedClickableTags.length + emptyButtons.length + emptyLinks.length + failedLinks.length;

var score = calculatePercentage((totalElements - totalFailedElements), 100);

function calculatePercentage(part, total) {
    var total = (part / total) * 100;
    return Math.round(total) + '%';
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
            <h2 class="flex text-3xl font-semibold text-gray-90">Clickables</h2>
            <span class="base-badge non_compliant-badge" v-if="Number(score.replace(/%/g, '')) < 60"> Score: {{
                score.replace(/%/g, '') }} </span>
            <span class="base-badge semi_compliant-badge"
                v-if="Number(score.replace(/%/g, '')) < 80 && Number(score.replace(/%/g, '')) >= 60"> Score: {{
                    score.replace(/%/g, '') }} </span>
            <span class="base-badge compliant-badge" v-if="Number(score.replace(/%/g, '')) >= 80"> Score: {{
                score.replace(/%/g, '') }} </span>
        </label>
        <div class="accordion arrows">
            <input type="radio" name="accordion" id="cb1" :value="'box-cb1'" v-model="selectedSection" />
            <section class="box" id="box-cb1">
                <label class="box-title" for="cb1">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle"
                                v-if="successClickableTags.length > failedClickableTags.length"></use>
                            <use xlink:href="#non_compliant-circle"
                                v-if="failedClickableTags.length > successClickableTags.length"></use>
                            <use xlink:href="#neutral-circle"
                                v-if="failedClickableTags.length == 0 && successClickableTags.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Elements with button functionality should be tagged for assistive technology</h3>
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
                                Elements that behave as buttons but are built using other tags such as span, div, a or
                                others, should include a "role" attribute that equals to "button".
                            </p>
                            <div v-if="failedClickableTags !== null && failedClickableTags.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedClickableTags.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedClickableTags"
                                            :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successClickableTags !== null && successClickableTags.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successClickableTags.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successClickableTags"
                                            :key="index">
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
                            <div class="border-b border-gray-200 pb-4" v-if="failedClickableTags.length == 0 && successClickableTags.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement
                                    non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedClickableTags.length != 0 || failedClickableTags.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalClickableElements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill"
                                                :style="'width: ' + calculatePercentage(failedClickableTags.length, totalClickableElements)">
                                            </div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedClickableTags.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill"
                                                :style="'width: ' + calculatePercentage(successClickableTags.length, totalClickableElements)">
                                            </div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successClickableTags.length }} </span>
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
            <input type="radio" name="accordion" id="cb2" :value="'box-cb2'" v-model="selectedSection" />
            <section class="box" id="box-cb2">
                <label class="box-title" for="cb2">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="notEmptyButtons.length > emptyButtons.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="emptyButtons.length > notEmptyButtons.length">
                            </use>
                            <use xlink:href="#neutral-circle"
                                v-if="emptyButtons.length == 0 && notEmptyButtons.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Buttons cannot be empty</h3>
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
                                Buttons should include text explaining their functionality, and if icons are used as
                                buttons, a screen-reader only text or an "aria-label" attribute should be used for that
                                description.
                            </p>

                            <div v-if="emptyButtons !== null && emptyButtons.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ emptyButtons.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in emptyButtons" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div v-if="notEmptyButtons !== null && notEmptyButtons.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ notEmptyButtons.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in notEmptyButtons" :key="index">
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
                            <div class="border-b border-gray-200 pb-4"
                                v-if="emptyButtons.length == 0 && notEmptyButtons.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement
                                    non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"
                                v-if="emptyButtons.length != 0 || notEmptyButtons.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalBtnEmpty }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill"
                                                :style="'width: ' + calculatePercentage(emptyButtons.length, totalBtnEmpty)">
                                            </div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ emptyButtons.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill"
                                                :style="'width: ' + calculatePercentage(notEmptyButtons.length, totalBtnEmpty)">
                                            </div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ notEmptyButtons.length }} </span>
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
            <input type="radio" name="accordion" id="cb3" :value="'box-cb3'" v-model="selectedSection" />
            <section class="box" id="box-cb3">
                <label class="box-title" for="cb3">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="notEmptyLinks.length > emptyLinks.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="emptyLinks.length > notEmptyLinks.length"></use>
                            <use xlink:href="#neutral-circle" v-if="emptyLinks.length == 0 && notEmptyLinks.length == 0">
                            </use>
                        </svg>
                        <h3 class="ml-2">Links cannot be empty</h3>
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
                                Links should include text that is setting expectations to what page they are leading to. If
                                empty links are used as layout wrapping elements, a screen-reader only text or an
                                "aria-label" attribute should be used for that description.
                            </p>

                            <div v-if="emptyLinks !== null && emptyLinks.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ emptyLinks.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in emptyLinks" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div v-if="notEmptyLinks !== null && notEmptyLinks.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ notEmptyLinks.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in notEmptyLinks" :key="index">
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
                            <div class="border-b border-gray-200 pb-4"
                                v-if="emptyLinks.length == 0 && notEmptyLinks.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement
                                    non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"
                                v-if="emptyLinks.length != 0 || notEmptyLinks.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalLinkEmpty }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill"
                                                :style="'width: ' + calculatePercentage(emptyLinks.length, totalLinkEmpty)">
                                            </div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ emptyLinks.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill"
                                                :style="'width: ' + calculatePercentage(notEmptyLinks.length, totalLinkEmpty)">
                                            </div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ notEmptyLinks.length }} </span>
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
            <input type="radio" name="accordion" id="cb4" :value="'box-cb4'" v-model="selectedSection" />
            <section class="box" id="box-cb4">
                <label class="box-title" for="cb4">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successLinks.length > failedLinks.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedLinks.length > successLinks.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedLinks.length == 0 && successLinks.length == 0">
                            </use>
                        </svg>
                        <h3 class="ml-2">Links that open in new tabs/windows should be tagged for assistive technology</h3>
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
                                Links that open in a new tab or a new window should either have an "aria-label" attribute or
                                a screen-reader only element explaining to screen-readers that this opens in a new tab.
                            </p>

                            <div v-if="failedLinks !== null && failedLinks.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedLinks.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedLinks" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div v-if="successLinks !== null && successLinks.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successLinks.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successLinks" :key="index">
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
                            <div class="border-b border-gray-200 pb-4"
                                v-if="failedLinks.length == 0 && successLinks.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement
                                    non-applicable.
                                </p>
                            </div>
                            <div class="border-b border-gray-200 pb-4"
                                v-if="failedLinks.length != 0 || successLinks.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalLiksOpenInNewWindow }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill"
                                                :style="'width: ' + calculatePercentage(failedLinks.length, totalLiksOpenInNewWindow)">
                                            </div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedLinks.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill"
                                                :style="'width: ' + calculatePercentage(successLinks.length, totalLiksOpenInNewWindow)">
                                            </div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successLinks.length }} </span>
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
            <input type="radio" name="accordion" id="acc-close" />
        </div>
    </div>
</template>