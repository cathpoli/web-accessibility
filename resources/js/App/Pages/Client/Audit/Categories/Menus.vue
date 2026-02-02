<script setup>
import { ref, watch } from 'vue'

const selectedSection = ref(null);
const props = defineProps({
    dom: Object,
});

const doc = props.dom;
const failedM01 = [];
const successM01 = [];
const failedM02 = [];
const successM02 = [];
const failedM03 = [];
const successM03 = [];

const nav = doc.querySelector('nav');
const liElements = nav.querySelectorAll('li');

// Menus should be tagged for assistive technology - M01
const navElements = doc.querySelectorAll('nav');
navElements.forEach((navElement, index) => {
    if (navElement) {
        successM01.push(navElement);
    } else {
        failedM01.push(navElement);
    }
});
var totalM01Elements = failedM01.length + successM01.length;

// Menus should be tagged for assistive technology - M02
liElements.forEach((li) => {
    const ulElement = li.querySelector('ul');
    if (ulElement) {
        const ariaHaspopUp = li.getAttribute('aria-haspopup');
        if (ariaHaspopUp && ariaHaspopUp.toLowerCase() === 'true') {
            successM02.push(li);
        } else {
            failedM02.push(li);
        }
    }
});
var totalM02Elements = failedM02.length + successM02.length;

// Dropdown expanded/collapsed state should be represented in the code - M03
liElements.forEach((li) => {
    const ulElement = li.querySelector('ul');
    if (ulElement) {
        const btnElement = li.querySelector('button');
        if (btnElement) {
            const ariaExpanded = btnElement.getAttribute('aria-expanded');
            if (ariaExpanded) {
                successM03.push(li);
            } else {
                failedM03.push(li);
            }
        }
    }
});
var totalM03Elements = failedM03.length + successM03.length;

// Score computation
var totalElements = totalM01Elements + totalM02Elements + totalM03Elements;
var totalFailedElements = failedM01.length + failedM02.length + failedM03.length;
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
            <h2 class="flex text-3xl font-semibold text-gray-90">Menus</h2>
            <span class="base-badge non_compliant-badge" v-if="Number(score.replace(/%/g, '')) < 60 && totalFailedElements !== 0 && totalElements !== 0"> Score: {{ score.replace(/%/g, '') }} </span>
            <span class="base-badge semi_compliant-badge" v-if="Number(score.replace(/%/g, '')) < 80 && Number(score.replace(/%/g, '')) >= 60"> Score: {{ score.replace(/%/g, '') }} </span>
            <span class="base-badge compliant-badge" v-if="Number(score.replace(/%/g, '')) >= 80"> Score: {{ score.replace(/%/g, '') }} </span>
            <span class="base-badge neutral-badge" v-if="totalFailedElements === 0 && totalElements === 0"> Neutral </span>
        </label>
        <div class="accordion arrows">
            <!-- M01 -->
            <input type="radio" name="accordion" id="m01" :value="'box-m01'" v-model="selectedSection" />
            <section class="box" id="box-m01">
                <label class="box-title" for="m01">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successM01.length > failedM01.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedM01.length > successM01.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedM01.length == 0 && successM01.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Menus should be tagged for assistive technology</h3>
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
                                Menus should either be built using the HTML5 "nav" element or include a "role" attribute that equals to "menu" or "navigation" to indicate a navigation landmark for screen-readers.
                            </p>

                            <div v-if="failedM01 !== null && failedM01.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedM01.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedM01" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successM01 !== null && successM01.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successM01.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successM01" :key="index">
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
                            <div class="border-b border-gray-200 pb-4" v-if="failedM01.length == 0 && successM01.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedM01.length != 0 || successM01.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalM01Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedM01.length, totalM01Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedM01.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successM01.length, totalM01Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successM01.length }} </span>
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

            <!-- M02 -->
            <input type="radio" name="accordion" id="m02" :value="'box-m02'" v-model="selectedSection" />
            <section class="box" id="box-m02">
                <label class="box-title" for="m02">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successM02.length > failedM02.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedM02.length > successM02.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedM02.length == 0 && successM02.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Menu dropdowns should be tagged for assistive technology</h3>
                    </div>
                    <div class="affected-icons hidden lg:flex">
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
                    </div>
                </label>
                <label class="box-close" for="acc-close"></label>
                <div class="box-content">
                    <div class="flex flex-col-reverse lg:flex-row">
                        <div class="w-full lg:w-3/4 bg-white rounded border border-gray-200 mt-4 lg:mt-0 p-5">
                            <h3 class="font-bold">Requirement:</h3>
                            <p>
                                Menu items that have a dropdown menu include an "aria-haspopup" attribute that equals to "true".
                            </p>

                            <div v-if="failedM02 !== null && failedM02.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedM02.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedM02" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successM02 !== null && successM02.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successM02.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successM02" :key="index">
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
                            <div class="border-b border-gray-200 pb-4" v-if="failedM02.length == 0 && successM02.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedM02.length != 0 || successM02.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalM02Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedM02.length, totalM02Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedM02.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successM02.length, totalM02Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successM02.length }} </span>
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

            <!-- M03 -->
            <input type="radio" name="accordion" id="m03" :value="'box-m03'" v-model="selectedSection" />
            <section class="box" id="box-m03">
                <label class="box-title" for="m03">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successM03.length > failedM03.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedM03.length > successM03.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedM03.length == 0 && successM03.length == 0"></use>
                        </svg> 
                        <h3 class="ml-2">Dropdown expanded/collapsed state should be represented in the code</h3>
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
                                Menu items that have a dropdown menu include an "aria-expanded" attribute that equals to "false" that changes to "true" and back when opening or closing the dropdown.
                            </p>

                            <div v-if="failedM03 !== null && failedM03.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedM03.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedM03" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successM03 !== null && successM03.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successM03.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successM03" :key="index">
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
                            <div class="border-b border-gray-200 pb-4" v-if="failedM03.length == 0 && successM03.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedM03.length != 0 || successM03.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalM03Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedM03.length, totalM03Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedM03.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successM03.length, totalM03Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successM03.length }} </span>
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