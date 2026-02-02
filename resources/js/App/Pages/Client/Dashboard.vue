<script setup>
import { ref, onMounted } from 'vue'
import AuthenticatedLayout from '@/App/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import debounce from 'lodash.debounce'
import axios from 'axios';
// import { useToast } from "vue-toastification";
import { useNotificationStore } from '@dafcoe/vue-notification'
import moment from 'moment'

const { setNotification } = useNotificationStore()
// const toast = useToast();
var searchVal = ''
var domains = new Object
var data = {
    links: [{}]
}

var plans = new Object

const renderComponent = ref(true);
const selectedItemsPerPage = ref(10);
const isPlanDisabled = ref(false);
const pageNumber = ref(1);
const pageCount = ref(0);
var sort = 'asc'
var orderBy = 'id'

const showLoader = ref(false);

onMounted(() => {
    getDomains()
});

const props = defineProps({
    search: String,
});

const form = useForm({})

var newDomainProcess = ref({
    first_step: false,
    second_step: false,
    plan_id: null,
    name: null,
    coupon: null,
})

const validateNewDomain = debounce((event) => {
    axios.post(route('domains.validate'), {
        name: newDomainProcess.value.name
    }).then((res) => {
        document.getElementById('subscribe-new-domain').disabled = false
        document.getElementById('domain-error').classList.add('hidden')
    }).catch((err) => {
        document.getElementById('subscribe-new-domain').disabled = true

        document.getElementById('domain-error').classList.remove('hidden')
        document.getElementById('domain-error').textContent = err.response.data.message || 'The domain name is invalid.'
    })
}, 300)

const validateStepTwo = (plan) => {
    isPlanDisabled.value = true;

    axios.post(route('coupon.validate'), {
        coupon: newDomainProcess.value.coupon,
        plan_id: plan.id,
    }).then((res) => {
        newDomainProcess.value.plan_id = plan.id
        document.getElementById('coupon-error').classList.add('hidden')
        subscribeDomain()
    }).catch((error) => {
        document.getElementById('coupon-error').classList.remove('hidden')
        document.getElementById('coupon-error').textContent = error.response.data.message || 'The coupon is invalid.'

        setNotification({type: 'alert', message: error.response.data.message})
    })
}

const subscribeDomain = () => {
    /** TODO: add loading animation to indicate request is being processed */
    var planElem = document.getElementsByClassName('choose-plan');
    planElem.forEach(element => {
        element.disabled = true
    });

    axios.post(route('domains.subscribe'), {
        plan_id: newDomainProcess.value.plan_id,
        name: newDomainProcess.value.name,
        coupon: newDomainProcess.value.coupon,
        page_count: pageCount.value,
    }).then((res) => {
        //props.domains.push(res.data.domain)

        resetNewDomainProcess()
        // toast.success('New domain successfully subscribed.');
        setNotification({type: 'success', message: 'New domain successfully subscribed.'})

        setInterval(() => {
            location.reload();
        }, 3000);

    }).catch((error) => {
        document.getElementById('subscribe-new-domain').disabled = false
        // toast.error(error.response.data.message);
        setNotification({type: 'alert', message: error.response.data.message})
    })
}

const resetNewDomainProcess = () => {
    newDomainProcess.value.first_step = false
    newDomainProcess.value.second_step = false
    newDomainProcess.value.plan_id = null
    newDomainProcess.value.name = null
    newDomainProcess.value.coupon = null
}

const getDomains = () => {
    const params = {
        search: searchVal,
        page: pageNumber.value,
        limit: selectedItemsPerPage.value,
        sort: sort,
        orderBy: orderBy
    };
    // Set renderComponent.value to false
    renderComponent.value = false;

    window.axios.get('/dashboard/domains', { params }).then(async res => {
        data = res.data;
        domains = res.data.data;

        // Set renderComponent.value to true
        renderComponent.value = true;
    });
};

const paginate = (page) => {
    pageNumber.value = page
    getDomains()
}

const setItemsPerPage = () => {
    pageNumber.value = 1
    getDomains()
};

// for mobile page searching
const searchPage = () => {
    pageNumber.value = 1
    getDomains()
}

const searchDomain = () => {
    pageNumber.value = 1
    getDomains()
}

const ascendingData = (order) => {
    sort = 'asc'; // lowest to highest
    pageNumber.value = 1
    orderBy = order;
    getDomains()
    
}

const descendingData = (order) => {
    sort = 'desc'; // lowest to highest
    pageNumber.value = 1
    orderBy = order;
    getDomains()
    
}

