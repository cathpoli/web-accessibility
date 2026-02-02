<script setup>
import { ref, watch } from 'vue'

const selectedSection = ref(null);
const props = defineProps({
    dom: Object,
});

const doc = props.dom;
const failedGE01 = [];
const successGE01 = [];
const failedGE02 = [];
const successGE02 = [];
const failedGE03 = [];
const successGE03 = [];
const failedGE04 = [];
const successGE04 = [];
const failedGE05 = [];
const successGE05 = [];

// Deleted text should include an additional description - GE01
const spans = doc.querySelectorAll('span');
spans.forEach(span => {
    const computedStyle = window.getComputedStyle(span);
    if (computedStyle.display === 'none') {
        const ariaLabel = span.getAttribute('aria-label');
        if (ariaLabel) {
            successGE01.push(span);
        } else {
            failedGE01.push(span);
        }
    }
});
var totalGE01Elements = failedGE01.length + successGE01.length;

// Breadcrumbs should be labeled accordingly and tagged as navigation - GE02
const mainElements = doc.querySelectorAll('main');
mainElements.forEach(mainElement => {
    const roleAttributeValue = mainElement.getAttribute('role');
    if (roleAttributeValue && roleAttributeValue.toLowerCase() === 'navigation') {
        if (mainElement.hasAttribute('aria-label')) {
            successGE02.push(mainElement);
        } else {
            failedGE02.push(mainElement);
        }
    }
});
var totalGE02Elements = failedGE02.length + successGE02.length;

// Reviews and ratings should be labeled and described accordingly - GE03
const ratingElements = doc.querySelectorAll('.rating');
ratingElements.forEach(ratingElement => {
    const ariaLabel = ratingElement.getAttribute('aria-label');
    if (ariaLabel) {
        successGE03.push(ratingElement);
    } else {
        failedGE03.push(ratingElement);
    }
});
var totalGE03Elements = failedGE03.length + successGE03.length;

// Include a web accessibility interface - GE04
const accessibilityWidgets = doc.querySelectorAll('.accessibility-widget');
accessibilityWidgets.forEach(accessibilityWidget => {
    if (accessibilityWidget) {
        successGE04.push(accessibilityWidget);
    } else {
        failedGE04.push(accessibilityWidget);
    }
});
var totalGE04Elements = failedGE04.length + successGE04.length;

// Iframe elements should be labeled or titled - GE05
const iframes = doc.querySelectorAll('iframe');
iframes.forEach(iframe => {
    const title = iframe.getAttribute('title');
    const ariaLabel = iframe.getAttribute('aria-label');
    if (title || ariaLabel) {
        successGE05.push(iframe);
    } else {
        failedGE05.push(iframe);
    }
});
var totalGE05Elements = failedGE05.length + successGE05.length;

