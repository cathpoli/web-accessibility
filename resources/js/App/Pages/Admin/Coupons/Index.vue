<script setup>
import AdminLayout from '@/App/Layouts/AdminLayout.vue'
import { Head } from '@inertiajs/inertia-vue3';
import { inject } from 'vue'
import { useNotificationStore } from '@dafcoe/vue-notification'
import { ref, computed, onMounted } from 'vue';

const swal = inject('$swal')
const { setNotification } = useNotificationStore()

const renderComponent = ref(true);
const selectedItemsPerPage = ref('10');
const pageNumber = ref('');

const url = new URL(window.location.href); // Get the current URL
const searchParams = new URLSearchParams(url.search); // Create a new URLSearchParams object based on the current URL's search parameters

var search = ''
var page = 1
var items = ''
var sort = 'desc'
var orderBy = 'created_at'
var coupons = new Object
var data = {
    links: [{}]
}

onMounted(() => {
    getData()
});

const getData = () => {
    const params = {
        search: search,
        page: page,
        items: selectedItemsPerPage.value,
        sort: sort,
        orderBy: orderBy
    };
    console.log(params);
    // Set renderComponent.value to false
    renderComponent.value = false;
    window.axios.get('/admin/coupons/list', { params }).then(async res => {
        data = res.data;
        coupons = res.data.data;
        // Create a new URL object with the current URL
        const url = new URL(window.location.href);
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
    getData()
}
const triggerSearch = () => {
    page = 1
    getData()
}
// setting items per page and returning to the first page
const setItemsPerPage = () => {
    page = 1
    getData()
};
// for mobile page searching
const searchPage = () => {
    page = page
    getData()
}

const ascendingData = () => {
    sort = 'asc'; // lowest to highest
    page = 1
    
    this.updateOrderBy()
    triggerSearch()
    getData();
    
}
const descendingData = () => {
    sort = 'desc'; // lowest to highest
    page = 1

    this.updateOrderBy()
    triggerSearch()
    getData();
    
}
const updateOrderBy = (order) => {
    
    orderBy = order;
    getData();
}

const deleteCoupon = (coupon_id) => {
    swal.fire({
        title: 'Are you sure you want to delete this entry?',
        text: `You won't be able to revert this!`,
        icon: 'warning',
        iconColor: '#DF0404',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        confirmButtonColor:"#DF0404",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(route('admin.coupons.destroy', coupon_id))
            .then((res) => {
                // props.coupons = res.data.coupons
                setNotification({type: 'success', message: 'Successfully updated default payment method.'})
                window.location.reload()
            }).catch((err) => {
                setNotification({type: 'success', message: 'Something went wrong.'})
            })
        }
    })
}

</script>

<template>
    <Head title="Coupons" />

    <AdminLayout>
        <div class="py-12">
            <div class="max-w-8xl px-6">
                <div class="flex justify-between items-center mb-2">
                    <div>
                        <h1 class="text-3xl font-bold blue-text">Coupons</h1>
                    </div>
                    <a :href="route('admin.coupons.create')" class="btn teal-btn capitalize gap-1">
                        <svg class="hidden md:block" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24"><title>ticket-percent</title><path d="M4,4A2,2 0 0,0 2,6V10C3.11,10 4,10.9 4,12A2,2 0 0,1 2,14V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V14A2,2 0 0,1 20,12C20,10.89 20.9,10 22,10V6C22,4.89 21.1,4 20,4H4M15.5,7L17,8.5L8.5,17L7,15.5L15.5,7M8.81,7.04C9.79,7.04 10.58,7.83 10.58,8.81A1.77,1.77 0 0,1 8.81,10.58C7.83,10.58 7.04,9.79 7.04,8.81A1.77,1.77 0 0,1 8.81,7.04M15.19,13.42C16.17,13.42 16.96,14.21 16.96,15.19A1.77,1.77 0 0,1 15.19,16.96C14.21,16.96 13.42,16.17 13.42,15.19A1.77,1.77 0 0,1 15.19,13.42Z" /></svg>
                        New <span class="hidden md:block">Coupon</span>
                        <svg class="block md:hidden" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24"><title>ticket-percent</title><path d="M4,4A2,2 0 0,0 2,6V10C3.11,10 4,10.9 4,12A2,2 0 0,1 2,14V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V14A2,2 0 0,1 20,12C20,10.89 20.9,10 22,10V6C22,4.89 21.1,4 20,4H4M15.5,7L17,8.5L8.5,17L7,15.5L15.5,7M8.81,7.04C9.79,7.04 10.58,7.83 10.58,8.81A1.77,1.77 0 0,1 8.81,10.58C7.83,10.58 7.04,9.79 7.04,8.81A1.77,1.77 0 0,1 8.81,7.04M15.19,13.42C16.17,13.42 16.96,14.21 16.96,15.19A1.77,1.77 0 0,1 15.19,16.96C14.21,16.96 13.42,16.17 13.42,15.19A1.77,1.77 0 0,1 15.19,13.42Z" /></svg>
                    </a>
                </div>
                <div class="overflow-hidden shadow-lg my-5 rounded-lg">
                    <div class="flex flex-col lg:flex-row justify-end items-center py-3 lg:p-3 space-y-2 lg:space-y-0">
                        <div class="relative my-4 px-4 flex w-full flex-wrap items-stretch xl:w-1/3">
                            <input
                            type="search"
                            class="relative m-0 -mr-0.5 block w-[1px] min-w-0 flex-auto rounded-l border border-gray-300 bg-transparent bg-clip-padding px-3 py-[0.25rem] text-base font-normal leading-[1.6] text-neutral-700 outline-none transition duration-200 ease-in-out focus:z-[3] focus:ring-blue-500 focus:border-blue-500 block float-right dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Search a code, name, discount, duration or times redeemed"
                            aria-label="Search"
                            aria-describedby="button-addon1"
                            v-model="search"
                            @keyup.enter="triggerSearch()"/>
                            <!--Search button-->
                            <button
                                class="relative z-[2] flex items-center rounded-r bg-primary px-6 py-2.5 text-xs font-medium uppercase leading-tight text-white shadow-md transition duration-150 ease-in-out hover:bg-primary-700 hover:shadow-lg focus:bg-primary-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-primary-800 active:shadow-lg"
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
                    <div class="overflow-x-auto">
                        <table v-if="renderComponent" class="table table-zebra w-full" ref="table">
                            <!-- head -->
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>
                                        <span class="flex justify-between items-center">
                                            Code
                                            <div @click="updateOrderBy('code')">
                                                <svg @click="ascendingData()" class="cursor-pointer" width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'asc' && orderBy === 'code' ? 'black' : 'gray'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6.62917 3.5C7.01407 2.83333 7.97632 2.83333 8.36122 3.5L13.1244 11.75C13.5093 12.4167 13.0281 13.25 12.2583 13.25L2.73205 13.25C1.96225 13.25 1.48113 12.4167 1.86603 11.75L6.62917 3.5Z"/>
                                                </svg>
                                                <svg @click="descendingData()" class="cursor-pointer"  width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'desc' && orderBy === 'code' ? 'black' : 'gray'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8.37083 11.5C7.98593 12.1667 7.02368 12.1667 6.63878 11.5L1.87564 3.25C1.49074 2.58333 1.97187 1.75 2.74167 1.75L12.2679 1.75C13.0377 1.75 13.5189 2.58333 13.134 3.25L8.37083 11.5Z"/>
                                                </svg>
                                            </div>
                                        </span>
                                    </th>
                                    <th>
                                        <span class="flex justify-between items-center">
                                            Name
                                            <div @click="updateOrderBy('name')">
                                                <svg @click="ascendingData()" class="cursor-pointer" width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'asc' && orderBy === 'name' ? 'black' : 'gray'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6.62917 3.5C7.01407 2.83333 7.97632 2.83333 8.36122 3.5L13.1244 11.75C13.5093 12.4167 13.0281 13.25 12.2583 13.25L2.73205 13.25C1.96225 13.25 1.48113 12.4167 1.86603 11.75L6.62917 3.5Z"/>
                                                </svg>
                                                <svg @click="descendingData()" class="cursor-pointer"  width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'desc' && orderBy === 'name' ? 'black' : 'gray'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8.37083 11.5C7.98593 12.1667 7.02368 12.1667 6.63878 11.5L1.87564 3.25C1.49074 2.58333 1.97187 1.75 2.74167 1.75L12.2679 1.75C13.0377 1.75 13.5189 2.58333 13.134 3.25L8.37083 11.5Z"/>
                                                </svg>
                                            </div>
                                        </span>
                                    </th>
                                    <th>
                                        <span class="flex justify-between items-center">
                                            Discount
                                            <div @click="updateOrderBy('type')">
                                                <svg @click="ascendingData()" class="cursor-pointer" width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'asc' && orderBy === 'type' ? 'black' : 'gray'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6.62917 3.5C7.01407 2.83333 7.97632 2.83333 8.36122 3.5L13.1244 11.75C13.5093 12.4167 13.0281 13.25 12.2583 13.25L2.73205 13.25C1.96225 13.25 1.48113 12.4167 1.86603 11.75L6.62917 3.5Z"/>
                                                </svg>
                                                <svg @click="descendingData()" class="cursor-pointer"  width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'desc' && orderBy === 'type' ? 'black' : 'gray'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8.37083 11.5C7.98593 12.1667 7.02368 12.1667 6.63878 11.5L1.87564 3.25C1.49074 2.58333 1.97187 1.75 2.74167 1.75L12.2679 1.75C13.0377 1.75 13.5189 2.58333 13.134 3.25L8.37083 11.5Z"/>
                                                </svg>
                                            </div>
                                        </span>
                                    </th>
                                    <th>
                                        <span class="flex justify-between items-center">
                                            Duration
                                            <div @click="updateOrderBy('duration')">
                                                <svg @click="ascendingData()" class="cursor-pointer" width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'asc' && orderBy === 'duration' ? 'black' : 'gray'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6.62917 3.5C7.01407 2.83333 7.97632 2.83333 8.36122 3.5L13.1244 11.75C13.5093 12.4167 13.0281 13.25 12.2583 13.25L2.73205 13.25C1.96225 13.25 1.48113 12.4167 1.86603 11.75L6.62917 3.5Z"/>
                                                </svg>
                                                <svg @click="descendingData()" class="cursor-pointer"  width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'desc' && orderBy === 'duration' ? 'black' : 'gray'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8.37083 11.5C7.98593 12.1667 7.02368 12.1667 6.63878 11.5L1.87564 3.25C1.49074 2.58333 1.97187 1.75 2.74167 1.75L12.2679 1.75C13.0377 1.75 13.5189 2.58333 13.134 3.25L8.37083 11.5Z"/>
                                                </svg>
                                            </div>
                                        </span>
                                    </th>
                                    <th>
                                        <span class="flex justify-between items-center">
                                            Times Redeemed
                                            <div @click="updateOrderBy('times_redeemed')">
                                                <svg @click="ascendingData()" class="cursor-pointer" width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'asc' && orderBy === 'times_redeemed' ? 'black' : 'gray'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6.62917 3.5C7.01407 2.83333 7.97632 2.83333 8.36122 3.5L13.1244 11.75C13.5093 12.4167 13.0281 13.25 12.2583 13.25L2.73205 13.25C1.96225 13.25 1.48113 12.4167 1.86603 11.75L6.62917 3.5Z"/>
                                                </svg>
                                                <svg @click="descendingData()" class="cursor-pointer"  width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'desc' && orderBy === 'times_redeemed' ? 'black' : 'gray'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8.37083 11.5C7.98593 12.1667 7.02368 12.1667 6.63878 11.5L1.87564 3.25C1.49074 2.58333 1.97187 1.75 2.74167 1.75L12.2679 1.75C13.0377 1.75 13.5189 2.58333 13.134 3.25L8.37083 11.5Z"/>
                                                </svg>
                                            </div>
                                        </span>
                                    </th>
                                    <th>
                                        <span class="flex justify-between items-center">
                                            Applicable Products
                                            <div @click="updateOrderBy('product_name')">
                                                <svg @click="ascendingData()" class="cursor-pointer" width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'asc' && orderBy === 'product_name' ? 'black' : 'gray'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6.62917 3.5C7.01407 2.83333 7.97632 2.83333 8.36122 3.5L13.1244 11.75C13.5093 12.4167 13.0281 13.25 12.2583 13.25L2.73205 13.25C1.96225 13.25 1.48113 12.4167 1.86603 11.75L6.62917 3.5Z"/>
                                                </svg>
                                                <svg @click="descendingData()" class="cursor-pointer"  width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'desc' && orderBy === 'product_name' ? 'black' : 'gray'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8.37083 11.5C7.98593 12.1667 7.02368 12.1667 6.63878 11.5L1.87564 3.25C1.49074 2.58333 1.97187 1.75 2.74167 1.75L12.2679 1.75C13.0377 1.75 13.5189 2.58333 13.134 3.25L8.37083 11.5Z"/>
                                                </svg>
                                            </div>
                                        </span>
                                    </th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="coupons.length < 1">
                                    <th colspan="8" class="italic font-normal text-center">Empty</th>
                                </tr>
                                <tr v-else v-for="(coupon, index) in coupons" :key="coupons.id">
                                    <th>{{ data.from + index }}</th>
                                    <th>{{ coupon.code }}</th>
                                    <th>{{ coupon.name }}</th>
                                    <td>
                                        <span v-if="coupon.type === 'percent_off'">{{ coupon.percent_off }} %</span>
                                        <span v-if="coupon.type === 'amount_off'">$ {{ coupon.amount_off }}</span>
                                        <span v-if="coupon.type === 'period_off'">{{ coupon.period_off }} months</span>
                                    </td>
                                    <td>{{ coupon.duration }}</td>
                                    <td>{{ coupon.times_redeemed }}</td>
                                    <td v-if="coupon.type === 'period_off'">
                                        <span> Annual products </span>
                                    </td>
                                    <td v-else-if="coupon.products.length > 0">
                                        <span v-for="(product, index) in coupon.products" :key="index"> {{ product.name }} - ${{ product.price }} / {{ product.interval }} </span>
                                    </td>
                                    <td v-else>
                                        <span> All </span>
                                    </td>
                                    <td class="flex gap-1">
                                        <!-- <button class="btn btn-sm btn-active">Edit</button> -->
                                        <button class="delete-action-button hover:bg-red-600/10" @click="deleteCoupon(coupon.id)" style="font-size: 0.5rem" title="Delete Coupon">
                                            <svg width="13" height="17" viewBox="0 0 13 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M13 0.944444H9.75L8.82143 0H4.17857L3.25 0.944444H0V2.83333H13M0.928571 15.1111C0.928571 15.6121 1.12423 16.0925 1.47252 16.4468C1.8208 16.801 2.29317 17 2.78571 17H10.2143C10.7068 17 11.1792 16.801 11.5275 16.4468C11.8758 16.0925 12.0714 15.6121 12.0714 15.1111V3.77778H0.928571V15.1111Z" fill="#DF0404"/>
                                            </svg>
                                        </button>
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
                                        :href="link.url"
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
                <div class="text-center">
                    <small> Page {{ data.current_page }} / {{ data.last_page }} </small> 
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style>
    .delete-action-button {
        border-radius: 8px;
        border: 1px solid #DF0404;
        background: white;
        width: fit-content;
        height: 32px;
        padding: 4px 10px;
        justify-content: center;
        align-items: center;
        color: #DF0404;
    }
    .sidebar-coupons {
        color: #02C9B2;
        fill: #02C9B2;
    }
    .table :where(thead, tfoot) :where(th, td) {
        --tw-bg-opacity: 1;
        background-color: rgb(241,247,254);
        font-size: 0.75rem;
        line-height: 1rem;
        font-weight: 700;
        text-transform: uppercase;
    }
    .table-zebra tbody tr:nth-child(even) th, .table-zebra tbody tr:nth-child(even) td {
        --tw-bg-opacity: 1;
        background-color: rgb(241,247,254);
    }
    .text-white {
        color: white !important;
    }
</style>
