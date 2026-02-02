<script setup>
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/App/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'

import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

import Titles from './Categories/Titles.vue'
import Clickables from './Categories/Clickables.vue'
import Orientation from './Categories/Orientation.vue'
import Menus from './Categories/Menus.vue'
import Forms from './Categories/Forms.vue'
import Graphics from './Categories/Graphics.vue'
import Documents from './Categories/Documents.vue'
import Carousels from './Categories/Carousels.vue'
import General from './Categories/General.vue'
import Tables from './Categories/Tables.vue'
import Readability from './Categories/Readability.vue'

// Use a ref to track the visibility of the loader
const showLoader = ref(true);

var auditModal = ref({
    open: false,
})

const htmlDOM = ref({});
const domainRef = ref({});

const openRunAuditModal = () => {
    // Reset the showLoader value when opening the modal
    auditModal.value.open = true;
    showLoader.value = true;

    var domain = document.getElementById("domain");
    var domainValue = domain.value;

    domainRef.value = {
        title: domainValue
    }

    axios.post(route('crawl'), {
        url: domainValue
    }).then((res) => {

        auditModal.value.open = true;
        showLoader.value = false;

        const parser = new DOMParser();
        const doc = parser.parseFromString(res.data.html, 'text/html');

        htmlDOM.value = {
            doc: doc
        }
    }).catch((error) => {
        
    })
}

const closeRunAuditModal = () => {
    auditModal.value.open = false
}

var downloadModal = ref({
    open: false,
})

const openRunDownloadModal = () => {
    downloadModal.value.open = true
    auditModal.value.open = false
}

const closeRunDownloadModal = () => {
    downloadModal.value.open = false
    auditModal.value.open = true
}
</script>