const updateOrderBy = (order) => {
    orderBy = order;
    //getDomains();
}

const subscribe = () => {
    newDomainProcess.value.first_step = false
    showLoader.value = true;
    document.getElementById('subscribe-new-domain').disabled = true

    let domain = newDomainProcess.value.name
    
    axios.get('/domains/' + domain  + '/get-plans').then(async res => {
        plans = res.data.plans
        pageCount.value = res.data.page_count
        newDomainProcess.value.second_step = true
        showLoader.value = false;
    });
}

/** TODO: show human friendly like 'in 46 days' or 'in 3 hours' */
const fromNow = (datetime) => moment(datetime).format('LLL')

const getStatusBadgeColor = (status) => {
    switch (status) {
        case 'active':
            return 'base-badge active-badge';
        case 'past_due':
            return 'base-badge past_due-badge';
        case 'unpaid':
            return 'base-badge unpaid-badge';
        case 'canceled':
            return 'base-badge cancelled-badge';
        case 'incomplete':
            return 'base-badge incomplete-badge';
        case 'incomplete_expired':
            return 'base-badge incomplete_expired-badge';
        case 'trialing':
            return 'base-badge trialing-badge';
        case 'paused':
            return 'base-badge paused-badge';
        default:
            return 'base-badge default-badge';
    }
}

