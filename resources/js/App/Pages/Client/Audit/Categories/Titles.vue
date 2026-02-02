<script setup>

import { ref, watch } from 'vue'

const selectedSection = ref(null);
const props = defineProps({
    dom: Object,
});
const doc = props.dom;
const failedT01 = [];
const successT01 = [];
const failedT02 = [];
const successT02 = [];
const failedT03 = [];
const successT03 = [];
const failedT04 = [];
const successT04 = [];
const headings = [];

// Every page should include a single H1 title - T01
const h1Elements = doc.getElementsByTagName('h1');
function hasH1Element() {
    return h1Elements.length > 0;
}
if (hasH1Element()) {
    h1Elements.forEach(h1Element => {
        successT01.push(h1Element);
    });
    
} else {
    h1Elements.forEach(h1Element => {
        failedT01.push(h1Element);
    });
}
var totalT01Elements = failedT01.length + successT01.length;

// Titles cannot be empty - T02
const headingElements = doc.querySelectorAll('h1, h2, h3, h4, h5, h6');
headingElements.forEach(heading => {
    const text = heading.textContent.trim();
    if (text === '') {
        failedT02.push(heading);
    } else {
        successT02.push(heading);
    }

    if (!headings.includes(heading.tagName)) {
        headings.push(heading.tagName)
    }
});
var totalT02Elements = failedT02.length + successT02.length;

// Titles built as text tags should be labeled as headings for assistive technology - T03
const titleElements = doc.querySelectorAll('div[aria-role="heading"], span[aria-role="heading"], p[aria-role="heading"]');
titleElements.forEach(element => {
    const roleAttribute = element.getAttribute('aria-role');
    if (!roleAttribute || roleAttribute !== 'heading') {
        failedT03.push(element);
    } else {
        successT03.push(element);
    }
});
var totalT03Elements = failedT03.length + successT03.length;

// Titles should have a consistent hierarchy - T04
headings.sort()
let lastHeading = headings[headings.length - 1];
if (lastHeading == 'H' + headings.length ) {
    successT04.push([1])
} else {
    failedT04.push([]);
}
var totalT04Elements = failedT04.length + successT04.length;

// Score computation
var totalElements = totalT01Elements + totalT02Elements + totalT03Elements + totalT04Elements;
var totalFailedElements = failedT01.length + failedT02.length + failedT03.length + failedT04.length;
var score = totalFailedElements == 0 ? '100%' : calculatePercentage((totalElements - totalFailedElements), 100);

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
            <h2 class="flex text-3xl font-semibold text-gray-90">Titles</h2>
            <span class="base-badge non_compliant-badge" v-if="Number(score.replace(/%/g, '')) < 60 && totalFailedElements !== 0 && totalElements !== 0"> Score: {{ score.replace(/%/g, '') }} </span>
            <span class="base-badge semi_compliant-badge" v-if="Number(score.replace(/%/g, '')) < 80 && Number(score.replace(/%/g, '')) >= 60"> Score: {{ score.replace(/%/g, '') }} </span>
            <span class="base-badge compliant-badge" v-if="Number(score.replace(/%/g, '')) >= 80"> Score: {{ score.replace(/%/g, '') }} </span>
            <span class="base-badge neutral-badge" v-if="totalFailedElements === 0 && totalElements === 0"> Neutral </span>
        </label>
        <div class="accordion arrows">
            <!-- T01 -->
            <input type="radio" name="accordion" id="t01" :value="'box-t01'" v-model="selectedSection" />
            <section class="box" id="box-t01">
                <label class="box-title" for="t01">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successT01.length > failedT01.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedT01.length > successT01.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedT01.length == 0 && successT01.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Every page should include a single H1 title</h3>
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
                                An H1 title provides information to blind-users using screen-readers of what the main topic
                                of the page is and each page should have exactly one H1 title.
                            </p>

                            <div v-if="failedT01 !== null && failedT01.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedT01.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedT01" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successT01 !== null && successT01.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successT01.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successT01" :key="index">
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
                            <div class="border-b border-gray-200 pb-4" v-if="failedT01.length == 0 && successT01.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedT01.length != 0 || successT01.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalT01Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedT01.length, totalT01Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedT01.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successT01.length, totalT01Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successT01.length }} </span>
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
            <!-- T02 -->
            <input type="radio" name="accordion" id="t02" :value="'box-t02'" v-model="selectedSection" />
            <section class="box" id="box-t02">
                <label class="box-title" for="t02">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successT02.length > failedT02.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedT02.length > successT02.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedT02.length == 0 && successT02.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Titles cannot be empty</h3>
                    </div>
                    <div class="affected-icons hidden lg:flex">
                        <svg width="24" height="24">
                            <use xlink:href="#vision_impaired-icon"></use>
                        </svg>
                    </div>
                </label>
                <label class="box-close" for="acc-close"></label>
                <div class="box-content">
                    <div class="flex flex-col-reverse lg:flex-row">
                        <div class="w-full lg:w-3/4 bg-white rounded border border-gray-200 mt-4 lg:mt-0 p-5">
                            <h3 class="font-bold">Requirement:</h3>
                            <p>
                                HTML title elements (H1-6) should have texts. If images or links are used, they should
                                include an alternative or screen-reader only text.
                            </p>

                            <div v-if="failedT02 !== null && failedT02.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedT02.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedT02" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successT02 !== null && successT02.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successT02.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successT02" :key="index">
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
                            <div class="border-b border-gray-200 pb-4" v-if="failedT02.length == 0 && successT02.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedT02.length != 0 || successT02.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalT02Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedT02.length, totalT02Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedT02.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successT02.length, totalT02Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successT02.length }} </span>
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
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- T03 -->
            <input type="radio" name="accordion" id="t03" :value="'box-t03'" v-model="selectedSection" />
            <section class="box" id="box-t03">
                <label class="box-title" for="t03">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successT03.length > failedT03.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedT03.length > successT03.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedT03.length == 0 && successT03.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Titles built as text tags should be labeled as headings for assistive technology
                        </h3>
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
                                Elements that visually appear as titles but are coded with a non-heading HTML Tag should
                                include a "role" attribute that equals to "heading" or have their tags fixed.
                            </p>

                            <div v-if="failedT03 !== null && failedT03.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedT03.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedT03" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successT03 !== null && successT03.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successT03.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successT03" :key="index">
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
                            <div class="border-b border-gray-200 pb-4" v-if="failedT03.length == 0 && successT03.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedT03.length != 0 || successT03.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalT03Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedT03.length, totalT03Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedT03.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successT03.length, totalT03Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successT03.length }} </span>
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
            <!-- T04 -->
            <input type="radio" name="accordion" id="t04" :value="'box-t04'" v-model="selectedSection" />
            <section class="box" id="box-t04">
                <label class="box-title" for="t04">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successT04.length > failedT04.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedT04.length > successT04.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedT04.length == 0 && successT04.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Titles should have a consistent hierarchy</h3>
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
                                Title levels should not be skipped and should be built with consistent hierarchy. For
                                example: you cannot have "h4" titles and "h2" titles without having "h3" titles.
                            </p>

                            <p class="mt-4">
                                Want to see all the elements and full details? <span class="font-bold">Get the free PDF
                                    report to your email.</span>
                            </p>
                        </div>
                        <div class="w-full lg:w-1/4 bg-white rounded border border-gray-200 ml-0 lg:ml-4 p-5">
                            <div class="border-b border-gray-200 pb-4" v-if="failedT04.length == 0 && successT04.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedT04.length != 0 || successT04.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalT04Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedT04.length, totalT04Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedT04.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successT04.length, totalT04Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successT04.length }} </span>
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