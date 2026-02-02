<script setup>
import AdminLayout from '@/App/Layouts/AdminLayout.vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import moment from 'moment'
import { inject, ref } from 'vue'
// import { useToast } from "vue-toastification";
import { useNotificationStore } from '@dafcoe/vue-notification'

const { setNotification } = useNotificationStore()
const swal = inject('$swal')

const form = useForm({})
const changePlanForm = useForm({plan_id: null})
// const toast = useToast();
const changePlanProcess = ref(false)
const changePlanLoading = ref(false)


const url = new URL(window.location.href); // Get the current URL
const searchParams = new URLSearchParams(url.search); // Create a new URLSearchParams object based on the current URL's search parameters

// On the first load of page, it will check if there are current URL parameter of status
const selectedItemsPerPage = ref(searchParams.get('size') || 10); // Set the number of items per page to 10
const sort = ref(searchParams.get('sort') || 'desc'); // Set the order direction, default to descending
const orderBy = ref(searchParams.get('orderBy') || 'created_at'); // Set the order, default to creation date
const pageNumber = ref ('');

const props = defineProps({
    domain: Object,
    subscription: Object,
    paymentMethod: Object,
    invoices: Object,
    coupon: Object,
    plans: Object,
})

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

const getInvoiceStatusBadgeColor = (status) => {
    switch (status) {
        case 'draft':
            return 'base-badge draft-badge';
        case 'open':
            return 'base-badge open-badge';
        case 'paid':
            return 'base-badge paid-badge';
        case 'uncollectible':
            return 'base-badge uncollectible-badge';
        case 'void':
            return 'base-badge void-badge';
        default:
            return 'base-badge default-badge'
    }
}

const formatTimestamp = (timestamp) => {
    const date = new Date(timestamp * 1000)
    return moment(date).utc().format('LLL')
}

const setItemsPerPage = () => {
    if (selectedItemsPerPage.value !== '') {
        searchParams.set('page', '1'); // Return to page 1
        searchParams.set('size', selectedItemsPerPage.value); // Set the link in relation to the selected value for items per page
        props.invoices.per_page = selectedItemsPerPage.value; // Set the number of items per page in the frontend
    } else {
        searchParams.delete('size');
    }
    updateURL()
};

const updateOrderBy = (order) => {
    searchParams.set('orderBy', order);
    updateURL()
}

const ascendingData = () => {
    searchParams.set('sort', 'asc'); // Return to page 1
}

const descendingData = () => {
    searchParams.set('sort', 'desc'); // Return to page 1
}

const updateURL = () => {
    const newSearchParamsString = searchParams.toString(); // Convert the updated search parameters back to a string
    url.search = newSearchParamsString; // Update the URL with the modified search parameters
    window.location.href = url.toString(); // Reload the page with the updated URL
}

</script>

