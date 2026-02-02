<script setup>
import AdminLayout from '@/App/Layouts/AdminLayout.vue'
import { Head, Link } from '@inertiajs/inertia-vue3';
import MembersList from '@/Components/Members.vue'
import moment from 'moment'
import { ref, onMounted } from 'vue'

const props = defineProps({
    client: Object
});
var searchVal = ''
var domains = new Object
var data = {
    links: [{}]
}
const renderComponent = ref(true);
const selectedItemsPerPage = ref(10);
const pageNumber = ref(1);
var sort = 'asc'
var orderBy = 'id'
const clientID = props.client.id;

onMounted(() => {
    getDomains()
});

const getDomains = () => {
    const params = {
        user_id: props.client.id,
        search: searchVal,
        page: pageNumber.value,
        limit: selectedItemsPerPage.value,
        sort: sort,
        orderBy: orderBy
    };
    // Set renderComponent.value to false
    renderComponent.value = false;

    window.axios.get('/admin/clients/domain/list', { params }).then(async res => {
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

const searchMember = () => {
    pageNumber.value = 1
    getMembers()
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
</script>

<template>
    <Head title="Profile" />

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profile</h2>
        </template>

        <div class="py-12">
            <div class="max-w-8xl px-6 space-y-5">
                <div>
                    <h1 class="text-3xl font-bold blue-text">Client's name : {{ client.name }}</h1>
                </div>
                
                <div class="overflow-hidden shadow-lg my-5 rounded-lg">
                    <div class="flex flex-wrap lg:flex-wrap items-center py-3 lg:p-3 space-y-2 lg:space-y-0">
                        <div class="w-2/3">
                            <h3 class="text-xl font-bold">List of domains</h3>
                        </div>
                        <div class="relative my-4 px-4 flex w-full flex-wrap items-stretch xl:w-1/3">
                            <input
                            type="search"
                            class="relative m-0 -mr-0.5 block w-[1px] min-w-0 flex-auto rounded-l border border-gray-300 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-neutral-700 outline-none transition duration-200 ease-in-out focus:z-[3] focus:ring-blue-500 focus:border-blue-500 block float-right dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Search domain name"
                            aria-label="Search"
                            aria-describedby="button-addon1"
                            v-model="searchVal"
                            @keyup.enter="searchDomain()"/>

                            <!--Search button-->
                            <button
                                class="relative z-[2] flex items-center rounded-r bg-primary px-6 py-2.5 text-xs font-medium uppercase leading-tight text-white shadow-md transition duration-150 ease-in-out hover:bg-primary-700 hover:shadow-lg focus:bg-primary-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-primary-800 active:shadow-lg"
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
                    </div>
                    <div class="overflow-x-auto">
                        <table v-if="renderComponent" class="table table-zebra w-full">
                                <!-- head -->
                                <thead>
                                    <tr>
                                        <th>
                                            <span class="flex justify-between items-center">
                                                Domain Name
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
                                        <th colspan="6" class="italic font-normal text-center">Empty</th>
                                    </tr>
                                    <!-- row 1 -->
                                    <tr v-else v-for="(domain, index) in domains" :key="domain.id">
                                        <!-- <th>{{ client.id }}</th> -->
                                        <th>{{ domain.name }}</th>
                                        <td>{{ domain.product ? domain.product.name : '' }}</td>
                                        <td>
                                            <span v-if="domain.next_payment_at">{{ fromNow(domain.next_payment_at) }} {{ $page.props.timezone }}</span>
                                        </td>
                                        <td>
                                            <div
                                                :class="`${getStatusBadgeColor(domain.subscription.stripe_status)}`"
                                                v-if="domain.subscription">
                                                    <span>{{ domain.subscription.stripe_status }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            {{ moment(domain.created_at).format('LL') }}
                                        </td>
                                        <td>
                                            <Link :href="route('admin.clients.domain', domain.id)" class="show-action-button hover:bg-teal-600/10 text-teal-600 capitalize tooltip" data-tip="Manage subscription">
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

                    <div class="flex flex-col xl:flex-row justify-between items-center py-3 xl:p-3 space-y-2 xl:space-y-0">
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
                                <button v-if="data.prev_page_url" @click="paginate(Number(data.current_page) - 1)" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                    <span class="sr-only">Previous</span>
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <button v-if="data.prev_page_url" @click="paginate(Number(data.current_page) + 1)" :href="data.links[data.links.length - 1].url" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
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
                                    class="cursor-pointer flex items-center rounded-r bg-blue-500 p-2 text-white transition duration-150 ease-in-out"
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
            <div class="text-center">
                <small> Page {{ data.current_page }} / {{ data.last_page }} </small>
            </div>
            <br>
            <hr>
            <MembersList :clientId=clientID />
        </div>
    </AdminLayout>
</template>

<style scoped>
    .sidebar-profile {
        color: #02C9B2;
        fill: #02C9B2;
    }

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

    .member-active-badge {
        border-radius: 8px;
        border: 1px solid #00B087;
        background: rgba(26, 210, 199, 0.20);
        width: fit-content;
        padding: 4px 20px;
        justify-content: center;
        align-items: center;
        color: #00B087;
    }
</style>
