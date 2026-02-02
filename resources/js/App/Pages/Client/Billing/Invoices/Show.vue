<script setup>
import AuthenticatedLayout from '@/App/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import moment from 'moment'

const props = defineProps({
    invoice: Object
})

const formatAmount = (amount) => {
    const formatter =  new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    })

    return formatter.format(amount)
}

const formatTimestamp = (timestamp) => {
    const date = new Date(timestamp * 1000)
    return moment(date).utc().format('LLL')
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
</script>

<template>
    <Head title="Invoice Details" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Invoice Details</h2>
        </template>

        <div class="p-5 lg:py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-12">
                <div class="overflow-hidden bg-white blue-shadow sm:rounded-lg rounded-2x">
                    <div class="flex bg-blue-500 justify-between items-center py-5 px-4">
                        <h1 class="text-2xl text-white">{{ invoice.number }}</h1>
                        <a :href="route('billing.invoices.download', invoice.id)" class="flex p-3 rounded text-white capitalize hover:border hover:border-white" target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            &nbsp; Download
                        </a>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Invoice number</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ invoice.number }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Billing to</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ invoice.customer_email }}</dd>
                            </div>
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Billing details</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ invoice.customer_name }}</dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                    <div :class="`${getStatusBadgeColor(invoice.status)}`">
                                        <span>{{ invoice.status }}</span>
                                    </div>
                                </dd>
                            </div>
                            <div class="bg-white py-5">
                                <div class="overflow-x-auto">
                                    <table class="table w-full">
                                        <!-- head -->
                                        <thead>
                                            <tr class="text-white">
                                                <th class="w-3/4">Description</th>
                                                <th class="w-1/4">Quantity</th>
                                                <th class="w-1/4">Unit Price</th>
                                                <th class="w-1/4">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(line, index) in invoice.lines.data" :key="index">
                                                <th class="w-3/4">
                                                    <span class="block"> {{ line.description }} </span>
                                                    <small class="font-light text-slate-500">{{ formatTimestamp(line.period.start) }} - {{ formatTimestamp(line.period.end) }}</small>
                                                </th>
                                                <td class="w-1/4">{{ line.quantity }}</td>
                                                <td class="w-1/4">{{ formatAmount(line.amount_excluding_tax / 100) }}</td>
                                                <td class="w-1/4">{{ formatAmount(line.amount / 100) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="w-3/4 border-b-0 py-2"></td>
                                                <td class="w-1/4 border-b-0 py-2"></td>
                                                <td class="text-sm w-1/4 border-b-0 py-2 text-right font-bold">Subtotal</td>
                                                <td class="text-sm w-1/4 border-b-0 py-2">{{ formatAmount(invoice.subtotal / 100) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="w-3/4 border-b-0 py-2"></td>
                                                <td class="w-1/4 border-b-0 py-2"></td>
                                                <td class="text-sm w-1/4 border-b-0 py-2 text-right font-bold">Total</td>
                                                <td class="text-sm w-1/4 border-b-0 py-2">{{ formatAmount(invoice.total / 100) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="w-3/4 border-b-0 py-2"></td>
                                                <td class="w-1/4 border-b-0 py-2"></td>
                                                <td class="text-sm w-1/4 border-b-0 py-2 text-right">Amount Paid</td>
                                                <td class="text-sm w-1/4 border-b-0 py-2">-{{ formatAmount(invoice.amount_paid / 100) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="w-3/4 border-b-0 py-2"></td>
                                                <td class="w-1/4 border-b-0 py-2"></td>
                                                <td class="text-sm w-1/4 border-b-0 py-2 text-right font-bold">Amount Due</td>
                                                <td class="text-sm w-1/4 border-b-0 py-2">{{ formatAmount(invoice.amount_remaining / 100) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
    /* Base class */
    .base-badge {
        border-radius: 8px;
        width: fit-content;
        padding: 4px;
        justify-content: center;
        align-items: center;
        padding: 4px 20px;
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
</style>