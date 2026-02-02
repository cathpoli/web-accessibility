<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
    dom: Object,
});

const selectedSection = ref(null);
const doc = props.dom;
const header = doc.querySelectorAll('head');
const hasPageName = ref(false);
const titlePage = header[0].querySelectorAll('title');
const html = doc.querySelectorAll('html')[0];
const hasLanguage = ref(false);
const meta = doc.querySelectorAll('meta')[0];
const canPinchZoom = ref(false);
const failedD01 = [];
const successD01 = [];
const failedD02 = [];
const successD02 = [];
const failedD03 = [];
const successD03 = [];
const failedD04 = [];
const successD04 = [];

// The HEAD element should include a title element with the name of the page - D01
if (titlePage[0].innerText != null) {
    hasPageName.value = true
    successD01.push([1]);
}
var totalD01Elements = failedD01.length + successD01.length;

// The HTML element should include a proper "lang" attribute - D02
if (html.getAttribute('lang') != null) {
    hasLanguage.value = true
    successD02.push([1]);
}
var totalD02Elements = failedD02.length + successD02.length;

// Meta viewport allows display scaling of at least 200% - D03
if (meta.getAttribute('user-scalable') != null && meta.getAttribute('maximum-scale') != null) {
    if (meta.getAttribute('user-scalable') == 'yes' && meta.getAttribute('maximum-scale') >= 2) {
        canPinchZoom.value = true;
        successD03.push([1]);
    } else {
        failedD03.push([1]);
    }
}
var totalD03Elements = failedD03.length + successD03.length;

// Page landmarks should be tagged and described for assistive technology - D04
const mainElement = doc.querySelector('main');
if (!mainElement) {
    failedD04.push(mainElement);
} else {
    successD04.push(mainElement)
}

const footerElement = doc.querySelector('footer');
if (!footerElement) {
    failedD04.push(footerElement);
} else {
    successD04.push(footerElement)
}
var totalD04Elements = failedD04.length + successD04.length;

// Score computation
var totalElements = totalD01Elements + totalD02Elements + totalD03Elements + totalD04Elements;
var totalFailedElements = failedD01.length + failedD02.length + failedD03.length + failedD04.length;
var score = totalFailedElements == 0 ? '100%' : calculatePercentage((totalElements - totalFailedElements), 100);

console.log(successD03.length);

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

function calculatePercentage(part, total) {
    var total = (part / total) * 100;
    return Math.round(total)+'%';
}
</script>


