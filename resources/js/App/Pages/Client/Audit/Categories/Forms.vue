<script setup>

const props = defineProps({
    dom: Object,
});
const doc = props.dom;
const failedF01 = [];
const successF01 = [];
const failedF02 = [];
const successF02 = [];
const failedF03 = [];
const successF03 = [];
const failedF04 = [];
const successF04 = [];
const failedF05 = [];
const successF05 = [];
const failedF06 = [];
const successF06 = [];
const failedF07 = [];
const successF07 = [];

const formFields = doc.querySelectorAll('input, textarea, select');
const forms = doc.getElementsByTagName('form');

// Form fields should be properly labeled - F01
formFields.forEach(field => {
    if (field.id) {
        const associatedLabel = doc.querySelector(`label[for="${field.id}"]`);
        if (associatedLabel) {
            successF01.push(field);
        } else {
            if (field.hasAttribute('aria-label') && field.hasAttribute('aria-labelledby') && field.hasAttribute('aria-describedby')) {
                successF01.push(field);
            } else {
                failedF01.push(field);
            }
        }
    }
});
var totalF01Elements = failedF01.length + successF01.length;

// Form fields should not have duplicate ID selectors - F02
const idSet = new Set();
for (let i = 0; i < forms.length; i++) {
    const form = forms[i];
    const formId = form.id;
    if (formId) {
        if (idSet.has(formId)) {
            failedF02.push(form);
        } else {
            successF02.push(form);
        }
    }
}
var totalF02Elements = failedF02.length + successF02.length;

// Required form fields should be tagged for assistive technology - F03
const requiredFields = document.querySelectorAll('input[required], select[required], textarea[required]');
requiredFields.forEach(field => {
    const ariaRequired = field.getAttribute('aria-required');

    if (ariaRequired == 'true') {
        successF03.push(field);
    } else {
        failedF03.push(field);
    }
});
var totalF03Elements = failedF03.length + successF03.length;

// Validation status of form fields should be represented in the code - F04
formFields.forEach(field => {
    if (field.hasAttribute('aria-invalid')) {
        successF04.push(field);
    } else {
        failedF04.push(field);
    }
});
var totalF04Elements = failedF04.length + successF04.length;

// Incorrect use of aria-describedby/labeledby - F05
const formFieldsWithAriaLabel = document.querySelectorAll('input[aria-label], textarea[aria-label], select[aria-label]');
formFieldsWithAriaLabel.forEach(field => {
    if (field.hasAttribute('aria-describedby') || field.hasAttribute('aria-labelledby')) {
        successF05.push(field);
    } else {
        failedF05.push(field);
    }
});
var totalF05Elements = failedF05.length + successF05.length;

// All forms have associated submission buttons - F06
forms.forEach(form => {
    const submitButton = form.querySelector('button[type="submit"], input[type="submit"]');
    if (!submitButton) {
        failedF06.push(form);
    } else {
        successF06.push(form);
    }
});
var totalF06Elements = failedF06.length + successF06.length;

// Search forms should be tagged for assistive technology - F07
if (forms) {
    forms.forEach(form => {
        const searchInput = form.querySelector('input[type="search"]');
        const submitButton = form.querySelector('button[type="submit"]');
        const roleAttributeValue = form.getAttribute('role');
        if (searchInput && submitButton) {
            if (roleAttributeValue === 'search') {
                successF07.push(form);
            } else {
                failedF07.push(form);
            }
        }
    });
}
var totalF07Elements = failedF07.length + successF07.length;

// Score computation
var totalElements = totalF01Elements + totalF02Elements + totalF03Elements + totalF04Elements + totalF05Elements + totalF06Elements + totalF07Elements;
var totalFailedElements = failedF01.length + failedF02.length + failedF03.length + failedF04.length + failedF05.length + failedF06.length + failedF07.length;
var score = calculatePercentage((totalElements - totalFailedElements), 100);

function calculatePercentage(part, total) {
    var total = (part / total) * 100;
    return Math.round(total)+'%';
}

