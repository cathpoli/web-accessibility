<script setup>
import { ref } from 'vue'
import AuthenticatedLayout from '@/App/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import debounce from 'lodash.debounce'
import axios from 'axios';
// import { useToast } from "vue-toastification";
import { useNotificationStore } from '@dafcoe/vue-notification'
import moment from 'moment'

const { setNotification } = useNotificationStore()
// const toast = useToast();

const props = defineProps({
    domains: Object,
    plans: Object,
});

var newDomainProcess = ref({
    active: false,
    plan_id: null,
    name: null,
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
        document.getElementById('domain-error').textContent = 'The domain name is invalid.'
    })
}, 300)

const subscribeDomain = () => {
    /** TODO: add loading animation to indicate request is being processed */

    document.getElementById('subscribe-new-domain').disabled = true

    axios.post(route('domains.subscribe'), {
        plan_id: newDomainProcess.value.plan_id,
        name: newDomainProcess.value.name
    }).then((res) => {
        props.domains.push(res.data.domain)

        resetNewDomainProcess()
        // toast.success('New domain successfully subscribed.');
        setNotification({type: 'success', message: 'New domain successfully subscribed.'})
    }).catch((error) => {
        // toast.error(error.response.data.message);
        setNotification({type: 'alert', message: error.response.data.message})
    })
}

const resetNewDomainProcess = () => {
    newDomainProcess.value.active = false
    newDomainProcess.value.plan_id = null
    newDomainProcess.value.name = null
}

/** TODO: show human friendly like 'in 46 days' or 'in 3 hours' */
const fromNow = (datetime) => moment(datetime).format('LLL')

const getStatusBadgeColor = (status) => {
    switch (status) {
        case 'active':
            return 'bg-green-600 border-green-600';
        case 'past_due':
            return 'bg-orange-600 border-orange-600';
        case 'unpaid':
            return 'bg-red-600 border-red-600';
        case 'canceled':
            return 'bg-rose-600 border-rose-600';
        case 'incomplete':
            return 'bg-yellow-600 border-yellow-600';
        case 'incomplete_expired':
            return 'bg-amber-600 border-amber-600';
        case 'trialing':
            return 'bg-blue-600 border-blue-600';
        case 'paused':
            return 'bg-neutral-600 border-neutral-600';
        default:
            return 'bg-transparent border-tranparent text-black'
    }
}

</script>

<template>
    <Head title="Subscribed Domains" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Subscribed Domains</h2>
        </template>

        <div class="p-5 lg:py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-xl sm:rounded-lg">
                    <div class="flex justify-start lg:justify-end p-5">
                        <!-- <div class="col-span-2">
                            <p>Here you'll find a history of all accessilibility audits that have you have run for your websites. You can download them below.</p>
                        </div> -->
                        <button id="add-new-domain" class="btn btn-sm btn-primary capitalize" @click="newDomainProcess.active = true">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                <path fill-rule="evenodd" d="M12 3.75a.75.75 0 01.75.75v6.75h6.75a.75.75 0 010 1.5h-6.75v6.75a.75.75 0 01-1.5 0v-6.75H4.5a.75.75 0 010-1.5h6.75V4.5a.75.75 0 01.75-.75z" clip-rule="evenodd" />
                            </svg>
                            Add New Domain
                        </button>
                    </div>
                    <div class="overflow-x-scroll lg:overflow-x-visible">
                        <table class="table w-full drop-shadow-lg">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Domain</th>
                                    <th>Plan</th>
                                    <th>Next Payment At</th>
                                    <th>Status</th>
                                    <th>Date Added</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="domains.length < 1">
                                    <th colspan="7" class="italic font-normal text-center">Empty</th>
                                </tr>
                                <tr v-else v-for="(domain, index) in domains" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ domain.name }}</td>
                                    <td>{{ domain.product.name }}</td>
                                    <td>
                                        <span v-if="domain.next_payment_at">{{ fromNow(domain.next_payment_at) }} {{ $page.props.timezone }}</span>
                                    </td>
                                    <td>
                                        <div :class="`badge ${getStatusBadgeColor(domain.subscription.stripe_status)}`" v-if="domain.subscription">
                                            <span>{{ domain.subscription.stripe_status }}</span>
                                        </div>
                                    </td>
                                    <td>{{ moment(domain.created_at).format('LL') }}</td>
                                    <td>
                                        <Link :href="route('domains.show', domain.id)" class="btn btn-xs btn-ghost capitalize tooltip" data-tip="Manage subscription">
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
            </div>
        </div>

        <!-- Start: New Domain -->
        <!-- Step 1 -->
        <TransitionRoot as="template" :show="newDomainProcess.active && newDomainProcess.plan_id == null">
            <Dialog as="div" class="relative z-10" @close="resetNewDomainProcess">
            <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 z-10 overflow-y-auto">
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
                                            <button type="submit" title="Submit" @click="newDomainProcess.plan_id = plan.id" class="block w-full py-3 px-6 text-center rounded-xl transition bg-purple-600 hover:bg-purple-700 active:bg-purple-800 focus:bg-indigo-600 group-hover:scale-105 lg:group-hover:scale-110">
                                                <span class="text-white font-semibold">
                                                    Choose
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
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

        <!-- Step 2 -->
        <TransitionRoot as="template" :show="newDomainProcess.active && newDomainProcess.plan_id != null">
            <Dialog as="div" class="relative z-10" @close="resetNewDomainProcess">
            <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 z-10 overflow-y-auto">
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
                            <input
                                type="text"
                                id="domain"
                                name="domain"
                                v-model="newDomainProcess.name"
                                class="block w-full flex-1 rounded border-0 ring-1 ring-inset ring-gray-300"
                                placeholder="example.com"
                                @input="validateNewDomain"
                            />
                            <small id="domain-error" class="text-red-500 hidden"></small>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="button" id="subscribe-new-domain" class="inline-flex w-full justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 sm:ml-3 sm:w-auto disabled:opacity-25" @click="subscribeDomain" disabled>Subscribe</button>
                            <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" @click="resetNewDomainProcess" ref="cancelButtonRef">Cancel</button>
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