//Temporary fix for the modal conflict with the filter contrast
setInterval(() => {
    var item = JSON.parse(localStorage.getItem("aceada-accessibility"))
    if (item.color.contrast == 'high' || item.color.saturation) {
        var choosePlanModal = document.getElementById('choose-plan-modal');
        if (choosePlanModal) {
            choosePlanModal.style.marginTop = '-80rem'; 
        }
        var domainModal = document.getElementById('domain-modal');
        if (domainModal) {
            domainModal.style.marginTop = '-80rem'; 
        }
    } else {
        var choosePlanModal = document.getElementById('choose-plan-modal');
        if (choosePlanModal) {
            choosePlanModal.style.marginTop = '0px'; 
        }
    }
}, 1000);

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard </h2>
        </template>

        <div class="p-5 lg:py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 rounded-2xl">
                <div class="bg-white rounded-2xl blue-shadow">
                    <div class="flex justify-between">
                        <div class="relative my-4 px-4 flex w-full flex-wrap items-stretch xl:w-1/3">
                            <input
                            type="search"
                            class="teal-border relative m-0 -mr-0.5 block w-[1px] min-w-0 flex-auto rounded-l border border-gray-300 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-neutral-700 outline-none transition duration-200 ease-in-out focus:z-[3] focus:ring-blue-500 focus:border-blue-500 block float-right dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Search an invoice, domain, period, amount or status"
                            aria-label="Search"
                            aria-describedby="button-addon1"
                            v-model="searchVal"
                            @keyup.enter="searchDomain()"/>

                            <!--Search button-->
                            <button
                                class="teal-btn relative z-[2] flex items-center rounded-r bg-primary px-6 py-2.5 text-xs font-medium uppercase leading-tight text-black shadow-md transition duration-150 ease-in-out hover:bg-primary-700 hover:shadow-lg focus:bg-primary-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-primary-800 active:shadow-lg"
                                id="button-addon1"
                                data-te-ripple-init
                                data-te-ripple-color="light"
                                @click="searchDomain()">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                    class="h-5 w-5">
                                    <path
                                    fill-rule="evenodd"
                                    d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"
                                    clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    
                        <div class="my-4 px-4">
                            <!-- <div class="col-span-2">
                                <p>Here you'll find a history of all accessilibility audits that have you have run for your websites. You can download them below.</p>
                            </div> -->
                            <button id="add-new-domain" class="btn teal-btn capitalize" @click="newDomainProcess.first_step = true">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 ml-1">
                                    <path fill-rule="evenodd" d="M12 3.75a.75.75 0 01.75.75v6.75h6.75a.75.75 0 010 1.5h-6.75v6.75a.75.75 0 01-1.5 0v-6.75H4.5a.75.75 0 010-1.5h6.75V4.5a.75.75 0 01.75-.75z" clip-rule="evenodd" />
                                </svg>
                                <span class="hidden md:block pr-1">Add New</span>Domain
                            </button>
                        </div>
                    </div>
                    

                    <div class="overflow-x-scroll lg:overflow-x-visible">
                        <div class="overflow-x-auto">  
                            <table v-if="renderComponent" class="table w-full bg-white rounded-2xl blue-shadow">
                                <thead>
                                    <tr class="text-white">
                                        <th>
                                            #
                                        </th>
                                        <th>
                                            <span class="flex justify-between items-center">
                                                Domain
                                                <div @click="updateOrderBy('name')">
                                                    <svg @click="ascendingData('name')" class="cursor-pointer" width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'asc' && orderBy === 'name' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M6.62917 3.5C7.01407 2.83333 7.97632 2.83333 8.36122 3.5L13.1244 11.75C13.5093 12.4167 13.0281 13.25 12.2583 13.25L2.73205 13.25C1.96225 13.25 1.48113 12.4167 1.86603 11.75L6.62917 3.5Z"/>
                                                    </svg>
                                                    <svg @click="descendingData('name')" class="cursor-pointer"  width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'desc' && orderBy === 'name' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M8.37083 11.5C7.98593 12.1667 7.02368 12.1667 6.63878 11.5L1.87564 3.25C1.49074 2.58333 1.97187 1.75 2.74167 1.75L12.2679 1.75C13.0377 1.75 13.5189 2.58333 13.134 3.25L8.37083 11.5Z"/>
                                                    </svg>
                                                </div>
                                            </span>
                                        </th>
                                        <th>
                                            <span class="flex justify-between items-center">
                                                Plan
                                                <div @click="updateOrderBy('product_id')">
                                                    <svg @click="ascendingData('product_id')" class="cursor-pointer" width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'asc' && orderBy === 'product_id' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M6.62917 3.5C7.01407 2.83333 7.97632 2.83333 8.36122 3.5L13.1244 11.75C13.5093 12.4167 13.0281 13.25 12.2583 13.25L2.73205 13.25C1.96225 13.25 1.48113 12.4167 1.86603 11.75L6.62917 3.5Z"/>
                                                    </svg>
                                                    <svg @click="descendingData('product_id')" class="cursor-pointer"  width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'desc' && orderBy === 'product_id' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M8.37083 11.5C7.98593 12.1667 7.02368 12.1667 6.63878 11.5L1.87564 3.25C1.49074 2.58333 1.97187 1.75 2.74167 1.75L12.2679 1.75C13.0377 1.75 13.5189 2.58333 13.134 3.25L8.37083 11.5Z"/>
                                                    </svg>
                                                </div>
                                            </span>
                                        </th>
                                        <th>
                                            <span class="flex justify-between items-center">
                                                Next Payment At
                                                <div @click="updateOrderBy('next_payment_at')">
                                                    <svg @click="ascendingData('next_payment_at')" class="cursor-pointer" width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'asc' && orderBy === 'next_payment_at' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M6.62917 3.5C7.01407 2.83333 7.97632 2.83333 8.36122 3.5L13.1244 11.75C13.5093 12.4167 13.0281 13.25 12.2583 13.25L2.73205 13.25C1.96225 13.25 1.48113 12.4167 1.86603 11.75L6.62917 3.5Z"/>
                                                    </svg>
                                                    <svg @click="descendingData('next_payment_at')" class="cursor-pointer"  width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'desc' && orderBy === 'next_payment_at' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M8.37083 11.5C7.98593 12.1667 7.02368 12.1667 6.63878 11.5L1.87564 3.25C1.49074 2.58333 1.97187 1.75 2.74167 1.75L12.2679 1.75C13.0377 1.75 13.5189 2.58333 13.134 3.25L8.37083 11.5Z"/>
                                                    </svg>
                                                </div>
                                            </span>
                                        </th>
                                        <th>
                                            <span class="flex justify-between items-center">
                                                Status
                                                <div @click="updateOrderBy('cancelled_at')">
                                                    <svg @click="ascendingData('cancelled_at')" class="cursor-pointer" width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'asc' && orderBy === 'cancelled_at' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M6.62917 3.5C7.01407 2.83333 7.97632 2.83333 8.36122 3.5L13.1244 11.75C13.5093 12.4167 13.0281 13.25 12.2583 13.25L2.73205 13.25C1.96225 13.25 1.48113 12.4167 1.86603 11.75L6.62917 3.5Z"/>
                                                    </svg>
                                                    <svg @click="descendingData('cancelled_at')" class="cursor-pointer"  width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'desc' && orderBy === 'cancelled_at' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M8.37083 11.5C7.98593 12.1667 7.02368 12.1667 6.63878 11.5L1.87564 3.25C1.49074 2.58333 1.97187 1.75 2.74167 1.75L12.2679 1.75C13.0377 1.75 13.5189 2.58333 13.134 3.25L8.37083 11.5Z"/>
                                                    </svg>
                                                </div>
                                            </span>
                                        </th>
                                        <th>
                                            <span class="flex justify-between items-center">
                                                Date Added
                                                <div @click="updateOrderBy('created_at')">
                                                    <svg @click="ascendingData('created_at')" class="cursor-pointer" width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'asc' && orderBy === 'created_at' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M6.62917 3.5C7.01407 2.83333 7.97632 2.83333 8.36122 3.5L13.1244 11.75C13.5093 12.4167 13.0281 13.25 12.2583 13.25L2.73205 13.25C1.96225 13.25 1.48113 12.4167 1.86603 11.75L6.62917 3.5Z"/>
                                                    </svg>
                                                    <svg @click="descendingData('created_at')" class="cursor-pointer"  width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'desc' && orderBy === 'created_at' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M8.37083 11.5C7.98593 12.1667 7.02368 12.1667 6.63878 11.5L1.87564 3.25C1.49074 2.58333 1.97187 1.75 2.74167 1.75L12.2679 1.75C13.0377 1.75 13.5189 2.58333 13.134 3.25L8.37083 11.5Z"/>
                                                    </svg>
                                                </div>
                                            </span>
                                        </th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="domains.length < 1">
                                        <th colspan="7" class="italic font-normal text-center">Empty</th>
                                    </tr>
                                    <tr v-else v-for="(domain, index) in domains" :key="index">
                                        <td width="1%">{{ data.from + index }}</td>
                                        <td width="20%">{{ domain.name }}</td>
                                        <td width="10%">{{ domain.product ? domain.product.name : '' }}</td>
                                        <td width="10%">
                                            <span v-if="domain.next_payment_at">{{ fromNow(domain.next_payment_at) }} {{ $page.props.timezone }}</span>
                                        </td >
                                        <td width="10%">
                                            <div
                                                :class="`${getStatusBadgeColor(domain.subscription.stripe_status)}`"
                                                v-if="domain.subscription">
                                                    <span>{{ domain.subscription.stripe_status }}</span>
                                            </div>
                                        </td>
                                        <td width="10%">{{ moment(domain.created_at).format('LL') }}</td>
                                        <td width="5%">
                                            <Link :href="route('domains.show', domain.id)" class="show-action-button hover:bg-teal-600/10 text-teal-600 capitalize tooltip" data-tip="Manage subscription">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="flex flex-col lg:flex-row justify-between items-center rounded-b-2xl border-t-4 border-gray-100 bg-white py-5 space-y-2 lg:px-6 lg:space-y-0 xl:px-12">
                        <div>
                            <p class="text-sm text-gray-700">
                                Showing
                                <span class="font-medium font-semibold"> {{ data.from }} </span>
                                to
                                <span class="font-medium font-semibold"> {{ data.to }}</span>
                                of
                                <span class="font-medium font-semibold"> {{ data.total }} </span>
                                results
                            </p>
                        </div>
                        <div class="hidden md:block" v-if="data.total > 10">
                            <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                                <button v-if="data.prev_page_url" @click="paginate(Number(data.current_page) - 1)" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                    <span class="sr-only">Previous</span>
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <!-- Loop through the links -->
                                <template v-for="(link, index) in data.links">
                                    <button
                                        v-if="index !== 0 && index !== data.links.length - 1"
                                        :key="link.url"
                                        v-html="link.label"
                                        :aria-current="link.active ? 'page' : null"
                                        class="relative inline-flex items-center px-4 py-2 text-sm font-semibold"
                                        :class="[
                                            'text-gray-900 ring-1 ring-inset ring-gray-300 focus:z-20 focus:outline-offset-0',
                                            { 'bg-indigo-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600': link.active }
                                        ]"
                                        @click="paginate(link.label)"
                                    ></button>
                                    
                                    <span v-else-if="index === data.links.length - 2 && data.links.length > 3">...</span>

                                </template>

                                <button v-if="data.next_page_url" @click="paginate(Number(data.current_page) + 1)" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                    <span class="sr-only">Next</span>
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </nav>
                        </div>

                        <div class="flex items-center mx-5 text-sm text-gray-700">
                            <span> Show </span>
                                <select v-model="selectedItemsPerPage" @change="getDomains()" id="items-per-page" class="w-16 p-2 mx-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block float-right dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition duration-200 ease-in-out">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            <span> items per page </span>
                        </div>

                            
                        <!-- mobile pagination -->
                        <div class="flex space-x-5 block md:hidden">
                            <div class="inline-flex justify-center">
                                <button v-if="data.prev_page_url" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                    <span class="sr-only">Previous</span>
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <button v-if="data.next_page_url" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                    <span class="sr-only">Next</span>
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>

                            <div class="flex w-full flex-wrap items-stretch">
                                <input
                                    type="number"
                                    class="rounded-l w-12 border-gray-600/30 text-center text-xs font-medium text-gray-900 ring-gray-300 focus:z-20 focus:outline-offset-0"
                                    min="1"
                                    id="PaginationPage"
                                    v-model="pageNumber"
                                />
                                <!--Search button-->
                                <a
                                    class="flex items-center rounded-r bg-blue-500 p-2 text-white transition duration-150 ease-in-out"
                                    type="button"
                                    id="search-page-button"
                                    @click="searchPage()">
                                    
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                        class="mr-1 h-4 w-4">
                                        <path
                                        fill-rule="evenodd"
                                        d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"
                                        clip-rule="evenodd" />
                                    </svg>

                                    Page #
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <small> Page {{ data.current_page }} / {{ data.last_page }} </small> 
                </div>
            </div>
        </div>

        <!-- Start: New Domain -->
        <!-- Step 1 -->
        <TransitionRoot as="template" :show="newDomainProcess.first_step">
            <Dialog as="div" class="relative z-10" @close="resetNewDomainProcess">
            <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
            </TransitionChild>

            <div id="domain-modal" class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all w-1/2">
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:px-6">
                            <DialogTitle as="h3" class="text-base font-semibold leading-6 text-gray-900">Domain Name</DialogTitle>
                        </div>
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex justify-start items-center text-blue-600 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                </svg>
                                <span class="text-sm ml-2">
                                    Note that for the initial payment, the current default payment method will be used. <br>
                                    <!-- You can change the payment method to be used for this subscription on the Domains tab for future invoices.  -->
                                </span>
                            </div>
                            <div class="form-control">
                                <label class="input-group">
                                    <span>https://</span>
                                    <input
                                        type="text"
                                        id="domain"
                                        name="domain"
                                        v-model="newDomainProcess.name"
                                        class="block w-full flex-1 rounded border-0 ring-1 ring-inset ring-gray-300"
                                        placeholder="example.com"
                                        @input="validateNewDomain"
                                    />
                                </label>
                                <small id="domain-error" class="text-red-500 hidden mt-2"></small>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="button" id="subscribe-new-domain" class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto disabled:opacity-25" @click="subscribe()" disabled>Subscribe</button>
                            <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" @click="resetNewDomainProcess" ref="cancelButtonRef">Cancel</button>
                        </div>
                    </DialogPanel>
                </TransitionChild>
                </div>
            </div>
            </Dialog>
        </TransitionRoot>

        <!-- Loader -->
        <TransitionRoot as="template" :show="showLoader">
            <Dialog as="div" class="relative z-10" @close="resetNewDomainProcess">
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
                </TransitionChild>
                <div id="choose-plan-modal" class="fixed inset-0 z-10 overflow-y-auto">
                    <div class="flex min-h-full items-end justify-center my-1 p-4 text-center sm:items-center sm:p-0">
                        <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                            <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all w-4/5 xl:w-3/4">
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
                                            {{ newDomainProcess.name }}
                                        </h1>
                                        <button class="ml-5 md:ml-24" type="button" @click="closeRunAuditModal" ref="cancelButtonRef">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"><path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" fill="white" /></svg>
                                        </button>
                                    </DialogTitle>
                                </div>
                                <div class="loader">
                                    <div class="scan">
                                        <div class="logo">
                                        </div>
                                        <h3>Scanning your website...</h3>
                                    </div>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- Step 2 -->
        <TransitionRoot as="template" :show="newDomainProcess.second_step">
            <Dialog as="div" class="relative z-10" @close="resetNewDomainProcess">
            <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
            </TransitionChild>

            <div id="choose-plan-modal" class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center my-1 p-4 text-center sm:items-center sm:p-0">
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all w-4/5 xl:w-3/4">
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:px-6">
                            <DialogTitle as="h3" class="text-base font-semibold leading-6 text-gray-900">Choose Plan</DialogTitle>
                        </div>
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex flex-wrap flex-row gap-5 justify-center">
                                <div class="card w-80 xl:w-96 bg-white shadow-xl" v-for="(plan, index) in plans" :key="index">
                                    <div class="relative z-10 -mx-4 group mx-0 w-full">
                                        <div aria-hidden="true" class="absolute top-0 w-full h-full rounded-2xl bg-white shadow-xl transition duration-500"></div>
                                        <div class="relative p-6 space-y-6 lg:p-8">
                                            <h3 class="text-3xl text-gray-700 font-semibold text-center">{{ plan.name }}</h3>
                                            <div>
                                                <div class="relative flex justify-around">
                                                    <div class="flex items-end">
                                                        <div class="pb-2">
                                                            <span class="block text-2xl text-gray-700 font-bold">$</span>
                                                        </div>
                                                        <span class="text-8xl text-gray-800 font-bold leading-0">{{ parseFloat(plan.price) }}</span>
                                                        <span class="block text-xl text-purple-500">/ {{ plan.interval }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <ul role="list" class="w-max space-y-4 py-6 m-auto text-gray-600">
                                                <li class="space-x-2">
                                                    <span>{{ plan.description }}</span>
                                                </li>
                                            </ul>
                                            <button type="submit" title="Submit" @click="validateStepTwo(plan)" class="block w-full py-3 px-6 text-center rounded-xl transition bg-purple-600 hover:bg-purple-700 active:bg-purple-800 focus:bg-indigo-600 group-hover:scale-105 lg:group-hover:scale-110 disabled:opacity-25 choose-plan">
                                                <span class="text-white font-semibold">
                                                    Choose
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-center mt-6 md:mt-10 mb-8">
                            <div class="form-control block">
                                <label class="input-group input-group-sm">
                                    <span class="bg-purple-600 text-white">Coupon</span>
                                    <input type="text" id="coupon" name="coupon" v-model="newDomainProcess.coupon" placeholder="Enter code here.." class="input input-bordered input-sm" />
                                </label>
                                <small id="coupon-error" class="text-red-500 hidden"></small>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto" @click="resetNewDomainProcess">Cancel</button>
                            <!-- <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" @click="resetNewDomainProcess" ref="cancelButtonRef">Cancel</button> -->
                        </div>
                    </DialogPanel>
                </TransitionChild>
                </div>
            </div>
            </Dialog>
        </TransitionRoot>
        <!-- End: New Domain -->

    </AuthenticatedLayout>
</template>

<style scoped>
    /* for the active page*/
    .text-white {
        color: white !important;
    }

    /* badges */

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
    .active-badge {
        border: 1px solid #00B087;
        background: rgba(26, 210, 199, 0.20);
        color: #00B087;
    }

    /* rose */
    .cancelled-badge {
        border: 1px solid rgb(225 29 72);
        background: rgba(225, 29, 72, .1);
        color: rgb(225 29 72);
    }

    /* orange */
    .past_due-badge {
        border: 1px solid rgb(234 88 12);
        background: rgba(234, 88, 12, .1);
        color: rgb(234 88 12);
    }

    /* red */
    .unpaid-badge {
        border: 1px solid #DF0404;
        background: rgba(255, 197, 197, 0.30);
        color: #DF0404;
    }

    /* yellow */
    .incomplete-badge {
        border: 1px solid rgb(250 204 21);
        background: rgba(250, 204, 21, 0.20);
        color: rgb(250 204 21);
    }

    /* amber */
    .incomplete_expired-badge {
        border: 1px solid rgb(253 224 71);
        background: rgba(253, 224, 71, 0.20);
        color: rgb(234 179 8);
    }

    /* blue */
    .trialing-badge {
        border: 1px solid rgb(37 99 235);
        background: rgba(37, 99, 235, 0.20);
        color: rgb(37 99 235);
    }

    /* gray */
    .paused-badge {
        border: 1px solid rgb(229 229 229);
        background: rgba(229, 229, 229, 0.20);
        color: rgb(115 115 115);
    }

    /* black */
    .default-badge {
        border: 1px solid rgb(0 0 0);
        background: transparent;
        color: rgb(0 0 0);
    }
    
    .blue-shadow {
        box-shadow: 0 0 60px rgba(226, 236, 249, 0.5);
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
        z-index: 10;
    }
    tr.text-white th {
        background-color: #3b82f6;
        color: #ffffff;
        font-size: 1.125rem;
        text-transform: capitalize;
        border-radius: 0px;
    }

    .blue-shadow {
        box-shadow: 0 0 60px rgba(226, 236, 249, 0.5);
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