</script>
<template>
    <div class="bg-white rounded-2xl blue-shadow">
        <label for="acc-close" class="flex justify-between items-center p-5">
            <h2 class="flex text-3xl font-semibold text-gray-90">Forms</h2>
            <span class="base-badge non_compliant-badge" v-if="Number(score.replace(/%/g, '')) < 60"> Score: {{ score.replace(/%/g, '') }} </span>
            <span class="base-badge semi_compliant-badge" v-if="Number(score.replace(/%/g, '')) < 80 && Number(score.replace(/%/g, '')) >= 60"> Score: {{ score.replace(/%/g, '') }} </span>
            <span class="base-badge compliant-badge" v-if="Number(score.replace(/%/g, '')) >= 80"> Score: {{ score.replace(/%/g, '') }} </span>
        </label>
        <div class="accordion arrows">
            <input type="radio" name="accordion" id="f01" />
            <section class="box">
                <label class="box-title" for="f01">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successF01.length > failedF01.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedF01.length > successF01.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedF01.length == 0 && successF01.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Form fields should be properly labeled</h3>
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
                                Form fields should either include an "aria-label" attribute or a connected LABEL element describing the requirement of the field (email, phone, name, etc.).
                            </p>

                            <div v-if="failedF01 !== null && failedF01.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedF01.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedF01" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successF01 !== null && successF01.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successF01.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successF01" :key="index">
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
                            <div class="border-b border-gray-200 pb-4" v-if="failedF01.length == 0 && successF01.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedF01.length != 0 || successF01.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalF01Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedF01.length, totalF01Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedF01.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successF01.length, totalF01Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successF01.length }} </span>
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

            <input type="radio" name="accordion" id="f02" />
            <section class="box">
                <label class="box-title" for="f02">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successF02.length > failedF02.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedF02.length > successF02.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedF02.length == 0 && successF02.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Form fields should not have duplicate ID selectors</h3>
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
                                Form elements cannot have the same ID, or else their corresponding label will provide false information.
                            </p>

                            <div v-if="failedF02 !== null && failedF02.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedF02.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedF02" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successF02 !== null && successF02.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successF02.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successF02" :key="index">
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
                            <div class="border-b border-gray-200 pb-4" v-if="failedF02.length == 0 && successF02.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedF02.length != 0 || successF02.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalF02Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedF02.length, totalF02Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedF02.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successF02.length, totalF02Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successF02.length }} </span>
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

            <input type="radio" name="accordion" id="f03" />
            <section class="box">
                <label class="box-title" for="f03">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successF03.length > failedF03.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedF03.length > successF03.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedF03.length == 0 && successF03.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Required form fields should be tagged for assistive technology</h3>
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
                                Required form fields should include an "aria-required" attribute that equals to "true" so blind users using screen-readers know their validation.
                            </p>

                            <div v-if="failedF03 !== null && failedF03.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedF03.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedF03" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successF03 !== null && successF03.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successF03.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successF03" :key="index">
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
                            <div class="border-b border-gray-200 pb-4" v-if="failedF03.length == 0 && successF03.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedF03.length != 0 || successF03.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalF03Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedF03.length, totalF03Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedF03.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successF03.length, totalF03Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successF03.length }} </span>
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

            <input type="radio" name="accordion" id="f04" />
            <section class="box">
                <label class="box-title" for="f04">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successF04.length > failedF04.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedF04.length > successF04.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedF04.length == 0 && successF04.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Validation status of form fields should be represented in the code</h3>
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
                                The validity status of every form element must be represented in the code at all times using the "aria-invalid" attributes and the "true/false" values, and change dynamically when the status changes.
                            </p>

                            <div v-if="failedF04 !== null && failedF04.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedF04.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedF04" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successF04 !== null && successF04.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successF04.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successF04" :key="index">
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
                            <div class="border-b border-gray-200 pb-4" v-if="failedF04.length == 0 && successF04.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedF04.length != 0 || successF04.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalF04Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedF04.length, totalF04Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedF04.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successF04.length, totalF04Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successF04.length }} </span>
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

            <input type="radio" name="accordion" id="f05" />
            <section class="box">
                <label class="box-title" for="f05">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successF05.length > failedF05.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedF05.length > successF05.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedF05.length == 0 && successF05.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Incorrect use of aria-describedby/labeledby</h3>
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
                                aria-describedby and aria-labeledby attributes should be connected by ID to an element that either has text description or an "aria-label" attribute.
                            </p>

                            <div v-if="failedF05 !== null && failedF05.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedF05.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedF05" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successF05 !== null && successF05.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successF05.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successF05" :key="index">
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
                            <div class="border-b border-gray-200 pb-4" v-if="failedF05.length == 0 && successF05.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedF05.length != 0 || successF05.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalF05Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedF05.length, totalF05Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedF05.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successF05.length, totalF05Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successF05.length }} </span>
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

            <input type="radio" name="accordion" id="f06" />
            <section class="box">
                <label class="box-title" for="f06">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successF06.length > failedF06.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedF06.length > successF06.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedF06.length == 0 && successF06.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">All forms have associated submission buttons</h3>
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
                                All forms should have a submission button that is built as an input type "submit" or a "button" element, or include a "role" tag that equals to "button". Buttons can be hidden if the form can be submitted using the Enter key.
                            </p>

                            <div v-if="failedF06 !== null && failedF06.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedF06.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedF06" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successF06 !== null && successF06.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successF06.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successF06" :key="index">
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
                            <div class="border-b border-gray-200 pb-4" v-if="failedF06.length == 0 && successF06.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedF06.length != 0 || successF06.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalF06Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedF06.length, totalF06Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedF06.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successF06.length, totalF06Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successF06.length }} </span>
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

            <input type="radio" name="accordion" id="f07" />
            <section class="box">
                <label class="box-title" for="f07">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successF07.length > successF07.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="successF07.length > successF07.length"></use>
                            <use xlink:href="#neutral-circle" v-if="successF07.length == 0 && successF07.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Search forms should be tagged for assistive technology</h3>
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
                                Search forms should include a "role" tag that equals to "search" (or be wrapped by one) to indicate a search landmark for screen-readers.
                            </p>

                            <div v-if="failedF07 !== null && failedF07.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedF07.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedF07" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successF07 !== null && successF07.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successF07.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successF07" :key="index">
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
                            <div class="border-b border-gray-200 pb-4" v-if="failedF07.length == 0 && failedF07.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedF07.length != 0 || failedF07.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalF07Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedF07.length, totalF07Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedF07.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successF07.length, totalF07Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successF07.length }} </span>
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