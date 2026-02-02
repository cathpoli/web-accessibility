<script setup>
import { ref, watch } from 'vue'

const selectedSection = ref(null);
const props = defineProps({
    dom: Object,
});

const doc = props.dom;
const failedTBL01 = [];
const successTBL01 = [];
const failedTBL02 = [];
const successTBL02 = [];
const failedTBL03 = [];
const successTBL03 = [];

const tables = doc.querySelectorAll('table');

// Tables used for building layouts should be tagged as presentation - TBL01
tables.forEach(table => {
    const roleAttributeValue = table.getAttribute('role');
    if (roleAttributeValue && roleAttributeValue.toLowerCase() === 'presentation') {
        successTBL01.push(table);
    } else {
        failedTBL01.push(table);
    }
});
var totalTBL01Elements = failedTBL01.length + successTBL01.length;

// Avoid nesting tables or exclude their role from assistive technology - TBL02
tables.forEach(table => {
    const rows = table.querySelectorAll('tr');
    const roleAttributeValue = rows.getAttribute('role');
    if (roleAttributeValue && roleAttributeValue.toLowerCase() === 'presentation') {
        successTBL02.push(table);
    } else {
        failedTBL02.push(table);
    }
});
var totalTBL02Elements = failedTBL02.length + successTBL02.length;

// Headless tables should have fallback rows for assistive technology - TBL03
tables.forEach(table => {
    const thead = table.querySelector('thead');
    if (thead === null) {
        const td = table.querySelectorAll('td');
        const roleAttributeValue = td.getAttribute('role');

        if (roleAttributeValue && roleAttributeValue.toLowerCase() === 'rowheader') {
            successTBL03.push(table);
        } else {
            failedTBL03.push(table);
        }
    } 
});
var totalTBL03Elements = failedTBL03.length + successTBL03.length;