<template>
    <Head title="Audit" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">My accessibility audits</h2>
        </template>

        <div class="p-5 pt-6 lg:py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-col-reverse md:flex-row justify-between items-center bg-white rounded-2xl blue-shadow pb-0 md:pb-10 p-10">
                    <div class="md:w-1/2 my-8 flex flex-col items-center md:items-start">
                        <h2 class="flex text-3xl font-semibold text-gray-900">Run Your Audit</h2>
                        <p class="mt-1 text-lg text-gray-600 text-center md:text-start">
                            Here you'll find a history of all accessilibility audits that have you have run for your websites. You can download them below.
                        </p>
                        <div class="mt-4">
                            <label for="search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Run Audit</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-globe" viewBox="0 0 16 16" id="IconChangeColor"> 
                                        <path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm7.5-6.923c-.67.204-1.335.82-1.887 1.855A7.97 7.97 0 0 0 5.145 4H7.5V1.077zM4.09 4a9.267 9.267 0 0 1 .64-1.539 6.7 6.7 0 0 1 .597-.933A7.025 7.025 0 0 0 2.255 4H4.09zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a6.958 6.958 0 0 0-.656 2.5h2.49zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5H4.847zM8.5 5v2.5h2.99a12.495 12.495 0 0 0-.337-2.5H8.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5H4.51zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5H8.5zM5.145 12c.138.386.295.744.468 1.068.552 1.035 1.218 1.65 1.887 1.855V12H5.145zm.182 2.472a6.696 6.696 0 0 1-.597-.933A9.268 9.268 0 0 1 4.09 12H2.255a7.024 7.024 0 0 0 3.072 2.472zM3.82 11a13.652 13.652 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5H3.82zm6.853 3.472A7.024 7.024 0 0 0 13.745 12H11.91a9.27 9.27 0 0 1-.64 1.539 6.688 6.688 0 0 1-.597.933zM8.5 12v2.923c.67-.204 1.335-.82 1.887-1.855.173-.324.33-.682.468-1.068H8.5zm3.68-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.65 13.65 0 0 1-.312 2.5zm2.802-3.5a6.959 6.959 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5h2.49zM11.27 2.461c.247.464.462.98.64 1.539h1.835a7.024 7.024 0 0 0-3.072-2.472c.218.284.418.598.597.933zM10.855 4a7.966 7.966 0 0 0-.468-1.068C9.835 1.897 9.17 1.282 8.5 1.077V4h2.355z" id="mainIconPathAttribute" fill="#737373"></path> 
                                    </svg>
                                </div>
                                <input type="search" id="domain" class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="domain.com" required @keyup.enter="openRunAuditModal()">
                            </div>
                            <div class="flex justify-center items-center mt-4 md:justify-start">
                                <button type="button" class="btn teal-btn"  @click="openRunAuditModal">Run Audit</button>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <img class="" src="../../../../../../public/assets/images/audits-header-image.png" alt="Client Account Header Image"> 
                    </div>
                </div>
                <div class="mt-4 lg:mt-8 bg-white rounded-2xl blue-shadow">
                    <header>
                        <div class="flex flex-col lg:flex-row justify-between items-center p-8 pb-3 space-y-4 lg:px-5 lg:pt-10 lg:space-y-0 xl:px-10">
                            <div class="flex flex-col items-center lg:items-start">
                                <h2 class="text-3xl font-semibold text-gray-900">Your Audit History</h2>
                                <p class="mt-1 text-lg text-gray-600 text-center md:text-start">
                                    Search a domain or select from the dropdown menu.
                                </p>
                            </div>
                           
                            <div class="flex space-x-4">
                                <div class="relative">
                                    <svg class="w-6 h-6 absolute left-3 top-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <title>magnify</title>
                                        <path d="M9.5,3A6.5,6.5 0 0,1 16,9.5C16,11.11 15.41,12.59 14.44,13.73L14.71,14H15.5L20.5,19L19,20.5L14,15.5V14.71L13.73,14.44C12.59,15.41 11.11,16 9.5,16A6.5,6.5 0 0,1 3,9.5A6.5,6.5 0 0,1 9.5,3M9.5,5C7,5 5,7 5,9.5C5,12 7,14 9.5,14C12,14 14,12 14,9.5C14,7 12,5 9.5,5Z"  fill="#737373" />
                                    </svg>
                                    <input type="search" id="search" class="p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="domain.com" required>
                                </div>
                                <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-44 float-right p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="">All Audits (2)</option>
                                    <option value="1">www.uv-brite.com</option>
                                    <option value="2">www.mypaww.com</option>
                                </select>
                            </div>
                        </div>

                    </header>
                    <div class="overflow-x-scroll mt-4 lg:mt-8">
                        <table class="table w-full">
                            <thead>
                                <tr class="text-white">
                                    <th class="lg:pl-6 xl:pl-12">No.</th>
                                    <th>Webpage</th>
                                    <th>Finding</th>
                                    <th>App</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="lg:pl-6 xl:pl-12 py-8 font-bold">1</td>
                                    <td>www.uv-brite.com</td>
                                    <td><div class="base-badge semi_compliant-badge">SEMI COMPLIANT</div></td>
                                    <td><div class="font-semibold">uvBrite</div></td>
                                    <td></td>
                                    <td>February 22, 2023</td>
                                    <td class="space-x-1">
                                        <Link href="" class="show-action-button hover:bg-teal-600/10 text-teal-600 capitalize tooltip" data-tip="View Audit">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </Link>
                                        <a :href="route('audit.report.download', 'www.uv-brite.com')" class="download-action-button hover:bg-sky-600/10 text-sky-600 capitalize tooltip" data-tip="Download Audit" target="_blank">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path d="M5,20H19V18H5M19,9H15V3H9V9H5L12,16L19,9Z" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="lg:pl-6 xl:pl-12 py-8 font-bold">2</td>
                                    <td>www.mypaww.com</td>
                                    <td><div class="base-badge compliant-badge">COMPLIANT</div></td>
                                    <td><div class="font-semibold">accessiBe</div></td>
                                    <td></td>
                                    <td>February 22, 2023</td>
                                    <td class="space-x-1">
                                        <Link href="" class="show-action-button hover:bg-teal-600/10 text-teal-600 capitalize tooltip" data-tip="Manage subscription">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </Link>
                                        <a :href="route('audit.report.download', 'www.mypaww.com')" class="download-action-button hover:bg-sky-600/10 text-sky-600 capitalize tooltip" data-tip="Download Audit" target="_blank">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path d="M5,20H19V18H5M19,9H15V3H9V9H5L12,16L19,9Z" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <TransitionRoot as="template" :show="auditModal.open" @close="closeRunAuditModal">
            <Dialog as="div" id="Run-Audit-Modal" class="relative z-30">
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
                </TransitionChild>

                <div class="fixed inset-0 z-30 overflow-y-auto py-5">
                    <div class="flex min-h-full items-end justify-center p-0 text-center sm:items-center md:p-4">
                        <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                            <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white teal-border text-left shadow-xl transition-all w-11/12">
                                <div class="teal-bg p-4">
                                    <DialogTitle as="h3" class="flex justify-between">
                                        <svg width="171" height="35" viewBox="0 0 171 35" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-28 md:w-44">
                                            <path d="M45.4512 28.4461L54.8338 7H58.3188L67.7015 28.4461H63.7697L61.6251 23.2633H51.5276L49.2936 28.4461H45.4512ZM52.868 20.0464H60.1954L56.5317 11.5573L52.868 20.0464Z" fill="white"/>
                                            <path d="M67.5225 20.4925C67.5225 18.9734 67.8799 17.633 68.5948 16.2926C69.3096 15.0416 70.2926 13.9693 71.5436 13.2544C72.7946 12.4502 74.2244 12.0928 75.8328 12.0928C77.2626 12.0928 78.5136 12.3609 79.4965 12.8076C80.4795 13.2544 81.4624 13.9693 82.2667 14.8629L79.9433 17.3649C79.3178 16.7394 78.6923 16.2033 78.0668 15.8458C77.4413 15.4884 76.6371 15.3097 75.8328 15.3097C74.4924 15.3097 73.4201 15.8458 72.5266 16.8288C71.633 17.8117 71.1862 19.0628 71.1862 20.4925C71.1862 21.9222 71.633 23.1733 72.5266 24.1562C73.4201 25.1392 74.5818 25.6753 76.0115 25.6753C76.8158 25.6753 77.5306 25.4966 78.2455 25.1391C78.871 24.7817 79.5859 24.2456 80.2114 23.6201L82.4454 25.854C81.5518 26.837 80.6582 27.5518 79.5859 28.088C78.603 28.6241 77.3519 28.8922 75.8328 28.8922C74.2244 28.8922 72.884 28.5348 71.5436 27.7306C70.2926 27.0157 69.3096 25.9434 68.5948 24.6924C67.8799 23.352 67.5225 22.0116 67.5225 20.4925Z" fill="white"/>
                                            <path d="M84.8584 20.4925C84.8584 18.9734 85.2158 17.5437 85.8413 16.2926C86.4668 15.0416 87.4498 13.9693 88.6115 13.2544C89.8625 12.5396 91.2029 12.0928 92.6326 12.0928C93.6156 12.0928 94.5985 12.2715 95.4027 12.5396C96.207 12.8076 96.9218 13.2544 97.5473 13.7906C98.1729 14.3267 98.6196 14.9523 99.0664 15.6671C99.5132 16.382 99.7813 17.1862 99.96 18.0798C100.139 18.9734 100.228 19.867 100.228 20.7606C100.228 21.2074 100.228 21.5648 100.139 21.7435H88.3434C88.5221 22.9945 89.0583 24.0668 89.8625 24.7817C90.6667 25.4966 91.739 25.854 92.9007 25.854C93.7943 25.854 94.5091 25.6753 95.224 25.4072C95.9389 25.1392 96.5644 24.603 97.2793 23.9775L99.4239 25.854C97.7261 27.9093 95.5814 28.8922 92.8113 28.8922C91.2922 28.8922 89.8625 28.5348 88.6115 27.8199C87.3604 27.105 86.3775 26.1221 85.6626 24.8711C85.3052 23.5307 84.8584 22.101 84.8584 20.4925ZM88.5221 19.3308H96.7431C96.6537 18.0798 96.207 17.0969 95.4921 16.2926C94.7772 15.4884 93.8836 15.131 92.6326 15.131C91.5603 15.131 90.5774 15.4884 89.8625 16.2926C89.237 17.0075 88.7008 18.0798 88.5221 19.3308Z" fill="white"/>
                                            <path d="M100.943 28.4461L110.326 7H113.811L123.194 28.4461H119.173L117.028 23.2633H106.93L104.696 28.4461H100.943ZM108.36 20.0464H115.688L112.024 11.5573L108.36 20.0464Z" fill="white"/>
                                            <path d="M126.768 28.4454V7.17798H134.721C136.329 7.17798 137.937 7.44605 139.278 7.9822C140.708 8.51835 141.869 9.23323 142.852 10.2162C143.835 11.1991 144.639 12.2714 145.175 13.6118C145.712 14.9522 145.98 16.2926 145.98 17.8117C145.98 19.3308 145.712 20.6711 145.175 22.0115C144.639 23.3519 143.835 24.4242 142.852 25.4072C141.869 26.3901 140.708 27.105 139.278 27.6411C137.848 28.1773 136.329 28.4454 134.721 28.4454H126.768ZM130.521 25.0497H134.721C136.954 25.0497 138.742 24.3349 140.082 22.9945C141.422 21.6541 142.137 19.8669 142.137 17.8117C142.137 15.6671 141.422 13.9692 140.082 12.6289C138.742 11.2885 136.954 10.5736 134.721 10.5736H130.521V25.0497Z" fill="white"/>
                                            <path d="M148.75 28.4461L158.133 7H161.618L171 28.4461H167.069L164.924 23.2633H154.826L152.592 28.4461H148.75ZM156.167 20.0464H163.494L159.83 11.5573L156.167 20.0464Z" fill="white"/>
                                            <path d="M13.9744 22.6662C16.6229 22.6662 19.1336 22.6662 21.7734 22.6662C20.4631 20.3317 19.1987 18.0787 17.8742 15.7188C16.5575 18.0642 15.2936 20.3164 13.9744 22.6662ZM7.17692 34.9269C5.81837 34.3945 4.3842 33.9903 3.13457 33.257C0.929466 31.9629 0.0168611 29.8028 0.000140256 27.2526C-0.0105256 25.6205 0.58879 24.1261 1.23113 22.668C3.18917 18.2234 5.67672 14.0786 8.21734 9.97565C10.4541 6.36324 16.5661 5.47327 19.2042 10.2822C20.0248 11.778 20.8776 13.255 21.7134 14.7419C23.0949 17.1992 24.4756 19.6567 25.8541 22.1158C26.0056 22.3859 26.1021 22.6594 26.5265 22.5299C27.6283 22.1936 28.4496 21.2529 28.2639 20.0934C28.0911 19.0153 27.7185 17.926 27.2211 16.9569C26.1178 14.8075 24.8954 12.7218 23.6948 10.6265C22.5036 8.54703 21.2759 6.48736 19.3978 4.95777C17.7885 3.64703 15.9632 3.07584 13.9237 3.45524C12.8929 3.64703 11.884 3.96424 10.8652 4.22473C11.5681 3.32045 12.336 2.45781 13.2255 1.75807C16.5879 -0.887356 20.3365 -0.522526 23.1813 2.68524C23.7115 3.28324 24.2603 3.88982 24.6621 4.57759C27.0253 8.62223 29.3865 12.6692 31.6686 16.7625C33.0243 19.1938 32.601 22.3935 30.7989 24.5125C29.5388 25.9939 27.9097 26.6226 26.0324 26.6258C21.5264 26.633 17.0199 26.6359 12.5139 26.6115C11.9461 26.6083 11.7315 26.798 11.6872 27.363C11.5897 28.6074 12.0578 29.5288 13.1999 29.9655C13.8424 30.2109 14.546 30.3886 15.2272 30.4073C17.4551 30.4682 19.6866 30.4846 21.9148 30.4365C23.8615 30.3943 25.8242 30.3784 27.7476 30.1091C31.6646 29.5608 33.8406 27.4129 34.6797 23.533C34.7673 23.1276 34.8302 22.7162 34.9305 22.165C35.0815 22.4263 35.193 22.5496 35.2289 22.6933C35.461 23.6137 35.7384 24.5284 35.8866 25.4639C36.5349 29.5582 34.3605 32.9455 30.4538 33.9071C29.4427 34.1558 28.3959 34.3614 27.3615 34.3828C23.0217 34.4725 18.6798 34.4608 14.3395 34.5326C9.85948 34.6065 5.58884 29.3997 8.67409 24.2445C10.971 20.4064 13.0733 16.4458 15.288 12.5552C15.5733 12.0541 15.4821 11.8053 15.0326 11.5412C14.1609 11.029 13.283 10.9882 12.4822 11.6062C11.953 12.0148 11.4402 12.5089 11.0806 13.0731C10.281 14.3279 9.55075 15.6326 8.84939 16.9491C7.51384 19.4561 6.04152 21.9074 4.94732 24.5237C3.28202 28.5056 4.16992 31.6343 7.39039 34.4187C7.5581 34.5636 7.71781 34.7184 8.02429 35C7.61318 34.9688 7.37412 35.0042 7.17692 34.9269Z" fill="url(#paint0_linear_475_410)"/>
                                            <defs>
                                            <linearGradient id="paint0_linear_475_410" x1="0" y1="35" x2="36.8269" y2="0.892855" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#03C7B4"/>
                                            <stop offset="1" stop-color="#2088E8"/>
                                            </linearGradient>
                                            </defs>
                                        </svg>
                                        <h1 class="font-bold text-white mt-1 text-lg md:text-2xl">
                                            {{ domainRef.title }}
                                        </h1>
                                        <button class="ml-5 md:ml-24" type="button" @click="closeRunAuditModal" ref="cancelButtonRef">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"><path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" fill="white" /></svg>
                                        </button>
                                    </DialogTitle>
                                </div>
                                <div class="loader" v-if="showLoader">
                                    <div class="scan">
                                        <div class="logo">
                                        </div>
                                        <h3>Scanning your website...</h3>
                                    </div>
                                </div>

                                <div class="bg-white max-w-7xl mx-auto space-y-5 md:px-24 lg:p-10" v-if="!showLoader">
                                    <div class="flex flex-col-reverse lg:flex-row justify-center items-center bg-white rounded-2xl blue-shadow p-5 lg:space-x-5">
                                        <div class="">
                                            <img class="blue-shadow" src="../../../../../../public/assets/images/audits-website-image-placeholder.png" alt="Client Account Header Image"> 
                                        </div>
                                        <div class="flex flex-col items-start space-y-4">
                                            <div class="base-badge semi_compliant-badge">SEMI COMPLIANT</div>
                                            <div>
                                                <h2 class="flex text-3xl font-semibold text-gray-900">You’re almost there but still not compliant.</h2>
                                                <p class="text-lg text-start">
                                                    Become accessible, do the right thing, and mitigate legal risks.
                                                </p>
                                            </div>
                                            <div class="pt-0 pb-4 xl:pt-10 flex items-center space-x-2">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="12" cy="12" r="12" fill="#E5E4E5"/>
                                                    <circle cx="12" cy="12" r="6" fill="#E5E4E5" stroke="#9F9F9F" stroke-width="2"/>
                                                </svg>
                                                <p class="text-md text-gray-600 text-center md:text-start">
                                                    No accessibility system detected.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col justify-between lg:flex-row lg:space-x-5">
                                        <div class="xl:flex xl:w-1/2 p-5 bg-white blue-shadow rounded-2xl">
                                            <div class="">
                                                <h2 class="flex font-semibold text-gray-900">Fix your website.</h2>
                                                <p class="">
                                                    Achieve accessibility and compliance within 2 minutes of installation!
                                                </p>
                                            </div>
                                            <a href="/" class="btn teal-btn p-2 mt-4">
                                                <span class="ml-2 whitespace-nowrap">Become Compliant</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" viewBox="0 0 24 24">
                                                    <path d="M10,17L15,12L10,7V17Z" />
                                                </svg>
                                            </a>
                                        </div>
                                        <div class="xl:w-1/2 mt-5 lg:mt-0 p-5 bg-white blue-shadow rounded-2xl xl:flex">
                                            <div class="">
                                                <h2 class="flex font-semibold text-gray-900">Get the PDF report to your email.</h2>
                                                <p class="text-base">
                                                    Get your PDF report and more info on our remediation solutions.
                                                </p>
                                            </div>
                                            <button @click="openRunDownloadModal" class="btn bg-white teal-border text-teal-500 mt-4">
                                                <span class="ml-2 whitespace-nowrap">Get Free Report</span>
                                                <svg class="icon fill-current transition-colors duration-300 ml-2" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6 20H19V18.2353H6M19 10.2941H15.2857V5H9.71429V10.2941H6L12.5 16.4706L19 10.2941Z" fill="#02C9B2"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Categories -->
                                    
                                    <Clickables :dom="htmlDOM.doc"/>
                                    <Titles :dom="htmlDOM.doc"/>
                                    <Orientation :dom="htmlDOM.doc"/>
                                    <Menus :dom="htmlDOM.doc"/>
                                    <Graphics :dom="htmlDOM.doc"/>
                                    <Forms :dom="htmlDOM.doc"/>
                                    <Documents :dom="htmlDOM.doc"/>
                                    <Readability :dom="htmlDOM.doc"/>
                                    <Carousels :dom="htmlDOM.doc"/>
                                    <Tables :dom="htmlDOM.doc"/>
                                    <General :dom="htmlDOM.doc"/>

                                    <div class="flex justify-center items-center text-gray-500 pb-5 lg:p-10 lg:pb-0 lg:pt-3">
                                        Powered by
                                        <svg width="171" height="35" viewBox="0 0 171 35" fill="none" xmlns="http://www.w3.org/2000/svg" class="ml-1 w-28 md:w-44">
                                            <path d="M45.4512 28.4461L54.8338 7H58.3188L67.7015 28.4461H63.7697L61.6251 23.2633H51.5276L49.2936 28.4461H45.4512ZM52.868 20.0464H60.1954L56.5317 11.5573L52.868 20.0464Z" fill="black"/>
                                            <path d="M67.5225 20.4925C67.5225 18.9734 67.8799 17.633 68.5948 16.2926C69.3096 15.0416 70.2926 13.9693 71.5436 13.2544C72.7946 12.4502 74.2244 12.0928 75.8328 12.0928C77.2626 12.0928 78.5136 12.3609 79.4965 12.8076C80.4795 13.2544 81.4624 13.9693 82.2667 14.8629L79.9433 17.3649C79.3178 16.7394 78.6923 16.2033 78.0668 15.8458C77.4413 15.4884 76.6371 15.3097 75.8328 15.3097C74.4924 15.3097 73.4201 15.8458 72.5266 16.8288C71.633 17.8117 71.1862 19.0628 71.1862 20.4925C71.1862 21.9222 71.633 23.1733 72.5266 24.1562C73.4201 25.1392 74.5818 25.6753 76.0115 25.6753C76.8158 25.6753 77.5306 25.4966 78.2455 25.1391C78.871 24.7817 79.5859 24.2456 80.2114 23.6201L82.4454 25.854C81.5518 26.837 80.6582 27.5518 79.5859 28.088C78.603 28.6241 77.3519 28.8922 75.8328 28.8922C74.2244 28.8922 72.884 28.5348 71.5436 27.7306C70.2926 27.0157 69.3096 25.9434 68.5948 24.6924C67.8799 23.352 67.5225 22.0116 67.5225 20.4925Z" fill="black"/>
                                            <path d="M84.8584 20.4925C84.8584 18.9734 85.2158 17.5437 85.8413 16.2926C86.4668 15.0416 87.4498 13.9693 88.6115 13.2544C89.8625 12.5396 91.2029 12.0928 92.6326 12.0928C93.6156 12.0928 94.5985 12.2715 95.4027 12.5396C96.207 12.8076 96.9218 13.2544 97.5473 13.7906C98.1729 14.3267 98.6196 14.9523 99.0664 15.6671C99.5132 16.382 99.7813 17.1862 99.96 18.0798C100.139 18.9734 100.228 19.867 100.228 20.7606C100.228 21.2074 100.228 21.5648 100.139 21.7435H88.3434C88.5221 22.9945 89.0583 24.0668 89.8625 24.7817C90.6667 25.4966 91.739 25.854 92.9007 25.854C93.7943 25.854 94.5091 25.6753 95.224 25.4072C95.9389 25.1392 96.5644 24.603 97.2793 23.9775L99.4239 25.854C97.7261 27.9093 95.5814 28.8922 92.8113 28.8922C91.2922 28.8922 89.8625 28.5348 88.6115 27.8199C87.3604 27.105 86.3775 26.1221 85.6626 24.8711C85.3052 23.5307 84.8584 22.101 84.8584 20.4925ZM88.5221 19.3308H96.7431C96.6537 18.0798 96.207 17.0969 95.4921 16.2926C94.7772 15.4884 93.8836 15.131 92.6326 15.131C91.5603 15.131 90.5774 15.4884 89.8625 16.2926C89.237 17.0075 88.7008 18.0798 88.5221 19.3308Z" fill="black"/>
                                            <path d="M100.943 28.4461L110.326 7H113.811L123.194 28.4461H119.173L117.028 23.2633H106.93L104.696 28.4461H100.943ZM108.36 20.0464H115.688L112.024 11.5573L108.36 20.0464Z" fill="black"/>
                                            <path d="M126.768 28.4454V7.17798H134.721C136.329 7.17798 137.937 7.44605 139.278 7.9822C140.708 8.51835 141.869 9.23323 142.852 10.2162C143.835 11.1991 144.639 12.2714 145.175 13.6118C145.712 14.9522 145.98 16.2926 145.98 17.8117C145.98 19.3308 145.712 20.6711 145.175 22.0115C144.639 23.3519 143.835 24.4242 142.852 25.4072C141.869 26.3901 140.708 27.105 139.278 27.6411C137.848 28.1773 136.329 28.4454 134.721 28.4454H126.768ZM130.521 25.0497H134.721C136.954 25.0497 138.742 24.3349 140.082 22.9945C141.422 21.6541 142.137 19.8669 142.137 17.8117C142.137 15.6671 141.422 13.9692 140.082 12.6289C138.742 11.2885 136.954 10.5736 134.721 10.5736H130.521V25.0497Z" fill="black"/>
                                            <path d="M148.75 28.4461L158.133 7H161.618L171 28.4461H167.069L164.924 23.2633H154.826L152.592 28.4461H148.75ZM156.167 20.0464H163.494L159.83 11.5573L156.167 20.0464Z" fill="black"/>
                                            <path d="M13.9744 22.6662C16.6229 22.6662 19.1336 22.6662 21.7734 22.6662C20.4631 20.3317 19.1987 18.0787 17.8742 15.7188C16.5575 18.0642 15.2936 20.3164 13.9744 22.6662ZM7.17692 34.9269C5.81837 34.3945 4.3842 33.9903 3.13457 33.257C0.929466 31.9629 0.0168611 29.8028 0.000140256 27.2526C-0.0105256 25.6205 0.58879 24.1261 1.23113 22.668C3.18917 18.2234 5.67672 14.0786 8.21734 9.97565C10.4541 6.36324 16.5661 5.47327 19.2042 10.2822C20.0248 11.778 20.8776 13.255 21.7134 14.7419C23.0949 17.1992 24.4756 19.6567 25.8541 22.1158C26.0056 22.3859 26.1021 22.6594 26.5265 22.5299C27.6283 22.1936 28.4496 21.2529 28.2639 20.0934C28.0911 19.0153 27.7185 17.926 27.2211 16.9569C26.1178 14.8075 24.8954 12.7218 23.6948 10.6265C22.5036 8.54703 21.2759 6.48736 19.3978 4.95777C17.7885 3.64703 15.9632 3.07584 13.9237 3.45524C12.8929 3.64703 11.884 3.96424 10.8652 4.22473C11.5681 3.32045 12.336 2.45781 13.2255 1.75807C16.5879 -0.887356 20.3365 -0.522526 23.1813 2.68524C23.7115 3.28324 24.2603 3.88982 24.6621 4.57759C27.0253 8.62223 29.3865 12.6692 31.6686 16.7625C33.0243 19.1938 32.601 22.3935 30.7989 24.5125C29.5388 25.9939 27.9097 26.6226 26.0324 26.6258C21.5264 26.633 17.0199 26.6359 12.5139 26.6115C11.9461 26.6083 11.7315 26.798 11.6872 27.363C11.5897 28.6074 12.0578 29.5288 13.1999 29.9655C13.8424 30.2109 14.546 30.3886 15.2272 30.4073C17.4551 30.4682 19.6866 30.4846 21.9148 30.4365C23.8615 30.3943 25.8242 30.3784 27.7476 30.1091C31.6646 29.5608 33.8406 27.4129 34.6797 23.533C34.7673 23.1276 34.8302 22.7162 34.9305 22.165C35.0815 22.4263 35.193 22.5496 35.2289 22.6933C35.461 23.6137 35.7384 24.5284 35.8866 25.4639C36.5349 29.5582 34.3605 32.9455 30.4538 33.9071C29.4427 34.1558 28.3959 34.3614 27.3615 34.3828C23.0217 34.4725 18.6798 34.4608 14.3395 34.5326C9.85948 34.6065 5.58884 29.3997 8.67409 24.2445C10.971 20.4064 13.0733 16.4458 15.288 12.5552C15.5733 12.0541 15.4821 11.8053 15.0326 11.5412C14.1609 11.029 13.283 10.9882 12.4822 11.6062C11.953 12.0148 11.4402 12.5089 11.0806 13.0731C10.281 14.3279 9.55075 15.6326 8.84939 16.9491C7.51384 19.4561 6.04152 21.9074 4.94732 24.5237C3.28202 28.5056 4.16992 31.6343 7.39039 34.4187C7.5581 34.5636 7.71781 34.7184 8.02429 35C7.61318 34.9688 7.37412 35.0042 7.17692 34.9269Z" fill="url(#paint0_linear_475_410)"/>
                                            <defs>
                                            <linearGradient id="paint0_linear_475_410" x1="0" y1="35" x2="36.8269" y2="0.892855" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#03C7B4"/>
                                            <stop offset="1" stop-color="#2088E8"/>
                                            </linearGradient>
                                            </defs>
                                        </svg>
                                    </div>
                                </div>

                                <!-- Compliance SVGs -->

                                <!-- Neutral -->
                                <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                                    <symbol id="neutral-circle" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="12" fill="#E5E4E5"/>
                                        <circle cx="12" cy="12" r="6" fill="#E5E4E5" stroke="#9F9F9F" stroke-width="2"/>
                                    </symbol>
                                </svg>
                                <!-- Compliant -->
                                <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                                    <symbol id="compliant-circle" viewBox="0 0 24 24">
                                        <g clip-path="url(#clip0_471_236)">
                                        <path d="M12 0C5.4 0 0 5.4 0 12C0 18.6 5.4 24 12 24C18.6 24 24 18.6 24 12C24 5.4 18.6 0 12 0ZM12 21.6C6.708 21.6 2.4 17.292 2.4 12C2.4 6.708 6.708 2.4 12 2.4C17.292 2.4 21.6 6.708 21.6 12C21.6 17.292 17.292 21.6 12 21.6ZM17.508 6.696L9.6 14.604L6.492 11.508L4.8 13.2L9.6 18L19.2 8.4L17.508 6.696Z" fill="#1AD2C7"/>
                                        </g>
                                        <defs>
                                        <clipPath id="clip0_471_236">
                                        <rect width="24" height="24" fill="white"/>
                                        </clipPath>
                                        </defs>
                                    </symbol>
                                </svg>
                                <!-- Semi-Compliant -->
                                <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                                    <symbol id="semi_compliant-circle" viewBox="0 0 24 24">
                                        <path d="M12 22.6C9.45392 22.6 7.01212 21.5886 5.21178 19.7882C3.41143 17.9879 2.4 15.5461 2.4 13C2.4 10.4539 3.41143 8.01212 5.21178 6.21178C7.01212 4.41143 9.45392 3.4 12 3.4C14.5461 3.4 16.9879 4.41143 18.7882 6.21178C20.5886 8.01212 21.6 10.4539 21.6 13C21.6 15.5461 20.5886 17.9879 18.7882 19.7882C16.9879 21.5886 14.5461 22.6 12 22.6ZM12 1C10.4241 1 8.86371 1.31039 7.4078 1.91345C5.95189 2.5165 4.62902 3.40042 3.51472 4.51472C1.26428 6.76516 0 9.8174 0 13C0 16.1826 1.26428 19.2348 3.51472 21.4853C4.62902 22.5996 5.95189 23.4835 7.4078 24.0866C8.86371 24.6896 10.4241 25 12 25C15.1826 25 18.2348 23.7357 20.4853 21.4853C22.7357 19.2348 24 16.1826 24 13C24 11.4241 23.6896 9.86371 23.0866 8.4078C22.4835 6.95189 21.5996 5.62902 20.4853 4.51472C19.371 3.40042 18.0481 2.5165 16.5922 1.91345C15.1363 1.31039 13.5759 1 12 1Z" fill="#EA580C"/>
                                        <path d="M12 7L6 18H18M12 9.31579L16.1073 16.8421H7.89273" fill="#EF8249"/>
                                    </symbol>
                                </svg>
                                <!-- Non-Compliant -->
                                <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                                    <symbol id="non_compliant-circle" viewBox="0 0 24 24">
                                        <g clip-path="url(#clip0_471_235)">
                                        <path d="M12 21.6C6.708 21.6 2.4 17.292 2.4 12C2.4 6.708 6.708 2.4 12 2.4C17.292 2.4 21.6 6.708 21.6 12C21.6 17.292 17.292 21.6 12 21.6ZM12 0C5.364 0 0 5.364 0 12C0 18.636 5.364 24 12 24C18.636 24 24 18.636 24 12C24 5.364 18.636 0 12 0ZM15.108 7.2L12 10.308L8.892 7.2L7.2 8.892L10.308 12L7.2 15.108L8.892 16.8L12 13.692L15.108 16.8L16.8 15.108L13.692 12L16.8 8.892L15.108 7.2Z" fill="#E11D48"/>
                                        </g>
                                        <defs>
                                        <clipPath id="clip0_471_235">
                                        <rect width="24" height="24" fill="white"/>
                                        </clipPath>
                                        </defs>
                                    </symbol>
                                </svg>

                                <!-- Affected SVGs -->

                                <!-- Motor Impaired -->
                                <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                                    <symbol id="motor_impaired-icon" viewBox="0 0 24 24">
                                        <title>Motor Impaired</title>
                                        <path d="M 11.5 0 C 10.119288 0 9 1.1192881 9 2.5 C 9 3.8807119 10.119288 5 11.5 5 C 12.880712 5 14 3.8807119 14 2.5 C 14 1.1192881 12.880712 0 11.5 0 z M 10.40625 6 C 9.10625 6 8 7.1125 8 8.3125 L 8 14.1875 C 8 15.6875 9.3125 17 10.8125 17 L 12.3125 17 C 14.811103 17 16.239655 16.819561 16.75 16.75 L 19.09375 21.4375 A 1.0063276 1.0063276 0 0 0 20.90625 20.5625 L 17.90625 14.5625 A 1.0001 1.0001 0 0 0 17 14 L 13 13.3125 L 13 8.3125 C 13 7.1125 11.8875 6 10.6875 6 L 10.40625 6 z M 7 10.3125 C 4.1 11.2125 2 13.8 2 17 C 2 20.9 5.1 24 9 24 C 12.5 24 15.40625 21.4 15.90625 18 L 13.90625 18 C 13.40625 20.3 11.4 22 9 22 C 6.2 22 4 19.8 4 17 C 4 15 5.2 13.20625 7 12.40625 L 7 10.3125 z"/>
                                    </symbol>
                                </svg>

                                <!-- Vision Impaired -->
                                <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                                    <symbol id="vision_impaired-icon" viewBox="0 0 48 48">
                                        <title>Vision Impaired</title>
                                        <g id="Layer_2" data-name="Layer 2">
                                            <g id="invisible_box" data-name="invisible box">
                                                <rect width="48" height="48" fill="none"/>
                                            </g>
                                            <g id="Q3_icons" data-name="Q3 icons">
                                                <g>
                                                <path d="M45.3,22.1C43.2,19.5,35.4,11,24,11a23,23,0,0,0-8.5,1.6L9.7,6.9A2,2,0,0,0,6.9,9.7L18.6,21.4A5.9,5.9,0,0,1,24,18a6,6,0,0,1,2.6,11.4L38.3,41.1a2,2,0,1,0,2.8-2.8l-4.8-4.8a31.4,31.4,0,0,0,9-7.6A3,3,0,0,0,45.3,22.1Z"/>
                                                <path d="M10.4,17.6a2,2,0,0,0-2.8,2.8l15,15a2,2,0,0,0,2.8-2.8Z"/>
                                                <path d="M5.4,22.6a1.9,1.9,0,0,0-2.8,0,1.9,1.9,0,0,0,0,2.8l7,7a2,2,0,0,0,2.8-2.8Z"/>
                                                </g>
                                            </g>
                                        </g>
                                    </symbol>
                                </svg>

                                <!-- Cognitive Disability -->
                                <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                                    <symbol id="cognitive_disability-icon" viewBox="0 0 17 17">
                                        <title>Cognitive Disability</title>
                                        <g clip-path="url(#clip0_475_477)">
                                        <path d="M15.7955 8.88969C15.197 8.0764 14.6528 7.22455 14.1664 6.33969C13.6351 2.55011 11.4747 0.637608 7.72052 0.708441C5.59552 0.743858 3.82469 1.45219 2.65594 2.76261C1.48719 4.07302 0.955939 5.77302 1.09761 7.72094C1.23927 9.66886 1.66427 10.3772 2.58511 11.5459C3.50594 12.7147 3.61219 12.9272 3.22261 14.3793C3.19604 14.4677 3.18794 14.5606 3.19881 14.6522C3.20968 14.7439 3.23929 14.8323 3.28579 14.912C3.33229 14.9918 3.3947 15.0611 3.46913 15.1156C3.54356 15.1702 3.62842 15.2089 3.71844 15.2293C3.80913 15.2561 3.90428 15.2646 3.99828 15.2541C4.09228 15.2436 4.18322 15.2144 4.26575 15.1681C4.34827 15.1219 4.42071 15.0597 4.47878 14.985C4.53685 14.9103 4.57938 14.8248 4.60386 14.7334C5.09969 12.7501 4.81635 12.148 3.68302 10.6959C2.94848 9.83513 2.5354 8.74611 2.51427 7.61469C2.42257 6.9196 2.47933 6.21296 2.68079 5.54142C2.88225 4.86987 3.22385 4.24869 3.68302 3.71886C4.60385 2.72719 6.02052 2.16052 7.75594 2.12511C9.49135 2.08969 12.2184 2.55011 12.7851 6.55219C12.8205 6.97719 13.458 7.96886 14.0955 8.96052L13.3164 9.20844C13.1796 9.25951 13.062 9.35156 12.9796 9.47201C12.8972 9.59246 12.854 9.73542 12.8559 9.88136V10.9084C12.8559 11.9001 12.8914 12.6793 12.6434 12.9272C12.3955 13.1751 12.0059 13.2105 11.2268 13.2105C11.0441 13.2056 10.8623 13.238 10.6925 13.3057C10.5227 13.3734 10.3685 13.475 10.2392 13.6042C10.11 13.7335 10.0084 13.8877 9.94073 14.0575C9.87304 14.2273 9.84065 14.4091 9.84552 14.5918V15.5834C9.84552 15.7713 9.92015 15.9515 10.053 16.0843C10.1858 16.2171 10.366 16.2918 10.5539 16.2918C10.7417 16.2918 10.9219 16.2171 11.0547 16.0843C11.1876 15.9515 11.2622 15.7713 11.2622 15.5834V14.6272C12.1122 14.6272 13.0684 14.5209 13.6705 13.9189C14.2726 13.3168 14.308 12.3251 14.2726 10.873V10.3772L15.4414 9.98761C15.548 9.95081 15.6449 9.89023 15.7246 9.81046C15.8044 9.73069 15.865 9.63383 15.9018 9.52719C15.9359 9.42012 15.9441 9.30647 15.9256 9.19562C15.9071 9.08477 15.8625 8.9799 15.7955 8.88969Z"/>
                                        <path d="M8.74806 5.38356L8.60639 5.02939L9.03139 4.35648C8.85573 4.11008 8.64029 3.89464 8.39389 3.71898L7.72098 4.14398L7.36681 4.00231L7.18973 3.22314H6.26889L6.09181 4.00231L5.73764 4.14398L5.06473 3.71898C4.81833 3.89464 4.60289 4.11008 4.42723 4.35648L4.85223 5.02939L4.71056 5.38356L3.93139 5.56064C3.90202 5.71226 3.89013 5.86674 3.89598 6.02106C3.89013 6.17538 3.90202 6.32986 3.93139 6.48148L4.71056 6.65856L4.85223 7.01273L4.42723 7.68564C4.60289 7.93204 4.81833 8.14748 5.06473 8.32314L5.73764 7.89814L6.09181 8.03981L6.26889 8.81898H7.18973L7.36681 8.03981L7.72098 7.89814L8.39389 8.32314C8.64029 8.14748 8.85573 7.93204 9.03139 7.68564L8.60639 7.01273L8.74806 6.65856L9.52723 6.48148C9.5566 6.32986 9.56849 6.17538 9.56264 6.02106C9.56849 5.86674 9.5566 5.71226 9.52723 5.56064L8.74806 5.38356ZM6.72931 6.72939C6.58922 6.72939 6.45227 6.68785 6.33578 6.61002C6.2193 6.53219 6.12851 6.42156 6.0749 6.29213C6.02128 6.1627 6.00726 6.02028 6.03459 5.88287C6.06192 5.74547 6.12938 5.61926 6.22844 5.52019C6.32751 5.42113 6.45372 5.35367 6.59112 5.32634C6.72852 5.29901 6.87095 5.31303 7.00038 5.36665C7.12981 5.42026 7.24044 5.51105 7.31827 5.62753C7.3961 5.74402 7.43764 5.88097 7.43764 6.02106C7.43764 6.20892 7.36302 6.38909 7.23018 6.52193C7.09734 6.65477 6.91717 6.72939 6.72931 6.72939Z"/>
                                        </g>
                                        <defs>
                                        <clipPath id="clip0_475_477">
                                        <rect width="17" height="17"/>
                                        </clipPath>
                                        </defs>
                                    </symbol>
                                </svg>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>

        <TransitionRoot as="template" :show="downloadModal.open" @close="closeRunDownloadModal">
            <Dialog as="div" class="relative z-30">
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
                </TransitionChild>
        
                <div class="fixed inset-0 overflow-y-auto py-5">
                    <div class="flex min-h-full items-end justify-center p-0 text-center sm:items-center md:p-4">
                        <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                            <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white teal-border text-left shadow-xl transition-all w-fit">
                                <div class="teal-bg p-4">
                                    <DialogTitle as="h3" class="flex justify-end">
                                        
                                        <button class="ml-5 md:ml-24" type="button" @click="closeRunDownloadModal" ref="cancelButtonRef">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"><path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" fill="white" /></svg>
                                        </button>
                                    </DialogTitle>
                                    
                                </div>
                                <div class="bg-white p-10">
                                    <svg width="171" height="35" viewBox="0 0 171 35" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-28 md:w-44">
                                        <path d="M45.4512 28.4461L54.8338 7H58.3188L67.7015 28.4461H63.7697L61.6251 23.2633H51.5276L49.2936 28.4461H45.4512ZM52.868 20.0464H60.1954L56.5317 11.5573L52.868 20.0464Z" fill="black"/>
                                        <path d="M67.5225 20.4925C67.5225 18.9734 67.8799 17.633 68.5948 16.2926C69.3096 15.0416 70.2926 13.9693 71.5436 13.2544C72.7946 12.4502 74.2244 12.0928 75.8328 12.0928C77.2626 12.0928 78.5136 12.3609 79.4965 12.8076C80.4795 13.2544 81.4624 13.9693 82.2667 14.8629L79.9433 17.3649C79.3178 16.7394 78.6923 16.2033 78.0668 15.8458C77.4413 15.4884 76.6371 15.3097 75.8328 15.3097C74.4924 15.3097 73.4201 15.8458 72.5266 16.8288C71.633 17.8117 71.1862 19.0628 71.1862 20.4925C71.1862 21.9222 71.633 23.1733 72.5266 24.1562C73.4201 25.1392 74.5818 25.6753 76.0115 25.6753C76.8158 25.6753 77.5306 25.4966 78.2455 25.1391C78.871 24.7817 79.5859 24.2456 80.2114 23.6201L82.4454 25.854C81.5518 26.837 80.6582 27.5518 79.5859 28.088C78.603 28.6241 77.3519 28.8922 75.8328 28.8922C74.2244 28.8922 72.884 28.5348 71.5436 27.7306C70.2926 27.0157 69.3096 25.9434 68.5948 24.6924C67.8799 23.352 67.5225 22.0116 67.5225 20.4925Z" fill="black"/>
                                        <path d="M84.8584 20.4925C84.8584 18.9734 85.2158 17.5437 85.8413 16.2926C86.4668 15.0416 87.4498 13.9693 88.6115 13.2544C89.8625 12.5396 91.2029 12.0928 92.6326 12.0928C93.6156 12.0928 94.5985 12.2715 95.4027 12.5396C96.207 12.8076 96.9218 13.2544 97.5473 13.7906C98.1729 14.3267 98.6196 14.9523 99.0664 15.6671C99.5132 16.382 99.7813 17.1862 99.96 18.0798C100.139 18.9734 100.228 19.867 100.228 20.7606C100.228 21.2074 100.228 21.5648 100.139 21.7435H88.3434C88.5221 22.9945 89.0583 24.0668 89.8625 24.7817C90.6667 25.4966 91.739 25.854 92.9007 25.854C93.7943 25.854 94.5091 25.6753 95.224 25.4072C95.9389 25.1392 96.5644 24.603 97.2793 23.9775L99.4239 25.854C97.7261 27.9093 95.5814 28.8922 92.8113 28.8922C91.2922 28.8922 89.8625 28.5348 88.6115 27.8199C87.3604 27.105 86.3775 26.1221 85.6626 24.8711C85.3052 23.5307 84.8584 22.101 84.8584 20.4925ZM88.5221 19.3308H96.7431C96.6537 18.0798 96.207 17.0969 95.4921 16.2926C94.7772 15.4884 93.8836 15.131 92.6326 15.131C91.5603 15.131 90.5774 15.4884 89.8625 16.2926C89.237 17.0075 88.7008 18.0798 88.5221 19.3308Z" fill="black"/>
                                        <path d="M100.943 28.4461L110.326 7H113.811L123.194 28.4461H119.173L117.028 23.2633H106.93L104.696 28.4461H100.943ZM108.36 20.0464H115.688L112.024 11.5573L108.36 20.0464Z" fill="black"/>
                                        <path d="M126.768 28.4454V7.17798H134.721C136.329 7.17798 137.937 7.44605 139.278 7.9822C140.708 8.51835 141.869 9.23323 142.852 10.2162C143.835 11.1991 144.639 12.2714 145.175 13.6118C145.712 14.9522 145.98 16.2926 145.98 17.8117C145.98 19.3308 145.712 20.6711 145.175 22.0115C144.639 23.3519 143.835 24.4242 142.852 25.4072C141.869 26.3901 140.708 27.105 139.278 27.6411C137.848 28.1773 136.329 28.4454 134.721 28.4454H126.768ZM130.521 25.0497H134.721C136.954 25.0497 138.742 24.3349 140.082 22.9945C141.422 21.6541 142.137 19.8669 142.137 17.8117C142.137 15.6671 141.422 13.9692 140.082 12.6289C138.742 11.2885 136.954 10.5736 134.721 10.5736H130.521V25.0497Z" fill="black"/>
                                        <path d="M148.75 28.4461L158.133 7H161.618L171 28.4461H167.069L164.924 23.2633H154.826L152.592 28.4461H148.75ZM156.167 20.0464H163.494L159.83 11.5573L156.167 20.0464Z" fill="black"/>
                                        <path d="M13.9744 22.6662C16.6229 22.6662 19.1336 22.6662 21.7734 22.6662C20.4631 20.3317 19.1987 18.0787 17.8742 15.7188C16.5575 18.0642 15.2936 20.3164 13.9744 22.6662ZM7.17692 34.9269C5.81837 34.3945 4.3842 33.9903 3.13457 33.257C0.929466 31.9629 0.0168611 29.8028 0.000140256 27.2526C-0.0105256 25.6205 0.58879 24.1261 1.23113 22.668C3.18917 18.2234 5.67672 14.0786 8.21734 9.97565C10.4541 6.36324 16.5661 5.47327 19.2042 10.2822C20.0248 11.778 20.8776 13.255 21.7134 14.7419C23.0949 17.1992 24.4756 19.6567 25.8541 22.1158C26.0056 22.3859 26.1021 22.6594 26.5265 22.5299C27.6283 22.1936 28.4496 21.2529 28.2639 20.0934C28.0911 19.0153 27.7185 17.926 27.2211 16.9569C26.1178 14.8075 24.8954 12.7218 23.6948 10.6265C22.5036 8.54703 21.2759 6.48736 19.3978 4.95777C17.7885 3.64703 15.9632 3.07584 13.9237 3.45524C12.8929 3.64703 11.884 3.96424 10.8652 4.22473C11.5681 3.32045 12.336 2.45781 13.2255 1.75807C16.5879 -0.887356 20.3365 -0.522526 23.1813 2.68524C23.7115 3.28324 24.2603 3.88982 24.6621 4.57759C27.0253 8.62223 29.3865 12.6692 31.6686 16.7625C33.0243 19.1938 32.601 22.3935 30.7989 24.5125C29.5388 25.9939 27.9097 26.6226 26.0324 26.6258C21.5264 26.633 17.0199 26.6359 12.5139 26.6115C11.9461 26.6083 11.7315 26.798 11.6872 27.363C11.5897 28.6074 12.0578 29.5288 13.1999 29.9655C13.8424 30.2109 14.546 30.3886 15.2272 30.4073C17.4551 30.4682 19.6866 30.4846 21.9148 30.4365C23.8615 30.3943 25.8242 30.3784 27.7476 30.1091C31.6646 29.5608 33.8406 27.4129 34.6797 23.533C34.7673 23.1276 34.8302 22.7162 34.9305 22.165C35.0815 22.4263 35.193 22.5496 35.2289 22.6933C35.461 23.6137 35.7384 24.5284 35.8866 25.4639C36.5349 29.5582 34.3605 32.9455 30.4538 33.9071C29.4427 34.1558 28.3959 34.3614 27.3615 34.3828C23.0217 34.4725 18.6798 34.4608 14.3395 34.5326C9.85948 34.6065 5.58884 29.3997 8.67409 24.2445C10.971 20.4064 13.0733 16.4458 15.288 12.5552C15.5733 12.0541 15.4821 11.8053 15.0326 11.5412C14.1609 11.029 13.283 10.9882 12.4822 11.6062C11.953 12.0148 11.4402 12.5089 11.0806 13.0731C10.281 14.3279 9.55075 15.6326 8.84939 16.9491C7.51384 19.4561 6.04152 21.9074 4.94732 24.5237C3.28202 28.5056 4.16992 31.6343 7.39039 34.4187C7.5581 34.5636 7.71781 34.7184 8.02429 35C7.61318 34.9688 7.37412 35.0042 7.17692 34.9269Z" fill="url(#paint0_linear_475_410)"/>
                                        <defs>
                                        <linearGradient id="paint0_linear_475_410" x1="0" y1="35" x2="36.8269" y2="0.892855" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#03C7B4"/>
                                        <stop offset="1" stop-color="#2088E8"/>
                                        </linearGradient>
                                        </defs>
                                    </svg>
                                    <div class="flex flex-col-reverse md:flex-row items-center">
                                        <div>
                                            <div class="mt-10">
                                                <h2 class="flex text-3xl font-semibold text-gray-900">Get the PDF report to your email</h2>
                                                <p class="text-lg text-start">
                                                    Become accessible, do the right thing, and mitigate legal risks.
                                                </p>
                                            </div>
                                            <form class="mt-6 space-y-6">
                                                <div>
                                                    <InputLabel for="name" class="font-semibold">
                                                        Name <span class="text-red-500">*</span>
                                                    </InputLabel>
                            
                                                    <TextInput
                                                        id="name"
                                                        type="text"
                                                        class="mt-1 teal-border bg-transparent w-full"
                                                        placeholder="Enter your full name"
                                                    />
                            
                                                    <!-- <InputError class="mt-2" :message="form.errors.name" /> -->
                                                </div>
                            
                                                <div class="mt-4">
                                                    <InputLabel for="email" class="font-semibold">
                                                        Email <span class="text-red-500">*</span>
                                                    </InputLabel>
                            
                                                    <TextInput
                                                        id="email"
                                                        type="email"
                                                        class="mt-1 teal-border bg-transparent w-full"
                                                        placeholder="Enter your email"
        
                                                    />
                            
                                                    <!-- <InputError class="mt-2" :message="form.errors.email" /> -->
                                                </div>
                            
                                                <div class="flex justify-start items-center mt-4">
                                                    <button type="button" class="btn teal-btn" @click="closeRunDownloadModal">
                                                        Get Free Report
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="pt-4 md:pt-0">
                                            <img class="relative right-5 md:right-0" src="../../../../../../public/assets/images/audits-header-image.png" alt="Client Account Header Image"> 
                                        </div>
                                    </div>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
            
        </TransitionRoot>
    </AuthenticatedLayout>