<template>
    <div class="bg-white rounded-2xl blue-shadow">
        <label for="acc-close" class="flex justify-between items-center p-5">
            <h2 class="flex text-3xl font-semibold text-gray-90">Documents</h2>
            <span class="base-badge non_compliant-badge" v-if="Number(score.replace(/%/g, '')) < 60 && totalFailedElements !== 0 && totalElements !== 0"> Score: {{ score.replace(/%/g, '') }} </span>
            <span class="base-badge semi_compliant-badge" v-if="Number(score.replace(/%/g, '')) < 80 && Number(score.replace(/%/g, '')) >= 60"> Score: {{ score.replace(/%/g, '') }} </span>
            <span class="base-badge compliant-badge" v-if="Number(score.replace(/%/g, '')) >= 80"> Score: {{ score.replace(/%/g, '') }} </span>
            <span class="base-badge neutral-badge" v-if="totalFailedElements === 0 && totalElements === 0"> Neutral </span>
        </label>
        <div class="accordion arrows">
            <input type="radio" name="accordion" id="dc1" :value="'box-dc1'" v-model="selectedSection" />
            <section class="box" id="box-dc1">
                <label class="box-title" for="dc1">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle"
                                v-if="hasPageName"></use>
                            <use xlink:href="#non_compliant-circle"
                                v-if="!hasPageName"></use>
                        </svg>
                        <h3 class="ml-2">The HEAD element should include a title element with the name of the page</h3>
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
                                The title tag of the HEAD section (also the title that appears in the browser tab and Search Engine result pages)
                                should exist and describe the name of the current webpage for screen-reader users.
                            </p>

                            <div v-if="hasPageName">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ titlePage.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in titlePage" :key="index">
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
                            <div class="border-b border-gray-200 pb-4">
                                <h3 class="font-bold pb-4">Total Elements {{ hasPageName ? 1 : 0 }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(!hasPageName ? 1 : 0, 1)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ !hasPageName ? 1 : 0 }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(hasPageName ? 1 : 0, 1)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ hasPageName ? 1 : 0 }} </span>
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
            <input type="radio" name="accordion" id="dc2" :value="'box-dc2'" v-model="selectedSection" />
            <section class="box" id="box-dc2">
                <label class="box-title" for="dc2">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle"
                                v-if="hasLanguage"></use>
                            <use xlink:href="#non_compliant-circle"
                                v-if="!hasLanguage"></use>
                        </svg>
                        <h3 class="ml-2">The HTML element should include a proper "lang" attribute</h3>
                    </div>
                    <div class="affected-icons hidden lg:flex">
                        <div class="affected-icons hidden lg:flex">
                            <svg width="24" height="24">
                                <use xlink:href="#vision_impaired-icon"></use>
                            </svg>
    
                            <svg width="24" height="24">
                                <use xlink:href="#cognitive_disability-icon"></use>
                            </svg>
                        </div>
                    </div>
                </label>
                <label class="box-close" for="acc-close"></label>
                <div class="box-content">
                    <div class="flex flex-col-reverse lg:flex-row">
                        <div class="w-full lg:w-3/4 bg-white rounded border border-gray-200 mt-4 lg:mt-0 p-5">
                            <h3 class="font-bold">Requirement:</h3>
                            <p>
                                The HTML tag should include a "lang" attribute that represents the main language of the webpage so screen-readers can calibrate on it for blind users.
                            </p>

                            <div v-if="hasLanguage">
                                <div class="mt-4">
                                    <h3 class="font-bold">1 Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                    <li class="scan-list__item">
                                        <span class="scan-list__code">
                                        <code>	&lt;html&gt;&lt;/html&gt; </code>
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
                            <div class="border-b border-gray-200 pb-4">
                                <h3 class="font-bold pb-4">Total Elements {{ hasLanguage ? 1 : 0 }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(!hasLanguage ? 1 : 0, 1)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ !hasLanguage ? 1 : 0 }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(hasLanguage ? 1 : 0, 1)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ hasLanguage ? 1 : 0 }} </span>
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
            <input type="radio" name="accordion" id="dc3" :value="'box-dc3'" v-model="selectedSection" />
            <section class="box" id="box-dc3">
                <label class="box-title" for="dc3">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle"
                                v-if="canPinchZoom"></use>
                            <use xlink:href="#non_compliant-circle"
                                v-if="!canPinchZoom"></use>
                        </svg>  
                        <h3 class="ml-2">Meta viewport allows display scaling of at least 200%</h3>
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
                                The meta viewport tag should allow vision impaired users to pinch-zoom at least double the standard scaling using the user-scalable="yes" and maximum-scale={2 or higher} content strings.
                            </p>

                            <div v-if="canPinchZoom">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ 1 }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                    <li class="scan-list__item">
                                        <span class="scan-list__code">
                                        <code>{{ meta }}</code>
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
                            <div class="border-b border-gray-200 pb-4">
                                <h3 class="font-bold pb-4">Total Elements {{ canPinchZoom ? 1 : 0 }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(!canPinchZoom ? 1 : 0, 1)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ !canPinchZoom ? 1 : 0 }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(canPinchZoom ? 1 : 0, 1)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ canPinchZoom ? 1 : 0 }} </span>
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
            <input type="radio" name="accordion" id="dc4" :value="'box-dc4'" v-model="selectedSection" />
            <section class="box" id="box-dc4">
                <label class="box-title" for="dc4">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successD04.length > failedD04.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedD04.length > successD04.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedD04.length == 0 && successD04.length == 0"></use>
                        </svg> 
                        <h3 class="ml-2">Page landmarks should be tagged and described for assistive technology</h3>
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
                                Landmarks such as main content and footer should be built using their corresponding HTML5 element or include a
                                description using an "aria-label" attribute and a "role" tag that equals to "contentinfo" or "main".
                                Landmarks such as search and navigation are tested in other sections.
                            </p>

                            <div v-if="failedD04 !== null && failedD04.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedD04.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedD04" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successD04 !== null && successD04.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successD04.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successD04" :key="index">
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
                            <div class="border-b border-gray-200 pb-4" v-if="failedD04.length == 0 && successD04.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedD04.length != 0 || successD04.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalD04Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedD04.length, totalD04Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedD04.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successD04.length, totalD04Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successD04.length }} </span>
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
    </div>
</template>