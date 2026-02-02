<script setup>
import { ref, watch } from 'vue'

const selectedSection = ref(null);
const props = defineProps({
    dom: Object,
});

const doc = props.dom;
const failedG01 = [];
const successG01 = [];
const failedG02 = [];
const successG02 = [];
const failedG03 = [];
const successG03 = [];
const failedG04 = [];
const successG04 = [];
const failedG05 = [];
const successG05 = [];
const failedG06 = [];
const successG06 = [];

const images = doc.querySelectorAll('img');

// Objects and embedded text of images should be described for assistive technology - G01
images.forEach((image) => {
    if (image.hasAttribute('alt')) {
        const altText = image.getAttribute('alt');
        if (!altText || altText.trim() === '') {
            failedG01.push(image);
        } else {
            successG01.push(image);
        }
    } else {
        failedG01.push(image);
    }
});
var totalG01Elements = failedG01.length + successG01.length;

// Background images behaving as standard images should be tagged and described - G02
images.forEach((image) => {
    const roleAttributeValue = image.getAttribute('role');
    if (roleAttributeValue && roleAttributeValue.toLowerCase() === 'img') {
        if (image.hasAttribute('aria-label')) {
            successG02.push(field);
        } else {
            failedG02.push(field);
        }
    }
});
var totalG02Elements = failedG02.length + successG02.length;

// Non-functional icons/spacers should be excluded from assistive technology - G03
const svgElements = doc.querySelectorAll('svg');
svgElements.forEach((svgElement) => {
    const roleAttributeValue = svgElement.getAttribute('role');
    if (roleAttributeValue) {
        if (roleAttributeValue.toLowerCase() === 'presentation' || roleAttributeValue.toLowerCase() === 'none') {
            successG03.push(svgElement);
        } else {
            failedG03.push(svgElement);
        }
    } else {
        failedG03.push(svgElement);
    }
});
const fontIconElements = doc.querySelectorAll('.icon-font');
fontIconElements.forEach((fontIconElement) => {
    const roleAttributeValue = fontIconElement.getAttribute('role');
    if (roleAttributeValue) {
        if (roleAttributeValue.toLowerCase() === 'presentation' || roleAttributeValue.toLowerCase() === 'none') {
            successG03.push(fontIconElement);
        } else {
            failedG03.push(fontIconElement);
        }
    } else {
        failedG03.push(fontIconElement);
    }
});
var totalG03Elements = failedG03.length + successG03.length;

// Figure elements should have text or be excluded from assistive technology - G04
const figureElements = doc.querySelectorAll('figure');
figureElements.forEach((figureElement) => {
    const roleAttributeValue = figureElement.getAttribute('role');
    if (roleAttributeValue == null) {
        const imgElement = figureElement.querySelector('img');
        if (imgElement !== null) {
            successG04.push(figureElement);
        } else {
            failedG04.push(figureElement);
        } 
    } else {
        failedG04.push(figureElement);
    }
});
var totalG04Elements = failedG04.length + successG04.length;

// Image map areas should be described for assistive technology - G05
const mapElements = doc.querySelectorAll('map');
mapElements.forEach((mapElement, index) => {
    const areaElements = mapElement.querySelectorAll('area');
    areaElements.forEach((areaElement) => {
        const altAttributeValue = areaElement.getAttribute('alt');
        if (altAttributeValue !== null) {
            successG05.push(mapElement);
        } else {
            failedG05.push(mapElement);
        }
    });
});
var totalG05Elements = failedG05.length + successG05.length;

// Tracker images (pixels) should be excluded from assistive technology - G06
const isPotentialTrackerPixel = (img) => {
    const width = img.width;
    const height = img.height;
    const fileSize = img.src.length;
    return width <= 1 && height <= 1 && fileSize < 50;
};
images.forEach((imgElement, index) => {
    if (isPotentialTrackerPixel(imgElement)) {
        const roleAttributeValue = imgElement.getAttribute('role');
        if (roleAttributeValue) {
            if (roleAttributeValue.toLowerCase() === 'presentation' || roleAttributeValue.toLowerCase() === 'none') {
            successG06.push(imgElement);
            } else {
                failedG06.push(imgElement);
            }
        }
    }
});
var totalG06Elements = failedG06.length + successG06.length;

function calculatePercentage(part, total) {
    var total = (part / total) * 100;
    return Math.round(total)+'%';
}

