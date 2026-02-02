<script setup>
import { ref, watch } from 'vue'

const selectedSection = ref(null);
const props = defineProps({
    dom: Object,
});

const doc = props.dom;
const failedCR01 = [];
const successCR01 = [];
const failedCR02 = [];
const successCR02 = [];
const failedCR03 = [];
const successCR03 = [];
const failedCR04 = [];
const successCR04 = [];

const carouselElements = doc.querySelectorAll('.carousel');

// Carousels should be labeled accordingly and be presented as landmarks - CR01
carouselElements.forEach(carousel => {
    const roleAttribute = carousel.getAttribute('role');
    if (roleAttribute) {
        successCR01.push(carousel);
    } else {
        failedCR01.push(carousel);
    }
});
var totalCR01Elements = failedCR01.length + successCR01.length;

// Next and previous buttons should be labeled accordingly - CR02
carouselElements.forEach(carousel => {
    const prevButton = carousel.querySelector('.prev-button');
    const nextButton = carousel.querySelector('.next-button');
    
    const prevButtonAriaLabel = prevButton.getAttribute('aria-label');
    if (prevButtonAriaLabel) {
        successCR02.push(carousel);
    } else {
        failedCR02.push(carousel);
    }
    
    const nextButtonAriaLabel = nextButton.getAttribute('aria-label');
    if (nextButtonAriaLabel) {
        successCR02.push(carousel);
    } else {
        failedCR02.push(carousel);
    }
});
var totalCR02Elements = failedCR02.length + successCR02.length;

// Carousels should not be tagged as live regions for assistive technology - CR03
carouselElements.forEach(carousel => {
    const ariaLiveAttribute = carousel.getAttribute('aria-live');
    
    if (ariaLiveAttribute && ariaLiveAttribute.toLowerCase() === 'polite') {
        failedCR03.push(carousel);
    } else {
        successCR03.push(carousel);
    }
});
var totalCR03Elements = failedCR03.length + successCR03.length;

// Carousel pagination buttons should be labeled accordingly - CR04
carouselElements.forEach(carousel => {
    const paginationElements = carousel.querySelectorAll('.pagination-element');
    
    paginationElements.forEach(paginationElement => {
        const ariaLabelAttribute = paginationElement.getAttribute('aria-label');
        
        if (ariaLabelAttribute) {
            successCR04.push(carousel);
        } else {
            failedCR04.push(carousel);
        }
    });
});
var totalCR04Elements = failedCR04.length + successCR04.length;

