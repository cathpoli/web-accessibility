<script setup>
import { ref, watch } from 'vue'

const selectedSection = ref(null);
const props = defineProps({
    dom: Object,
});

const doc = props.dom;
const failedR01 = [];
const successR01 = [];
const failedR02 = [];
const successR02 = [];
const failedR03 = [];
const successR03 = [];

const body = doc.body;
const computedStyle = window.getComputedStyle(body);

// Font sizes should be large enough to be readable - R01
const fontSize = computedStyle.getPropertyValue('font-size');
if (fontSize > 11) {
    successR01.push(body);
} else {
    failedR01.push(body);
}
var totalR01Elements = failedR01.length + successR01.length;

// Letter spacing should be wide enough to be readable - R02
const letterSpacing = computedStyle.getPropertyValue('letter-spacing');
if (letterSpacing > -1) {
    successR02.push(body);
} else {
    failedR02.push(body);
}
var totalR02Elements = failedR02.length + successR02.length;

// Foreground and background colors have sufficient contrast - R03
const elements = doc.querySelectorAll('*');

elements.forEach(element => {
    const bgColor = getComputedStyle(element).backgroundColor;
    const textColor = getComputedStyle(element).color;

    const contrastRatio = getContrastRatio(bgColor, textColor);

    if (contrastRatio < 4.5) {
        successR03.push(element);
    } else {
        failedR03.push(element);
    }
});
function getContrastRatio(color1, color2) {
    const luminance1 = getLuminance(color1);
    const luminance2 = getLuminance(color2);

    const brighter = Math.max(luminance1, luminance2);
    const darker = Math.min(luminance1, luminance2);

    return (brighter + 0.05) / (darker + 0.05);
}
function getLuminance(color) {
    if (color) {
        const rgb = color.match(/\d+/g).map(Number);
        const [r, g, b] = rgb.map(value => {
            value /= 255;
            return value <= 0.03928 ? value / 12.92 : Math.pow((value + 0.055) / 1.055, 2.4);
        });

        return 0.2126 * r + 0.7152 * g + 0.0722 * b;
    }
}
var totalR03Elements = failedR03.length + successR03.length;

// Score computation
var totalElements = totalR01Elements + totalR02Elements + totalR03Elements;
var totalFailedElements = failedR01.length + failedR02.length + failedR03.length;
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
            <h2 class="flex text-3xl font-semibold text-gray-90">Readability</h2>
            <span class="base-badge non_compliant-badge" v-if="Number(score.replace(/%/g, '')) < 60 && totalFailedElements !== 0 && totalElements !== 0"> Score: {{ score.replace(/%/g, '') }} </span>
            <span class="base-badge semi_compliant-badge" v-if="Number(score.replace(/%/g, '')) < 80 && Number(score.replace(/%/g, '')) >= 60"> Score: {{ score.replace(/%/g, '') }} </span>
            <span class="base-badge compliant-badge" v-if="Number(score.replace(/%/g, '')) >= 80"> Score: {{ score.replace(/%/g, '') }} </span>
            <span class="base-badge neutral-badge" v-if="totalFailedElements === 0 && totalElements === 0"> Neutral </span>
        </label>
        <div class="accordion arrows">
            <!-- R01 -->
            <input type="radio" name="accordion" id="r01" :value="'box-r01'" v-model="selectedSection" />
            <section class="box" id="box-r01">
                <label class="box-title" for="r01">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successR01.length > failedR01.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedR01.length > successR01.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedR01.length == 0 && successR01.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Font sizes should be large enough to be readable</h3>
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
                                Font sizes should be at least 11px in size in order to stay readable in the majority of font families. This should be verified also when using dynamic units such as REM or percents.
                            </p>

                            <div v-if="failedR01 !== null && failedR01.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedR01.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedR01" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successR01 !== null && successR01.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successR01.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successR01" :key="index">
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
                            <div class="border-b border-gray-200 pb-4" v-if="failedR01.length == 0 && successR01.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedR01.length != 0 || successR01.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalR01Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedR01.length, totalR01Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedR01.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successR01.length, totalR01Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successR01.length }} </span>
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
            <!-- R02 -->
            <input type="radio" name="accordion" id="r02" :value="'box-r02'" v-model="selectedSection" />
            <section class="box" id="box-r02">
                <label class="box-title" for="r02">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successR02.length > failedR02.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedR02.length > successR02.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedR02.length == 0 && successR02.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Letter spacing should be wide enough to be readable</h3>
                    </div>
                    <div class="affected-icons hidden lg:flex">
                        <div class="affected-icons hidden lg:flex">
                            <svg width="24" height="24">
                                <use xlink:href="#vision_impaired-icon"></use>
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
                                Letter spacing should not be set to below -1px in order to stay readable in the majority of font families. This should be verified also when using dynamic units such as REM or percents.
                            </p>

                            <div v-if="failedR02 !== null && failedR02.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedR02.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedR02" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successR02 !== null && successR02.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successR02.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successR02" :key="index">
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
                            <div class="border-b border-gray-200 pb-4" v-if="failedR02.length == 0 && successR02.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedR02.length != 0 || successR02.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalR02Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedR02.length, totalR02Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedR02.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successR02.length, totalR02Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successR02.length }} </span>
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
            <!-- R03 -->
            <input type="radio" name="accordion" id="r03" :value="'box-r03'" v-model="selectedSection" />
            <section class="box" id="box-r03">
                <label class="box-title" for="r03">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successR03.length > failedR03.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedR03.length > successR03.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedR03.length == 0 && successR03.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Foreground and background colors have sufficient contrast</h3>
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
                                Elements that have texts should meet a minimum contrast ratio of 4.5:1 between their foreground (usually text color) and background color.
                            </p>

                            <div v-if="failedR03 !== null && failedR03.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedR03.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedR03" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successR03 !== null && successR03.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successR03.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successR03" :key="index">
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
                            <div class="border-b border-gray-200 pb-4" v-if="failedR03.length == 0 && successR03.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedR03.length != 0 || successR03.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalR03Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedR03.length, totalR03Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedR03.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successR03.length, totalR03Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successR03.length }} </span>
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
            <input type="radio" name="accordion" id="acc-close" />
        </div>
    </div>
</template>