// Score computation
var totalElements = totalTBL01Elements + totalTBL02Elements + totalTBL03Elements;
var totalFailedElements = failedTBL01.length + failedTBL01.length + failedTBL01.length;
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
            <h2 class="flex text-3xl font-semibold text-gray-90">Tables</h2>
            <span class="base-badge non_compliant-badge" v-if="Number(score.replace(/%/g, '')) < 60 && totalFailedElements !== 0 && totalElements !== 0"> Score: {{ score.replace(/%/g, '') }} </span>
            <span class="base-badge semi_compliant-badge" v-if="Number(score.replace(/%/g, '')) < 80 && Number(score.replace(/%/g, '')) >= 60"> Score: {{ score.replace(/%/g, '') }} </span>
            <span class="base-badge compliant-badge" v-if="Number(score.replace(/%/g, '')) >= 80"> Score: {{ score.replace(/%/g, '') }} </span>
            <span class="base-badge neutral-badge" v-if="totalFailedElements === 0 && totalElements === 0"> Neutral </span>
        </label>
        <div class="accordion arrows">
            <!-- TBL01 -->
            <input type="radio" name="accordion" id="tbl01" :value="'box-tbl01'" v-model="selectedSection" />
            <section class="box" id="box-tbl01">
                <label class="box-title" for="tbl01">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successTBL01.length > failedTBL01.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedTBL01.length > successTBL01.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedTBL01.length == 0 && successTBL01.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Tables used for building layouts should be tagged as presentation</h3>
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
                                Tables that are being used as layout structures should include a "role" attribute that equals to "presentation" to indicate to screen-readers that it is not really a table.
                            </p>

                            <div v-if="failedTBL01 !== null && failedTBL01.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedTBL01.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedTBL01" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successTBL01 !== null && successTBL01.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successTBL01.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successTBL01" :key="index">
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
                            <div class="border-b border-gray-200 pb-4" v-if="failedTBL01.length == 0 && successTBL01.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedTBL01.length != 0 || successTBL01.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalTBL01Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedTBL01.length, totalTBL01Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedTBL01.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successTBL01.length, totalTBL01Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successTBL01.length }} </span>
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
            
            <!-- TBL02 -->
            <input type="radio" name="accordion" id="tbl02" :value="'box-tbl02'" v-model="selectedSection" />
            <section class="box" id="box-tbl02">
                <label class="box-title" for="tbl02">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successTBL02.length > failedTBL02.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedTBL02.length > successTBL02.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedTBL02.length == 0 && successTBL02.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Avoid nesting tables or exclude their role from assistive technology</h3>
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
                                If nested tables are used (a table element within a table cell), set the nested tables "role" attribute to "presentation".
                            </p>

                            <div v-if="failedTBL02 !== null && failedTBL02.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedTBL02.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedTBL02" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successTBL02 !== null && successTBL02.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successTBL02.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successTBL02" :key="index">
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
                            <div class="border-b border-gray-200 pb-4" v-if="failedTBL02.length == 0 && successTBL02.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedTBL02.length != 0 || successTBL02.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalTBL02Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedTBL02.length, totalTBL02Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedTBL02.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successTBL02.length, totalTBL02Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successTBL02.length }} </span>
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

            <!-- TBL03 -->
            <input type="radio" name="accordion" id="tbl03" :value="'box-tbl03'" v-model="selectedSection" />
            <section class="box" id="box-tbl03">
                <label class="box-title" for="tbl03">
                    <div class="flex items-start lg:items-center">
                        <svg width="24" height="24">
                            <use xlink:href="#compliant-circle" v-if="successTBL03.length > failedTBL03.length"></use>
                            <use xlink:href="#non_compliant-circle" v-if="failedTBL03.length > successTBL03.length"></use>
                            <use xlink:href="#neutral-circle" v-if="failedTBL03.length == 0 && successTBL03.length == 0"></use>
                        </svg>
                        <h3 class="ml-2">Headless tables should have fallback rows for assistive technology</h3>
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
                                Tables that are missing a THEAD row with TH elements, should include a "role" attribute that equals to "rowheader" on the TD elements of the TR that represents the visual table headings.
                            </p>

                            <div v-if="failedTBL03 !== null && failedTBL03.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ failedTBL03.length }} Failed Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in failedTBL03" :key="index">
                                            <span class="scan-list__code">
                                                <code>{{ arr.outerHTML.substring(0, 250) }}</code>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div v-if="successTBL03 !== null && successTBL03.length !== 0">
                                <div class="mt-4">
                                    <h3 class="font-bold">{{ successTBL03.length }} Successful Elements</h3>
                                    <ul class="scan-list" id="btnFunc">
                                        <li class="scan-list__item" v-for="(arr, index) in successTBL03" :key="index">
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
                            <div class="border-b border-gray-200 pb-4" v-if="failedTBL03.length == 0 && successTBL03.length == 0">
                                <h3 class="font-bold">Neutral Score:</h3>
                                <p>
                                    These elements aren't present or are irrelevant for the site rendering the requirement non-applicable.
                                </p>
                            </div>

                            <div class="border-b border-gray-200 pb-4"  v-if="failedTBL03.length != 0 || successTBL03.length != 0">
                                <h3 class="font-bold pb-4">Total Elements {{ totalTBL03Elements }}</h3>
                                <div>
                                    <h4>Failures</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="failures">
                                            <div class="failures__fill" :style="'width: ' + calculatePercentage(failedTBL03.length, totalTBL03Elements)"></div>
                                        </div>
                                        <span class="scanned_total_failures"> {{ failedTBL03.length }} </span>
                                    </div>
                                </div>
                                <div>
                                    <h4>Successes</h4>
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="successes">
                                            <div class="successes__fill" :style="'width: ' + calculatePercentage(successTBL03.length, totalTBL03Elements)"></div>
                                        </div>
                                        <span class="scanned_total_successes"> {{ successTBL03.length }} </span>
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