// Score computation
var totalElements = totalCR01Elements + totalCR02Elements + totalCR03Elements + totalCR04Elements;
var totalFailedElements = failedCR01.length + failedCR02.length + failedCR03.length + failedCR04.length;
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
            <h2 class="flex text-3xl font-semibold text-gray-90">Carousels</h2>
            <span class="base-badge non_compliant-badge" v-if="Number(score.replace(/%/g, '')) < 60 && totalFailedElements !== 0 && totalElements !== 0"> Score: {{ score.replace(/%/g, '') }} </span>
            <span class="base-badge semi_compliant-badge" v-if="Number(score.replace(/%/g, '')) < 80 && Number(score.replace(/%/g, '')) >= 60"> Score: {{ score.replace(/%/g, '') }} </span>
            <span class="base-badge compliant-badge" v-if="Number(score.replace(/%/g, '')) >= 80"> Score: {{ score.replace(/%/g, '') }} </span>
            <span class="base-badge neutral-badge" v-if="totalFailedElements === 0 && totalElements === 0"> Neutral </span>
        </label>
        <div class="accordion arrows">
            <!-- CR01 -->
            <input type="radio" name="accordion" id="cr01" :value="'box-cr01'" v-model="selectedSection" />
            <section class="box" id="box-cr01">
                <label class="box-title" for="cr01">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successCR01.length > failedCR01.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedCR01.length > successCR01.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedCR01.length == 0 && successCR01.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Carousels should be labeled accordingly and be presented as landmarks</h3>
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
                                Carousels should include an "aria-label" attribute that equals to "carousel"/"slider" or something else that indicates the functionality, as well as be tagged as a landmark for screen-readers using a "role" tag that equals to "contentinfo".
                            </p>

                            <div v-if="failedCR01 !== null && failedCR01.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedCR01.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedCR01" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successCR01 !== null && successCR01.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successCR01.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successCR01" :key="index">
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
                            <div class="border-b border-gray-200 pb-4" v-if="failedCR01.length == 0 && successCR01.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedCR01.length != 0 || successCR01.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalCR01Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedCR01.length, totalCR01Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedCR01.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successCR01.length, totalCR01Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successCR01.length }} </span>
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

            <!-- CR02 -->
            <input type="radio" name="accordion" id="cr02" :value="'box-cr02'" v-model="selectedSection" />
            <section class="box" id="box-cr02">
                <label class="box-title" for="cr02">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successCR02.length > failedCR02.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedCR02.length > successCR02.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedCR02.length == 0 && successCR02.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Next and previous buttons should be labeled accordingly</h3>
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
                                Control elements such as next and previous icons should include an "aria-label" attribute or a screen-reader only text that explains the functionality to screen-readers.
                            </p>

                            <div v-if="failedCR02 !== null && failedCR02.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedCR02.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedCR02" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successCR02 !== null && successCR02.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successCR02.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successCR02" :key="index">
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
                            <div class="border-b border-gray-200 pb-4" v-if="failedCR02.length == 0 && successCR02.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedCR02.length != 0 || successCR02.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalCR02Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedCR02.length, totalCR02Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedCR02.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successCR02.length, totalCR02Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successCR02.length }} </span>
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

            <!-- CR03 -->
            <input type="radio" name="accordion" id="cr03" :value="'box-cr03'" v-model="selectedSection" />
            <section class="box" id="box-cr03">
                <label class="box-title" for="cr03">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successCR03.length > failedCR03.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedCR03.length > successCR03.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedCR03.length == 0 && successCR03.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Carousels should not be tagged as live regions for assistive technology</h3>
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
                                A common accessibility mistake is to treat carousels as live regions using the "aria-live" attribute and the "polite" or the "assertive values. Using this practice will cause screen-readers to cut and skip announcements.
                            </p>

                            <div v-if="failedCR03 !== null && failedCR03.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedCR03.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedCR03" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successCR03 !== null && successCR03.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successCR03.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successCR03" :key="index">
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
                            <div class="border-b border-gray-200 pb-4" v-if="failedCR03.length == 0 && successCR03.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedCR03.length != 0 || successCR03.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalCR03Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedCR03.length, totalCR03Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedCR03.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successCR03.length, totalCR03Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successCR03.length }} </span>
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

            <!-- CR04 -->
            <input type="radio" name="accordion" id="cr04" :value="'box-cr04'" v-model="selectedSection" />
            <section class="box" id="box-cr04">
                <label class="box-title" for="cr04">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successCR03.length > failedCR03.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedCR03.length > successCR03.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedCR03.length == 0 && successCR03.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Carousel pagination buttons should be labeled accordingly</h3>
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
                                Carousel pagination items (usually used as small dots at the bottom of the carousel) should indicate their functionality and slide number they control using an "aria-label" and a screen-reader only text.
                            </p>

                            <div v-if="failedCR04 !== null && failedCR04.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedCR04.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedCR04" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successCR04 !== null && successCR04.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successCR04.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successCR04" :key="index">
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
                            <div class="border-b border-gray-200 pb-4" v-if="failedCR04.length == 0 && successCR04.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedCR04.length != 0 || successCR04.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalCR04Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedCR04.length, totalCR04Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedCR04.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successCR04.length, totalCR04Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successCR04.length }} </span>
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