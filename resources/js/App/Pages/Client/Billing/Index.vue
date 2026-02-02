<script setup>
import AuthenticatedLayout from '@/App/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { inject } from 'vue'
// import { useToast } from 'vue-toastification'
import { useNotificationStore } from '@dafcoe/vue-notification'
import moment from 'moment'
import { ref, onMounted } from 'vue';

const { setNotification } = useNotificationStore()
const swal = inject('$swal')
// const toast = useToast()

const renderComponent = ref(true);
const selectedItemsPerPage = ref(10);
const pageNumber = ref('');

const url = new URL(window.location.href); // Get the current URL
const searchParams = new URLSearchParams(url.search); // Create a new URLSearchParams object based on the current URL's search parameters

var search = ''
var page = 1
var items = ''
var sort = 'desc'
var orderBy = 'created_at'
var invoices = new Object
var data = {
    links: [{}]
}

onMounted(() => {
    getInvoices()
});

const props = defineProps({
    default_payment_method: Object,
    payment_methods: Object,
});

const form = useForm({})

const deletePaymentMethod = (payment_method) => {
    if (isDefaultPaymentMethod(payment_method)) {
        swal({
            icon: 'warning',
            text: 'Cannot delete default payment method',
        })
        return
    }

    swal.fire({
        title: 'Are you sure?',
        text: `You won't be able to revert this!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('billing.cards.destroy', payment_method.id))
        }
    })
}

const setPaymentMethodAsDefault = (payment_method_id) => {
    swal.fire({
        title: 'Are you sure?',
        text: 'This card will be charged on all of your subscriptions.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            form.patch(route('billing.cards.set-as-default', payment_method_id), {
                onSuccess: () => {
                    // toast.success('Successfully updated default payment method.')
                    setNotification({type: 'success', message: 'Successfully updated default payment method.'})
                },
                onError: (error) => {
                    // toast.error(error.message)
                    setNotification({type: 'alert', message: error.message})
                },
            })
        }
    })
}

const isDefaultPaymentMethod = (payment_method) => {
    return props.default_payment_method.id === payment_method.stripe_payment_method
}

const getStatusBadgeColor = (status) => {
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

const getInvoices = () => {
    const params = {
        search: search,
        page: page,
        items: selectedItemsPerPage.value,
        sort: sort,
        orderBy: orderBy
    };
    // Set renderComponent.value to false
    renderComponent.value = false;

    window.axios.get('/billing/invoices', { params }).then(async res => {
        data = res.data;
        invoices = res.data.data;

        // Set the updated params in the URL search parameters
        if (params.page !== '') {
            url.searchParams.set('page', params.page);
        } else {
            url.searchParams.delete('page');
        }

        if (params.items !== '') {
            url.searchParams.set('items', params.items);
        } else {
            url.searchParams.delete('items');
        }

        if (params.search !== '') {
            url.searchParams.set('search', params.search);
        } else {
            url.searchParams.delete('search');
        }

        if (params.orderBy !== '') {
            url.searchParams.set('orderBy', params.orderBy);
        } else {
            url.searchParams.delete('orderBy');
        }

        if (params.sort !== ''){
            url.searchParams.set('sort', params.sort);
        } else {
            url.searhParams.delete('sort');
        }

        // Update the URL with the modified search parameters
        window.history.replaceState(null, null, url.href);

        // Set renderComponent.value to true
        renderComponent.value = true;

        // Reload the page only if the URL has changed
        if (window.location.href !== url.href) {
            window.location.href = url.href;
        }
    });
};

const paginate = (pageNumber) => {
    page = pageNumber
    getInvoices()
}

const triggerSearch = () => {
    page = 1
    getInvoices()
}

// setting items per page and returning to the first page
const setItemsPerPage = () => {
    page = 1
    getInvoices()
};

// for mobile page searching
const searchPage = () => {
    page = page
    getInvoices()
}

const ascendingData = (order) => {
    sort = 'asc'; // lowest to highest
    page = 1
    orderBy = order;
    getInvoices()

}
const descendingData = (order) => {
    sort = 'desc'; // lowest to highest
    page = 1
    orderBy = order;
    getInvoices();

}
const updateOrderBy = (order) => {
    orderBy = order;
    //getInvoices();
}

</script>

<template>
    <Head title="Payments & Invoices" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Payments & Invoices</h2>
        </template>

        <div class="p-5 lg:py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-12">
                <div class="flex flex-wrap gap-4 lg:gap-8">
                    <div class="flex-1 lg:flex-none tooltip transition ease-in-out delay-150 hover:-translate-y-1 hover:scale-105 duration-300" data-tip="Default Card">
                        <div class=" card w-full lg:w-96 shadow-xl image-full">
                            <div class="card-body darkBlue-bg rounded-xl">
                                <h1 class="card-title mt-3 mb-14" id="card-number">****&nbsp;&nbsp;&nbsp;****&nbsp;&nbsp;&nbsp;****&nbsp;&nbsp;&nbsp;{{ default_payment_method.card.last4 }}</h1>
                                <div class="flex">
                                    <div class="flex">
                                        <div class="mr-6">
                                            <p class="mb-0 text-sm leading-normal text-white opacity-80">Card Holder</p>
                                            <h6 class="mb-0 text-white" id="card-name">{{ default_payment_method.billing_details.name }}</h6>
                                        </div>
                                        <div>
                                            <p class="mb-0 text-sm leading-normal text-white opacity-80">Expires</p>
                                            <h6 class="mb-0 text-white" id="card-expiry">{{ default_payment_method.card.exp_month }} / {{ default_payment_method.card.exp_year }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="card w-full bg-base-100 blue-shadow rounded-2xl">
                            <div id="payment-methods" class="card-body">
                                <div class="flex justify-between align-center mb-4">
                                    <h1 class="card-title">Payment Methods</h1>
                                    <Link :href="route('billing.cards.create')" class="btn teal-btn capitalize">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                            <path fill-rule="evenodd" d="M12 3.75a.75.75 0 01.75.75v6.75h6.75a.75.75 0 010 1.5h-6.75v6.75a.75.75 0 01-1.5 0v-6.75H4.5a.75.75 0 010-1.5h6.75V4.5a.75.75 0 01.75-.75z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="ml-1">Add Card</span>
                                    </Link>
                                </div>
                                <div class="flex-auto">
                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
                                        <div v-for="(payment_method, index) in payment_methods" :key="index">
                                            <div id="cardNumber" class="relative flex flex-row items-center justify-between flex-auto min-w-0 p-6 break-words bg-transparent teal-border shadow-none md-max:overflow-auto rounded-xl bg-clip-border">
                                                <h6 class="mb-0 text-black">****&nbsp;&nbsp;&nbsp;****&nbsp;&nbsp;&nbsp;****&nbsp;&nbsp;&nbsp;{{ payment_method.last4 }}&nbsp;&nbsp;</h6>
                                                <div class="btn-group">
                                                    <button class="btn btn-xs btn-outline border-slate-300" @click="deletePaymentMethod(payment_method)" style="font-size: 0.5rem">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                                            <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                    <button class="btn btn-xs btn-outline border-slate-300 border-l-0 dropdown dropdown-left">
                                                        <svg tabindex="0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM18.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                                        </svg>
                                                        <ul tabindex="0" class="dropdown-content menu shadow bg-base-100 rounded-box w-52 normal-case text-black">
                                                            <li class="text-red-500">
                                                                <button @click="deletePaymentMethod(payment_method)">Delete</button>
                                                            </li>
                                                            <li class="text-primary" :class="{ 'disabled': isDefaultPaymentMethod(payment_method) && payment_methods.length > 0 }">
                                                                <button @click="setPaymentMethodAsDefault(payment_method.id)" :disabled="isDefaultPaymentMethod(payment_method) && payment_methods.length > 0">Set as default</button>
                                                            </li>
                                                        </ul>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="blue-shadow rounded-2xl mt-4 lg:mt-8 bg-white">
                    <div class="flex flex-col lg:flex-row justify-end items-center py-3 lg:p-3 space-y-2 lg:space-y-0">
                        <div class="relative my-4 px-4 flex w-full flex-wrap items-stretch xl:w-1/3">
                            <input
                            type="search"
                            class="relative m-0 -mr-0.5 block w-[1px] min-w-0 flex-auto rounded-l border teal-border bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-neutral-700 outline-none transition duration-200 ease-in-out focus:z-[3] focus:ring-blue-500 focus:border-blue-500 block float-right dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Search an invoice, domain, period, amount or status"
                            aria-label="Search"
                            aria-describedby="button-addon1"
                            v-model="search"
                            @keyup.enter="triggerSearch()"/>

                            <!--Search button-->
                            <button
                                class="teal-btn relative z-[2] flex items-center rounded-r bg-primary px-6 py-2.5 text-xs font-medium uppercase leading-tight text-black shadow-md transition duration-150 ease-in-out hover:bg-primary-700 hover:shadow-lg focus:bg-primary-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-primary-800 active:shadow-lg"
                                id="button-addon1"
                                data-te-ripple-init
                                data-te-ripple-color="light"
                                @click="triggerSearch()">
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
                    </div>
                    <div class="overflow-x-scroll">
                        <table v-if="renderComponent" class="table w-full bg-white rounded-2xl blue-shadow">
                            <thead>
                                <tr class="text-white">
                                    <th>#</th>
                                    <th>
                                        <span class="flex justify-between items-center">
                                            Invoice No.
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
                                            Domain
                                            <div @click="updateOrderBy('domain_name')">
                                                <svg @click="ascendingData('domain_name')" class="cursor-pointer" width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'asc' && orderBy === 'domain_name' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6.62917 3.5C7.01407 2.83333 7.97632 2.83333 8.36122 3.5L13.1244 11.75C13.5093 12.4167 13.0281 13.25 12.2583 13.25L2.73205 13.25C1.96225 13.25 1.48113 12.4167 1.86603 11.75L6.62917 3.5Z"/>
                                                </svg>
                                                <svg @click="descendingData('domain_name')" class="cursor-pointer"  width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'desc' && orderBy === 'domain_name' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8.37083 11.5C7.98593 12.1667 7.02368 12.1667 6.63878 11.5L1.87564 3.25C1.49074 2.58333 1.97187 1.75 2.74167 1.75L12.2679 1.75C13.0377 1.75 13.5189 2.58333 13.134 3.25L8.37083 11.5Z"/>
                                                </svg>
                                            </div>
                                        </span>
                                    </th>
                                    <th>
                                        <span class="flex justify-between items-center">
                                            Period
                                            <div @click="updateOrderBy('period_end')">
                                                <svg @click="ascendingData('period_end')" class="cursor-pointer" width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'asc' && orderBy === 'period_end' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6.62917 3.5C7.01407 2.83333 7.97632 2.83333 8.36122 3.5L13.1244 11.75C13.5093 12.4167 13.0281 13.25 12.2583 13.25L2.73205 13.25C1.96225 13.25 1.48113 12.4167 1.86603 11.75L6.62917 3.5Z"/>
                                                </svg>
                                                <svg @click="descendingData('period_end')" class="cursor-pointer"  width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'desc' && orderBy === 'period_end' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8.37083 11.5C7.98593 12.1667 7.02368 12.1667 6.63878 11.5L1.87564 3.25C1.49074 2.58333 1.97187 1.75 2.74167 1.75L12.2679 1.75C13.0377 1.75 13.5189 2.58333 13.134 3.25L8.37083 11.5Z"/>
                                                </svg>
                                            </div>
                                        </span>
                                    </th>
                                    <th>
                                        <span class="flex justify-between items-center">
                                            Amount
                                            <div @click="updateOrderBy('subtotal')">
                                                <svg @click="ascendingData('subtotal')" class="cursor-pointer" width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'asc' && orderBy === 'subtotal' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6.62917 3.5C7.01407 2.83333 7.97632 2.83333 8.36122 3.5L13.1244 11.75C13.5093 12.4167 13.0281 13.25 12.2583 13.25L2.73205 13.25C1.96225 13.25 1.48113 12.4167 1.86603 11.75L6.62917 3.5Z"/>
                                                </svg>
                                                <svg @click="descendingData('subtotal')" class="cursor-pointer"  width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'desc' && orderBy === 'subtotal' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8.37083 11.5C7.98593 12.1667 7.02368 12.1667 6.63878 11.5L1.87564 3.25C1.49074 2.58333 1.97187 1.75 2.74167 1.75L12.2679 1.75C13.0377 1.75 13.5189 2.58333 13.134 3.25L8.37083 11.5Z"/>
                                                </svg>
                                            </div>
                                        </span>
                                    </th>
                                    <th>
                                        <span class="flex justify-between items-center">
                                            Status
                                            <div @click="updateOrderBy('status')">
                                                <svg @click="ascendingData('status')" class="cursor-pointer" width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'asc' && orderBy === 'status' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6.62917 3.5C7.01407 2.83333 7.97632 2.83333 8.36122 3.5L13.1244 11.75C13.5093 12.4167 13.0281 13.25 12.2583 13.25L2.73205 13.25C1.96225 13.25 1.48113 12.4167 1.86603 11.75L6.62917 3.5Z"/>
                                                </svg>
                                                <svg @click="descendingData('status')" class="cursor-pointer"  width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'desc' && orderBy === 'status' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8.37083 11.5C7.98593 12.1667 7.02368 12.1667 6.63878 11.5L1.87564 3.25C1.49074 2.58333 1.97187 1.75 2.74167 1.75L12.2679 1.75C13.0377 1.75 13.5189 2.58333 13.134 3.25L8.37083 11.5Z"/>
                                                </svg>
                                            </div>
                                        </span>
                                    </th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="invoices.length < 1">
                                    <th colspan="7" class="italic font-normal text-center">Empty</th>
                                </tr>
                                <tr v-else v-for="(invoice, index) in invoices" :key="index">
                                    <th>{{ data.from + index }}</th>
                                    <td>{{ invoice.stripe_invoice }}</td>
                                    <td >
                                        <Link :href="route('domains.show', invoice.domain_id)" class="text-blue-600 underline tooltip" data-tip="View Subscription" v-if="invoice.domain_id">
                                            {{ invoice.domain_name }}
                                        </Link>
                                    </td>
                                    <td>
                                        <small>Start:</small> {{ moment(invoice.period_start).format('LL') }} <br>
                                        <small>End:</small> {{ moment(invoice.period_end).format('LL') }}
                                    </td>
                                    <td>
                                        <small>Subtotal:</small> ${{ invoice.subtotal }} <br>
                                        <small>Total:</small> ${{ invoice.total }}
                                    </td>
                                    <td>
                                        <div :class="`${getStatusBadgeColor(invoice.status)}`">
                                            <span>{{ invoice.status }}</span>
                                        </div>
                                    </td>
                                    <td class="space-x-1">
                                        <Link :href="route('billing.invoices.show', invoice.id)" class="show-action-button hover:bg-teal-600/10 text-teal-600 capitalize tooltip" data-tip="View Invoice">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </Link>
                                        <a :href="route('billing.invoices.download', invoice.stripe_invoice)" class="show-action-button hover:bg-teal-600/10 text-teal-600 capitalize tooltip" data-tip="Download" target="_blank">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
                                <select v-model="selectedItemsPerPage" @change="setItemsPerPage()" id="items-per-page" class="w-16 p-2 mx-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block float-right dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 transition duration-200 ease-in-out">
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
                                    v-model="page"
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

            </div>
            <div class="mt-4 text-center">
                <small> Page {{ data.current_page }} / {{ data.last_page }} </small> 
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
    #cardNumber:hover {
        border: solid 1px #9cd3d0;
    }

    .darkBlue-bg {
        background-color: #031239;
    }

    .darkBlue-bg:hover {
        background-color: #101E43;

    }
    /* for the active page*/
    .text-white {
        color: white !important;
    }

    .blue-shadow {
        box-shadow: 0 0 60px rgba(226, 236, 249, 0.5);
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

    /* badges */
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
    .base-badge {
        border-radius: 8px;
        width: -moz-fit-content;
        width: fit-content;
        justify-content: center;
        align-items: center;
        padding: 4px 20px;
    }
</style>