// Score computation
var totalElements = totalG01Elements + totalG02Elements + totalG03Elements + totalG04Elements + totalG05Elements + totalG06Elements;
var totalFailedElements = failedG01.length + failedG02.length + failedG03.length + failedG04.length + failedG05.length + failedG06.length;
var score = calculatePercentage((totalElements - totalFailedElements), 100);

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
            <h2 class="flex text-3xl font-semibold text-gray-90">Graphics</h2>
            <span class="base-badge non_compliant-badge" v-if="Number(score.replace(/%/g, '')) < 60"> Score: {{ score.replace(/%/g, '') }} </span>
            <span class="base-badge semi_compliant-badge" v-if="Number(score.replace(/%/g, '')) < 80 && Number(score.replace(/%/g, '')) >= 60"> Score: {{ score.replace(/%/g, '') }} </span>
            <span class="base-badge compliant-badge" v-if="Number(score.replace(/%/g, '')) >= 80"> Score: {{ score.replace(/%/g, '') }} </span>
        </label>
        <div class="accordion arrows">
            <input type="radio" name="accordion" id="g01" :value="'box-g01'" v-model="selectedSection" />
            <section class="box" id="box-g01">
                <label class="box-title" for="g01">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successG01.length > failedG01.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedG01.length > successG01.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedG01.length == 0 && successG01.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Objects and embedded text of images should be described for assistive technology</h3>
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
                                Images should have an alternative text description that describes both the objects and the embedded text that the image contains, using the "alt" attribute.
                            </p>

                            <div v-if="failedG01 !== null && failedG01.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedG01.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedG01" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successG01 !== null && successG01.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successG01.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successG01" :key="index">
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
                            <div class="border-b border-gray-200 pb-4" v-if="failedG01.length == 0 && successG01.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedG01.length != 0 || successG01.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalG01Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedG01.length, totalG01Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedG01.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successG01.length, totalG01Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successG01.length }} </span>
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

            <!-- G02 -->
            <input type="radio" name="accordion" id="g02" :value="'box-g02'" v-model="selectedSection" />
            <section class="box" id="box-g02">
                <label class="box-title" for="g02">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successG02.length > failedG02.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedG02.length > successG02.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedG02.length == 0 && successG02.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Background images behaving as standard images should be tagged and described</h3>
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
                                Background images that are not just for decoration purposes and should have the same treatment as standard images and include a "role" attribute that equals to "img" and an alternative text description in an "aria-label" attribute.
                            </p>

                            <div v-if="failedG02 !== null && failedG02.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedG02.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedG02" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successG02 !== null && successG02.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successG02.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successG02" :key="index">
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
                            <div class="border-b border-gray-200 pb-4" v-if="failedG02.length == 0 && successG02.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedG02.length != 0 || successG02.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalG02Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedG02.length, totalG02Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedG02.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successG02.length, totalG02Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successG02.length }} </span>
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

            <!-- G03 -->
            <input type="radio" name="accordion" id="g03" :value="'box-g03'" v-model="selectedSection" />
            <section class="box" id="box-g03">
                <label class="box-title" for="g03">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successG03.length > failedG03.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedG03.length > successG03.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedG03.length == 0 && successG03.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Non-functional icons/spacers should be excluded from assistive technology</h3>
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
                                Font icons, SVG or images that are being used as spacers, decorations or their purpose is already described by the content should include a "role" attribute that equals to "presentation" or "none".
                            </p>

                            <div v-if="failedG03 !== null && failedG03.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedG03.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedG03" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successG03 !== null && successG03.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successG03.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successG03" :key="index">
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
                            <div class="border-b border-gray-200 pb-4" v-if="failedG03.length == 0 && successG03.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedG03.length != 0 || successG03.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalG03Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedG03.length, totalG03Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedG03.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successG03.length, totalG03Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successG03.length }} </span>
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

            <!-- G04 -->
            <input type="radio" name="accordion" id="g04" :value="'box-g04'" v-model="selectedSection" />
            <section class="box" id="box-g04">
                <label class="box-title" for="g04">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successG04.length > failedG04.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedG04.length > successG04.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedG04.length == 0 && successG04.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Figure elements should have text or be excluded from assistive technology</h3>
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
                                Figure elements that are used to display images should have a "role" attribute that equals to "none" and the image provide itself should provide the description using an "alt" attribute.
                            </p>

                            <div v-if="failedG04 !== null && failedG04.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedG04.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedG04" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successG04 !== null && successG04.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successG04.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successG04" :key="index">
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
                            <div class="border-b border-gray-200 pb-4" v-if="failedG04.length == 0 && successG04.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedG04.length != 0 || successG04.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalG04Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedG04.length, totalG04Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedG04.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successG04.length, totalG04Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successG04.length }} </span>
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

            <!-- G05 -->
            <input type="radio" name="accordion" id="g05" :value="'box-g05'" v-model="selectedSection" />
            <section class="box" id="box-g05">
                <label class="box-title" for="g05">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successG05.length > failedG05.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedG05.length > successG05.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedG05.length == 0 && successG05.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Image map areas should be described for assistive technology</h3>
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
                                Every "area" element of a "map" tag should be regarded as a standard image and receive an alternative text description using an "alt" attribute.
                            </p>

                            <div v-if="failedG05 !== null && failedG05.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedG05.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedG05" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successG05 !== null && successG05.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successG05.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successG05" :key="index">
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
                            <div class="border-b border-gray-200 pb-4" v-if="failedG05.length == 0 && successG05.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedG05.length != 0 || successG05.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalG05Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedG05.length, totalG05Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedG05.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successG05.length, totalG05Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successG05.length }} </span>
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

            <!-- G06 -->
            <input type="radio" name="accordion" id="g06" :value="'box-g06'" v-model="selectedSection" />
            <section class="box" id="box-g06">
                <label class="box-title" for="g06">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successG06.length > failedG06.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedG06.length > successG06.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedG06.length == 0 && successG06.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Tracker images (pixels) should be excluded from assistive technology</h3>
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
                                Small or hidden tracker pixel images (often used for analytics or marketing purposes) should include a "role" attribute that equals to "none" or "presentation" so they are excluded from screen-readers.
                            </p>

                            <div v-if="failedG06 !== null && failedG06.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedG06.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedG06" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successG06 !== null && successG06.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successG06.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successG06" :key="index">
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
                            <div class="border-b border-gray-200 pb-4" v-if="failedG06.length == 0 && successG06.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedG06.length != 0 || successG06.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalG06Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedG06.length, totalG06Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedG06.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successG06.length, totalG06Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successG06.length }} </span>
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