</template>
<style>
    .blue-shadow {
        box-shadow: 0 0 60px rgba(226, 236, 249, 0.5);
    }
    .download-action-button {
        cursor: pointer;
        border-radius: 6px;
        border: 1px solid rgb(3 105 161);
        background: white;
        width: fit-content;
        height: 32px;
        padding: 4px 8px;
        justify-content: center;
        align-items: center;
    }
    .show-action-button {
        cursor: pointer;
        border-radius: 6px;
        border: 1px solid teal;
        background: white;
        width: fit-content;
        height: 32px;
        padding: 4px 8px;
        justify-content: center;
        align-items: center;
    }

    .table th:first-child {
        position: sticky;
        left: 0px;
        z-index: 1;
    }

    .table td:first-child {
        position: sticky;
        left: 0;
        z-index: 1; /* Use a lower z-index for td to allow header to be on top */
        background-color: #fff; /* Reset background color */
    }

    tr.text-white th {
        background-color: #3b82f6;
        color: #ffffff;
        font-size: 1.125rem;
        text-transform: capitalize;
        border-radius: 0px;
    }

    /* Finding Badge */

    /* Base class */
    .base-badge {
        border-radius: 8px;
        width: fit-content;
        padding: 4px;
        justify-content: center;
        align-items: center;
        padding: 4px 20px;
    }
    /* teal */
    .compliant-badge {
        border: 1px solid #00B087;
        background: rgba(26, 210, 199, 0.20);
        color: #00B087;
    }

    /* orange */
    .semi_compliant-badge {
        border: 1px solid rgb(234 88 12);
        background: rgba(234, 88, 12, .1);
        color: rgb(234 88 12);
    }

    /* rose */
    .non_compliant-badge {
        border: 1px solid rgb(225 29 72);
        background: rgba(225, 29, 72, .1);
        color: rgb(225 29 72);
    }

    /* black */
    .neutral-badge {
        border: 1px solid #9aa6d3;
        background: #e3e8fb;
        color: #2b3e52;
    }

    #Run-Audit-Modal {
        z-index: 99 !important;
    }

    /*accordion*/
    .accordion {
        margin: auto;
        width: 100%;
    }
    .accordion input {
        display: none;
    }
    .box {
        position: relative;
        height: auto;
        transition: all .15s ease-in-out;
    }
    .box::before {
        content: '';
        position: absolute;
        display: block;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        pointer-events: none;
        border-top: solid 1px rgb(209 213 219);

    }
    .box-title {
        width: calc(100% - 40px);
        height: auto;
        line-height: auto;
        padding: 20px 0px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        -webkit-touch-callout: none;-webkit-user-select: none;-khtml-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;
    }
    .box-content {
        width: calc(100% - 40px);
        padding: 0px 20px 20px;
        font-size: 11pt;
        color: black;
        display: none;
    }
    .box-close {
        position: absolute;
        height: 64px;
        width: 100%;
        top: 0;
        left: 0;
        cursor: pointer;
        display: none;
    }
    input:checked + .box {
        height: auto;
        margin: 16px 0;
        box-shadow: 0 0 2px rgba(0,0,0,.16),0 6px 12px rgba(0,0,0,.1);
        background-color: #F6F9FD;
    }
    /* input:checked + .box .box-title {
    } */
    input:checked + .box .box-content,
    input:checked + .box .box-close {
        display: inline-block;
    }
    .arrows section .box-title {
        padding-left: 1.25rem;
    }
    .arrows section .box-title:before {
        position: absolute;
        display: block;
        content: '\203a';
        font-size: 20pt;
        right: 20px;
        top: 12px;
        transition: transform .15s ease-in-out;
        color: rgba(0,0,0,.54);
    }
    input:checked + section.box .box-title:before {
        transform: rotate(90deg);
    }
    
    .affected-icons svg {
        margin-left: 5px;
        border: solid 1px gray;
        fill: gray;
        width: 30px;
        height: 30px;
        padding: 4px;
        border-radius: 6px;
    }

    .affected-icons svg:hover {
        fill: white;
        background-color: gray;
    }

    .box-content .affected-icons svg {
        margin-left: 0px;
        margin-right: 8px;
    }

    /* progress bar*/
    .failures {
        width: 90%;
        height: 10px;
        background-color: rgb(209 213 219);
        border-radius: 6px;
        overflow: hidden;
    }

    .failures__fill {
        /* width: 0%; /*change the width according to how many failures out of total elements are there */
        height: 100%;
        background: #E11D48;
    }

    .successes {
        width: 90%;
        height: 10px;
        background-color: rgb(209 213 219);
        border-radius: 6px;
        overflow: hidden;
    }

    .successes__fill {
        /* width: 0%; /*change the width according to how many success out of total elements are there */
        height: 10px;
        background: #1AD2C7;
    }

    .scan-list {
        display: flex;
        flex-direction: column;
        margin-block-end: 12px;
        padding-block: 0;
        padding-inline: 40px 0;
        row-gap: 12px;
    }

    .scan-list__item {
        background-color: #101E43;
        border-end-end-radius: 8px;
        border-start-end-radius: 8px;
        color: #fff;
        counter-increment: list-item;
        padding: 12px;
        position: relative;
    }

    .scan-list__item::before {
        align-items: flex-start;
        background-color: #21315A;
        border-end-start-radius: 8px;
        border-start-start-radius: 8px;
        box-sizing: border-box;
        content: counter(list-item);
        display: flex;
        font-size: 14px;
        font-weight: 700;
        height: 100%;
        inset-block: 0;
        inset-inline-start: -40px;
        justify-content: center;
        padding: 12px;
        position: absolute;
        width: 40px;
    }

    .scanned_total_successes {
        width: 20px;
        font-weight: bold;
        display: flex;
        justify-content: flex-end;
    }

    .scanned_total_failures {
        font-weight: bold;
        width: 20px;
        display: flex;
        justify-content: flex-end;
    }

    /*loader-screen*/
    
    .loader {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 85vh;
        background-color: #101E43;
    }

    .scan {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .scan .logo {
        position: relative;
        width: 300px;
        height: 300px;
        background: no-repeat url('/public/assets/images/logo-gray-1.png');
        background-size: 300px;
    }


    .scan .logo::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: no-repeat url('/public/assets/images/logo-colored-2.png');
        animation: animate 6s ease-in-out infinite;
        background-size: 300px;
    }

    @keyframes animate {
        0%, 100% {
            height: 0%;
        }
        50% {
            height: 100%;
        }
    }

    .scan .logo::after {
        content: '';
        position:absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 8px;
        background: #1AD2C7;
        border-radius: 8px;
        filter: drop-shadow(0 0 30px #1AD2C7) drop-shadow(0 0 60px #1AD2C7);
        animation: animate_line 6s ease-in-out infinite;

    }

    @keyframes animate_line {
        0%, 100% {
            top: 0%;
        }
        50% {
            top: 100%;
        }
    }

    .scan h3 {
        text-transform: uppercase;
        font-size: 24px;
        font-weight: bold;
        letter-spacing: 2px;
        margin-top: 50px;
        padding: 0px 30px;
        color: #1AD2C7;
        filter: drop-shadow(0 0 60px #1AD2C7) drop-shadow(0 0 60px #1AD2C7);
    }

    @keyframes animate_text {
        0%, 100% {
            opacity: 0;
        }
        50% {
            opacity: 1;
        }
    }

</style>