<template>
    <Head title="Subscription Details" />

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Subscription Details</h2>
        </template>

        <div class="p-5 lg:py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-12">
                <div class="flex flex-wrap gap-4 lg:gap-8">
                    <div class="alert alert-warning shadow-lg" v-if="subscription.status !== 'canceled' && subscription.cancel_at_period_end">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            <span>Warning: This subscription is schedule for cancellation on {{ formatTimestamp(subscription.cancel_at) }} {{ $page.props.timezone }}</span>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="overflow-hidden bg-white blue-shadow rounded-2xl">
                            <div class="bg-blue-500 flex flex-col lg:flex-row justify-between items-start lg:items-center px-4 py-5 sm:px-6">
                                <h3 class="text-lg font-medium leading-6 text-white">{{ subscription.id }}</h3>
                            </div>
                            <div class="border-t border-gray-200">
                                <dl>
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Domain</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ domain.name }}</dd>
                                    </div>
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Plan</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ domain.product.name }}</dd>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Price</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">${{ domain.product.price }}</dd>
                                    </div>
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Interval</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ domain.product.interval }}</dd>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Started At</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ formatTimestamp(subscription.start_date) }} {{ $page.props.timezone }}</dd>
                                    </div>
                                    <!-- <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Next Payment</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ moment(domain.next_payment_at).format('LLL') }} {{ $page.props.timezone }}</dd>
                                    </div> -->
                                    <!-- <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6" v-if="subscription.ended_at">
                                        <dt class="text-sm font-medium text-gray-500">Ended At</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ formatTimestamp(subscription.ended_at) }} {{ $page.props.timezone }}</dd>
                                    </div> -->
                                    <!-- <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6" v-if="subscription.canceled_at">
                                        <dt class="text-sm font-medium text-gray-500">Cancelled At</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ formatTimestamp(subscription.canceled_at) }} {{ $page.props.timezone }}</dd>
                                    </div> -->
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6" v-if="subscription.current_period_start">
                                        <dt class="text-sm font-medium text-gray-500">Current Period Start</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ formatTimestamp(subscription.current_period_start) }} {{ $page.props.timezone }}</dd>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6" v-if="subscription.current_period_end">
                                        <dt class="text-sm font-medium text-gray-500">Current Period End</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ formatTimestamp(subscription.current_period_end) }} {{ $page.props.timezone }}</dd>
                                    </div>
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Latest Invoice</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ subscription.latest_invoice }}</dd>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6" v-if="coupon.code">
                                        <dt class="text-sm font-medium text-gray-500">Coupon Code</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ coupon.code }}</dd>
                                    </div>
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6" v-if="coupon.duration">
                                        <dt class="text-sm font-medium text-gray-500">Coupon Duration</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ coupon.duration }}</dd>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                            <div :class="`${getStatusBadgeColor(subscription.status)}`">
                                                <span>{{ subscription.status }}</span>
                                            </div>
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="flex-1 lg:flex-none">
                        <div class="card w-full lg:w-96 shadow-2xl blue-shadow image-full">
                            <div class="card-body darkBlue-bg rounded-xl">
                                <h1 class="card-title mt-3 mb-14" id="card-number">****&nbsp;&nbsp;&nbsp;****&nbsp;&nbsp;&nbsp;****&nbsp;&nbsp;&nbsp;{{ paymentMethod.last4 }}</h1>
                                <div class="flex">
                                    <div class="flex">
                                        <div class="mr-6">
                                            <p class="mb-0 text-sm leading-normal text-white opacity-80">Card Holder</p>
                                            <h6 class="mb-0 text-white" id="card-name">{{ paymentMethod.billing_name }}</h6>
                                        </div>
                                        <div>
                                            <p class="mb-0 text-sm leading-normal text-white opacity-80">Expires</p>
                                            <h6 class="mb-0 text-white" id="card-expiry">{{ paymentMethod.exp_month }} / {{ paymentMethod.exp_year }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl blue-shadow mt-5">
                    <div class="overflow-x-scroll rounded-2xl">
                        <table class="table w-full bg-white">
                            <thead>
                                <tr class="text-white">
                                    <th>
                                        <span class="flex justify-between items-center">
                                            Reference No.
                                            <div @click="updateOrderBy('number')">
                                                <svg @click="ascendingData()" class="cursor-pointer" width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'asc' && orderBy === 'number' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6.62917 3.5C7.01407 2.83333 7.97632 2.83333 8.36122 3.5L13.1244 11.75C13.5093 12.4167 13.0281 13.25 12.2583 13.25L2.73205 13.25C1.96225 13.25 1.48113 12.4167 1.86603 11.75L6.62917 3.5Z"/>
                                                </svg>
                                                <svg @click="descendingData()" class="cursor-pointer"  width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'desc' && orderBy === 'number' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8.37083 11.5C7.98593 12.1667 7.02368 12.1667 6.63878 11.5L1.87564 3.25C1.49074 2.58333 1.97187 1.75 2.74167 1.75L12.2679 1.75C13.0377 1.75 13.5189 2.58333 13.134 3.25L8.37083 11.5Z"/>
                                                </svg>
                                            </div>
                                        </span>
                                    </th>
                                    <th>
                                        <span class="flex justify-between items-center">
                                            Stripe No.
                                            <div @click="updateOrderBy('stripe_invoice')">
                                                <svg @click="ascendingData()" class="cursor-pointer" width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'asc' && orderBy === 'stripe_invoice' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6.62917 3.5C7.01407 2.83333 7.97632 2.83333 8.36122 3.5L13.1244 11.75C13.5093 12.4167 13.0281 13.25 12.2583 13.25L2.73205 13.25C1.96225 13.25 1.48113 12.4167 1.86603 11.75L6.62917 3.5Z"/>
                                                </svg>
                                                <svg @click="descendingData()" class="cursor-pointer"  width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'desc' && orderBy === 'stripe_invoice' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8.37083 11.5C7.98593 12.1667 7.02368 12.1667 6.63878 11.5L1.87564 3.25C1.49074 2.58333 1.97187 1.75 2.74167 1.75L12.2679 1.75C13.0377 1.75 13.5189 2.58333 13.134 3.25L8.37083 11.5Z"/>
                                                </svg>
                                            </div>
                                        </span>
                                    </th>
                                    <th>
                                        <span class="flex justify-between items-center">
                                            Period Start
                                            <div @click="updateOrderBy('period_start')">
                                                <svg @click="ascendingData()" class="cursor-pointer" width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'asc' && orderBy === 'period_start' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6.62917 3.5C7.01407 2.83333 7.97632 2.83333 8.36122 3.5L13.1244 11.75C13.5093 12.4167 13.0281 13.25 12.2583 13.25L2.73205 13.25C1.96225 13.25 1.48113 12.4167 1.86603 11.75L6.62917 3.5Z"/>
                                                </svg>
                                                <svg @click="descendingData()" class="cursor-pointer"  width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'desc' && orderBy === 'period_start' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8.37083 11.5C7.98593 12.1667 7.02368 12.1667 6.63878 11.5L1.87564 3.25C1.49074 2.58333 1.97187 1.75 2.74167 1.75L12.2679 1.75C13.0377 1.75 13.5189 2.58333 13.134 3.25L8.37083 11.5Z"/>
                                                </svg>
                                            </div>
                                        </span>
                                    </th>
                                    <th>
                                        <span class="flex justify-between items-center">
                                            Period End
                                            <div @click="updateOrderBy('period_end')">
                                                <svg @click="ascendingData()" class="cursor-pointer" width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'asc' && orderBy === 'period_end' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6.62917 3.5C7.01407 2.83333 7.97632 2.83333 8.36122 3.5L13.1244 11.75C13.5093 12.4167 13.0281 13.25 12.2583 13.25L2.73205 13.25C1.96225 13.25 1.48113 12.4167 1.86603 11.75L6.62917 3.5Z"/>
                                                </svg>
                                                <svg @click="descendingData()" class="cursor-pointer"  width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'desc' && orderBy === 'period_end' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8.37083 11.5C7.98593 12.1667 7.02368 12.1667 6.63878 11.5L1.87564 3.25C1.49074 2.58333 1.97187 1.75 2.74167 1.75L12.2679 1.75C13.0377 1.75 13.5189 2.58333 13.134 3.25L8.37083 11.5Z"/>
                                                </svg>
                                            </div>
                                        </span>
                                    </th>
                                    <th>
                                        <span class="flex justify-between items-center">
                                            Amount
                                            <div @click="updateOrderBy('total')">
                                                <svg @click="ascendingData()" class="cursor-pointer" width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'asc' && orderBy === 'total' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6.62917 3.5C7.01407 2.83333 7.97632 2.83333 8.36122 3.5L13.1244 11.75C13.5093 12.4167 13.0281 13.25 12.2583 13.25L2.73205 13.25C1.96225 13.25 1.48113 12.4167 1.86603 11.75L6.62917 3.5Z"/>
                                                </svg>
                                                <svg @click="descendingData()" class="cursor-pointer"  width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'desc' && orderBy === 'total' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8.37083 11.5C7.98593 12.1667 7.02368 12.1667 6.63878 11.5L1.87564 3.25C1.49074 2.58333 1.97187 1.75 2.74167 1.75L12.2679 1.75C13.0377 1.75 13.5189 2.58333 13.134 3.25L8.37083 11.5Z"/>
                                                </svg>
                                            </div>
                                        </span>
                                    </th>
                                    <th>
                                        <span class="flex justify-between items-center">
                                            Status
                                            <div @click="updateOrderBy('status')">
                                                <svg @click="ascendingData()" class="cursor-pointer" width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'asc' && orderBy === 'status' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6.62917 3.5C7.01407 2.83333 7.97632 2.83333 8.36122 3.5L13.1244 11.75C13.5093 12.4167 13.0281 13.25 12.2583 13.25L2.73205 13.25C1.96225 13.25 1.48113 12.4167 1.86603 11.75L6.62917 3.5Z"/>
                                                </svg>
                                                <svg @click="descendingData()" class="cursor-pointer"  width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'desc' && orderBy === 'status' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8.37083 11.5C7.98593 12.1667 7.02368 12.1667 6.63878 11.5L1.87564 3.25C1.49074 2.58333 1.97187 1.75 2.74167 1.75L12.2679 1.75C13.0377 1.75 13.5189 2.58333 13.134 3.25L8.37083 11.5Z"/>
                                                </svg>
                                            </div>
                                        </span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="invoices.length < 1">
                                    <th colspan="6" class="italic font-normal text-center">Empty</th>
                                </tr>
                                <tr v-else v-for="(invoice, index) in invoices.data" :key="index">
                                    <td><strong>{{ invoice.number }}</strong></td>
                                    <td><strong>{{ invoice.stripe_invoice }}</strong></td>
                                    <td>{{ moment(invoice.period_start).format('LL') }}</td>
                                    <td>{{ moment(invoice.period_end).format('LL') }}</td>
                                    <td>
                                        <small>Subtotal:</small> ${{ invoice.subtotal }} <br>
                                        <small>Total:</small> ${{ invoice.total }}
                                    </td>
                                    <td>
                                        <div :class="`${getInvoiceStatusBadgeColor(invoice.status)}`">
                                            <span>{{ invoice.status }}</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="flex flex-col lg:flex-row justify-between items-center rounded-b-2xl border-t-4 border-gray-100 bg-white py-5 space-y-2 lg:px-6 lg:space-y-0 xl:px-12">
                        <div>
                            <p class="text-sm text-gray-700">
                                Showing
                                <span class="font-medium font-semibold"> {{ invoices.from }} </span>
                                to
                                <span class="font-medium font-semibold"> {{ invoices.to }}</span>
                                of
                                <span class="font-medium font-semibold"> {{ invoices.total }} </span>
                                results
                            </p>
                        </div>
                        <div class="hidden md:block">
                            <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                                <a v-if="invoices.links[0].url" :href="`${invoices.links[0].url}&status=${selectedStatus}&size=${selectedItemsPerPage}`" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                    <span class="sr-only">Previous</span>
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                <!-- Loop through the links -->
                                <template v-for="(link, index) in invoices.links">
                                    <a
                                        v-if="index !== 0 && index !== invoices.links.length - 1"
                                        :href="`${link.url}&size=${selectedItemsPerPage}`"
                                        :key="`${link.url}`"
                                        v-html="link.label"
                                        :aria-current="link.active ? 'page' : null"
                                        class="relative inline-flex items-center px-4 py-2 text-sm font-semibold"
                                        :class="[
                                            'text-gray-900 ring-1 ring-inset ring-gray-300 focus:z-20 focus:outline-offset-0',
                                            { 'bg-indigo-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600': link.active }
                                        ]"
                                    ></a>
                                    
                                    <span v-else-if="index === invoices.links.length - 2 && invoices.links.length > 3">...</span>

                                </template>

                                <a v-if="invoices.links[invoices.links.length - 1].url" :href="`${invoices.links[invoices.links.length - 1].url}&size=${selectedItemsPerPage}`" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                    <span class="sr-only">Next</span>
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </nav>
                        </div>
                        <div class="flex items-center mx-5 text-sm text-gray-700">
                            <span> Show </span>
                            <select v-model="selectedItemsPerPage" @change="setItemsPerPage()" id="items-per-page" class="w-16 p-2 mx-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block float-right dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
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
                                <a v-if="invoices.links[0].url" :href="`${invoices.links[0].url}&size=${selectedItemsPerPage}`" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                    <span class="sr-only">Previous</span>
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                <a v-if="invoices.links[invoices.links.length - 1].url" :href="`${invoices.links[invoices.links.length - 1].url}&size=${selectedItemsPerPage}`" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                    <span class="sr-only">Next</span>
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </div>

                            <div v-if="invoices.total > 10" class="flex w-full flex-wrap items-stretch">
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
                                    :href="`/domains/${domain.id}?page=${pageNumber}&size=${selectedItemsPerPage}`">
                                    
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

            </div>
        </div>

        <!-- change plan -->
        <TransitionRoot as="template" :show="changePlanProcess">
            <Dialog as="div" class="relative z-20" @close="changePlanProcess = false">
            <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 z-20 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center my-1 p-4 text-center sm:items-center sm:p-0">
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    <DialogPanel class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all w-4/5 xl:w-3/4">
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:px-6">
                            <DialogTitle as="h3" class="text-base font-semibold leading-6 text-gray-900">Choose Plan</DialogTitle>
                        </div>
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex flex-wrap flex-row gap-5 justify-center">
                                <div class="card w-80 xl:w-96 bg-white shadow-xl" v-for="(plan, index) in plans" :key="index">
                                    <div class="relative z-20 -mx-4 group mx-0 w-full">
                                        <div aria-hidden="true" class="absolute top-0 w-full h-full rounded-2xl bg-white shadow-xl transition duration-500"></div>
                                        <div class="relative p-6 space-y-6 lg:p-8">
                                            <h3 class="text-3xl text-gray-700 font-semibold text-center">{{ plan.name }}</h3>
                                            <div>
                                                <div class="relative flex justify-around">
                                                    <div class="flex items-end">
                                                        <div class="pb-2">
                                                            <span class="block text-2xl text-gray-700 font-bold">$</span>
                                                        </div>
                                                        <span class="text-8xl text-gray-800 font-bold leading-0">{{ parseInt(plan.price) }}</span>
                                                        <span class="block text-xl text-purple-500">/ {{ plan.interval }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <ul role="list" class="w-max space-y-4 py-6 m-auto text-gray-600">
                                                <li class="space-x-2">
                                                    <span>{{ plan.description }}</span>
                                                </li>
                                            </ul>
                                            <button
                                                type="submit"
                                                title="Submit"
                                                :class="`block w-full py-3 px-6 text-center rounded-xl transition ${domain.product.id === plan.id ? 'bg-gray-400 cursor-not-allowed' : 'bg-purple-600 hover:bg-purple-700 active:bg-purple-800 focus:bg-indigo-600 group-hover:scale-105 lg:group-hover:scale-110'} ${changePlanLoading ? 'cursor-not-allowed' : ''}`"
                                                @click="changePlan(plan)"
                                                :disabled="changePlanLoading"
                                            >
                                                <span class="text-white italic" v-if="domain.product.id === plan.id">
                                                    Active Plan
                                                </span>
                                                <span class="text-white font-semibold" v-else>
                                                    {{ parseInt(domain.product.price) < parseInt(plan.price) ? 'Upgrade' : 'Downgrade' }} Plan
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto" @click="changePlanProcess = false">Cancel</button>
                        </div>
                    </DialogPanel>
                </TransitionChild>
                </div>
            </div>
            </Dialog>
        </TransitionRoot>

    </AdminLayout>
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

    /* Invoice Status Badge */
    .void-badge {
        border: 1px solid #DF0404;
        background: rgba(255, 197, 197, 0.30);
        color: #DF0404;
    }

    .uncollectible-badge {
        border: 1px solid rgb(234 88 12);
        background: rgba(234, 88, 12, .1);
        color: rgb(234 88 12);
    }

    .paid-badge {
        border: 1px solid #00B087;
        background: rgba(26, 210, 199, 0.20);
        color: #00B087;
    }

    .open-badge {
        border: 1px solid rgb(37 99 235);
        background: rgba(37, 99, 235, 0.20);
        color: rgb(37 99 235);
    }

    .draft-badge {
        border: 1px solid rgb(139 92 246);
        background: rgba(139, 92, 246, 0.20);
        color: rgb(124 58 237);
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
</style>