// Score computation
var totalElements = totalGE01Elements + totalGE02Elements + totalGE03Elements + totalGE04Elements + totalGE05Elements;
var totalFailedElements = failedGE01.length + failedGE02.length + failedGE03.length + failedGE04.length + failedGE05.length;
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
            <h2 class="flex text-3xl font-semibold text-gray-90">General</h2>
            <span class="base-badge non_compliant-badge" v-if="Number(score.replace(/%/g, '')) < 60 && totalFailedElements !== 0 && totalElements !== 0"> Score: {{ score.replace(/%/g, '') }} </span>
            <span class="base-badge semi_compliant-badge" v-if="Number(score.replace(/%/g, '')) < 80 && Number(score.replace(/%/g, '')) >= 60"> Score: {{ score.replace(/%/g, '') }} </span>
            <span class="base-badge compliant-badge" v-if="Number(score.replace(/%/g, '')) >= 80"> Score: {{ score.replace(/%/g, '') }} </span>
            <span class="base-badge neutral-badge" v-if="totalFailedElements === 0 && totalElements === 0"> Neutral </span>
        </label>
        <div class="accordion arrows">
            <!-- GE01 -->
            <input type="radio" name="accordion" id="ge01" :value="'box-ge01'" v-model="selectedSection" />
            <section class="box" id="box-ge01">
                <label class="box-title" for="ge01">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successGE01.length > failedGE01.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedGE01.length > successGE01.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedGE01.length == 0 && successGE01.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Deleted text should include an additional description</h3>
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
                                Visually deleted texts (often used to show a previous price of a sale) should include an "aria-label" attribute or a screen-reader only text that explains this is deleted text.
                            </p>

                            <div v-if="failedGE01 !== null && failedGE01.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedGE01.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedGE01" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successGE01 !== null && successGE01.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successGE01.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successGE01" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                            <p class="mt-4">
                                Want to see all the elements and full details? <span class="font-bold">Get the free PDF report to your email.</span>
                            </p>
                        </div>
                        <div class="w-full lg:w-1/4 bg-white rounded border border-gray-200 ml-0 lg:ml-4 p-5">
                            <div class="border-b border-gray-200 pb-4" v-if="failedGE01.length == 0 && successGE01.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedGE01.length != 0 || successGE01.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalGE01Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedGE01.length, totalGE01Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedGE01.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successGE01.length, totalGE01Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successGE01.length }} </span>
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

            <!-- GE02 -->
            <input type="radio" name="accordion" id="ge02" :value="'box-ge02'" v-model="selectedSection" />
            <section class="box" id="box-ge02">
                <label class="box-title" for="ge02">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successGE02.length > failedGE02.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedGE02.length > successGE02.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedGE02.length == 0 && successGE02.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Breadcrumbs should be labeled accordingly and tagged as navigation</h3>
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
                                Breadcrumbs main element should include a "role" attribute that equals to "navigation" and an "aria-label" attribute that describes the functionality."
                            </p>

                            <div v-if="failedGE02 !== null && failedGE02.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedGE02.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedGE02" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successGE02 !== null && successGE02.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successGE02.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successGE02" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                            <p class="mt-4">
                                Want to see all the elements and full details? <span class="font-bold">Get the free PDF report to your email.</span>
                            </p>
                        </div>
                        <div class="w-full lg:w-1/4 bg-white rounded border border-gray-200 ml-0 lg:ml-4 p-5">
                            <div class="border-b border-gray-200 pb-4" v-if="failedGE02.length == 0 && successGE02.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedGE02.length != 0 || successGE02.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalGE02Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedGE02.length, totalGE02Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedGE02.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successGE02.length, totalGE02Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successGE02.length }} </span>
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

            <!-- GE03 -->
            <input type="radio" name="accordion" id="ge03" :value="'box-ge03'" v-model="selectedSection" />
            <section class="box" id="box-ge03">
                <label class="box-title" for="ge03">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successGE03.length > failedGE03.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedGE03.length > successGE03.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedGE03.length == 0 && successGE03.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Reviews and ratings should be labeled and described accordingly</h3>
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
                                Ratings/scores that are represented visually using stars, meters or other indicators should either have an "aria-label" attribute or a screen-reader only texts conveying the score/rate.
                            </p>

                            <div v-if="failedGE03 !== null && failedGE03.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedGE03.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedGE03" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successGE03 !== null && successGE03.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successGE03.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successGE03" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                            <p class="mt-4">
                                Want to see all the elements and full details? <span class="font-bold">Get the free PDF report to your email.</span>
                            </p>
                        </div>
                        <div class="w-full lg:w-1/4 bg-white rounded border border-gray-200 ml-0 lg:ml-4 p-5">
                            <div class="border-b border-gray-200 pb-4" v-if="failedGE03.length == 0 && successGE03.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedGE03.length != 0 || successGE03.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalGE03Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedGE03.length, totalGE03Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedGE03.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successGE03.length, totalGE03Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successGE03.length }} </span>
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

            <!-- GE04 -->
            <input type="radio" name="accordion" id="ge04" :value="'box-ge04'" v-model="selectedSection" />
            <section class="box" id="box-ge04">
                <label class="box-title" for="ge04">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successGE04.length > failedGE04.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedGE04.length > successGE04.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedGE04.length == 0 && successGE04.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Include a web accessibility interface</h3>
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
                                Making use of an accessibility interface helps to ensure that vision impaired users can adjust some of the UI and the design (font-size, colors, spacing, etc.) to their needs. It is not a compliance requirement, but can help to improve compliance.
                            </p>

                            <div v-if="failedGE04 !== null && failedGE04.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedGE04.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedGE04" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successGE04 !== null && successGE04.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successGE04.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successGE04" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                            <p class="mt-4">
                                Want to see all the elements and full details? <span class="font-bold">Get the free PDF report to your email.</span>
                            </p>
                        </div>
                        <div class="w-full lg:w-1/4 bg-white rounded border border-gray-200 ml-0 lg:ml-4 p-5">
                            <div class="border-b border-gray-200 pb-4" v-if="failedGE04.length == 0 && successGE04.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedGE04.length != 0 || successGE04.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalGE04Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedGE04.length, totalGE04Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedGE04.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successGE04.length, totalGE04Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successGE04.length }} </span>
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

            <!-- GE05 -->
            <input type="radio" name="accordion" id="ge05" :value="'box-ge05'" v-model="selectedSection" />
            <section class="box" id="box-ge05">
                <label class="box-title" for="ge05">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successGE05.length > failedGE05.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedGE05.length > successGE05.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedGE05.length == 0 && successGE05.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Iframe elements should be labeled or titled</h3>
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
                                Iframe elements should include a "title" or an "aria-label" attribute explaining the purpose, the functionality or the destination of the iframe, or be tagged hidden for screen-readers if irrelevant.
                            </p>

                            <div v-if="failedGE05 !== null && failedGE05.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedGE05.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedGE05" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successGE05 !== null && successGE05.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successGE05.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successGE05" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                            <p class="mt-4">
                                Want to see all the elements and full details? <span class="font-bold">Get the free PDF report to your email.</span>
                            </p>
                        </div>
                        <div class="w-full lg:w-1/4 bg-white rounded border border-gray-200 ml-0 lg:ml-4 p-5">
                            <div class="border-b border-gray-200 pb-4" v-if="failedGE05.length == 0 && successGE05.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedGE05.length != 0 || successGE05.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalGE05Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedGE05.length, totalGE05Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedGE05.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successGE05.length, totalGE05Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successGE05.length }} </span>
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