<script setup>
import AuthenticatedLayout from '@/App/Layouts/AuthenticatedLayout.vue';
import CreateAccountForm from './Partials/CreateAccountForm.vue'
import { useForm } from '@inertiajs/inertia-vue3';
import { inject, ref, reactive } from 'vue'
const swal = inject('$swal')

const props = defineProps({
    members: Object,
    status: String,
});

let members = ref(props.members);
const pageNumber = ref ('');

const form = useForm({})

const url = new URL(window.location.href); // Get the current URL

const searchParams = new URLSearchParams(url.search); // Create a new URLSearchParams object based on the current URL's search parameters

// On the first load of page, it will check if there are current URL parameter of status
const selectedStatus = ref(searchParams.get('status') == 1 || searchParams.get('status') == 0 ? searchParams.get('status') : '');
const selectedItemsPerPage = ref(searchParams.get('size') || 10); // Set the number of items per page to 10
const sort = ref(searchParams.get('sort') || 'desc'); // Set the order direction, default to descending
const orderBy = ref(searchParams.get('orderBy') || 'created_at'); // Set the order, default to creation date

const deleteMember = (member_id) => {
    swal.fire({
        title: 'Are you sure you want to delete this team member?',
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
            form.delete(route('accounts.member.destroy', member_id), {
                preserveScroll: true,
                onSuccess: () => {
                    swal({
                        title: "Success!",
                        text: "The team member has been successfully deleted!",
                        type: "success",
                        confirmButtonColor:"#1ad2c7",
                    }).then(function() {
                        location.reload();
                    });
                },
            });
        }
    })
}



const updateMemberStatus = (member_id, status) => {
    swal.fire({
        title: 'Are you sure you want to change the status of this member?',
        text: `You can change this anytime.`,
        icon: 'question',
        iconColor: '#1ad2c7',
        showCancelButton: true,
        confirmButtonText: 'Yes, change it!',
        cancelButtonText: 'Cancel',
        confirmButtonColor:"#1ad2c7",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            var updateURL = '/accounts/member/'+ member_id + '/' + status;
            form.patch(updateURL, {
                preserveScroll: true,
                onSuccess: () => {
                    swal({
                        title: "Success!",
                        text: "The status of a team member has been successfully updated!",
                        type: "success",
                        confirmButtonColor:"#1ad2c7",
                    }).then(function() {
                        location.reload();
                    });
                },
            });
        }
    })
}

const filterMemberByStatus = () => {
    // Set or delete a parameter value
    if (selectedStatus.value == 1 || selectedStatus.value == 0) {
        searchParams.set('status', selectedStatus.value); //Filter base on status
        searchParams.set('page', '1'); // Return to page 1
    } else {
        searchParams.delete('status');
    }
    updateURL()
}

const setItemsPerPage = () => {
    if (selectedItemsPerPage.value !== '') {
        searchParams.set('page', '1'); // Return to page 1
        searchParams.set('size', selectedItemsPerPage.value); // Set the link in relation to the selected value for items per page
        props.members.per_page = selectedItemsPerPage.value; // Set the number of items per page in the frontend
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
    
    // updateOrderBy()
}

const descendingData = () => {
    searchParams.set('sort', 'desc'); // Return to page 1
    
    // updateOrderBy()
}

const updateURL = () => {
    const newSearchParamsString = searchParams.toString(); // Convert the updated search parameters back to a string
    url.search = newSearchParamsString; // Update the URL with the modified search parameters
    window.location.href = url.toString(); // Reload the page with the updated URL
}

</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Account</h2>
        </template>
        <div class="p-5 pt-6 lg:py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="bg-white rounded-2xl blue-shadow overflow-hidden">
                    <CreateAccountForm :status="status"/>
                </div>
                <div class="bg-white rounded-2xl blue-shadow">
                    <header>
                        <div class="flex flex-col lg:flex-row justify-between items-center p-8 pb-3 space-y-4 lg:px-5 lg:pt-10 lg:space-y-0 xl:px-10">
                            <div>
                                <h2 class="flex text-3xl font-semibold text-gray-900">Team Members</h2>
                            </div>
                           
                            <div>
                                <select v-model="selectedStatus" @change="filterMemberByStatus()" id="member-status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-44 float-right p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="">All Members</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>

                    </header>
                    <div class="overflow-x-scroll mt-4 lg:mt-8">
                        <table class="table w-full">
                            <thead>
                                <tr class="text-white">
                                    <th class="lg:pl-6 xl:pl-12">#</th>
                                    <th class="w-80">
                                        <span class="flex justify-between items-center">
                                            Name
                                            <div @click="updateOrderBy('name')">
                                                <svg @click="ascendingData()" class="cursor-pointer" width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'asc' && orderBy === 'name' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6.62917 3.5C7.01407 2.83333 7.97632 2.83333 8.36122 3.5L13.1244 11.75C13.5093 12.4167 13.0281 13.25 12.2583 13.25L2.73205 13.25C1.96225 13.25 1.48113 12.4167 1.86603 11.75L6.62917 3.5Z"/>
                                                </svg>
                                                <svg @click="descendingData()" class="cursor-pointer"  width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'desc' && orderBy === 'name' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8.37083 11.5C7.98593 12.1667 7.02368 12.1667 6.63878 11.5L1.87564 3.25C1.49074 2.58333 1.97187 1.75 2.74167 1.75L12.2679 1.75C13.0377 1.75 13.5189 2.58333 13.134 3.25L8.37083 11.5Z"/>
                                                </svg>
                                            </div>
                                        </span>
                                    </th>
                                    <th class="w-80">
                                        <span class="flex justify-between items-center">
                                            Email
                                            <div @click="updateOrderBy('email')">
                                                <svg @click="ascendingData()" class="cursor-pointer" width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'asc' && orderBy === 'email' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6.62917 3.5C7.01407 2.83333 7.97632 2.83333 8.36122 3.5L13.1244 11.75C13.5093 12.4167 13.0281 13.25 12.2583 13.25L2.73205 13.25C1.96225 13.25 1.48113 12.4167 1.86603 11.75L6.62917 3.5Z"/>
                                                </svg>
                                                <svg @click="descendingData()" class="cursor-pointer"  width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'desc' && orderBy === 'email' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8.37083 11.5C7.98593 12.1667 7.02368 12.1667 6.63878 11.5L1.87564 3.25C1.49074 2.58333 1.97187 1.75 2.74167 1.75L12.2679 1.75C13.0377 1.75 13.5189 2.58333 13.134 3.25L8.37083 11.5Z"/>
                                                </svg>
                                            </div>
                                        </span>
                                    </th>
                                    <th>
                                        <span class="flex justify-between items-center">
                                            Role
                                            <div @click="updateOrderBy('type')">
                                                <svg @click="ascendingData()" class="cursor-pointer" width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'asc' && orderBy === 'type' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M6.62917 3.5C7.01407 2.83333 7.97632 2.83333 8.36122 3.5L13.1244 11.75C13.5093 12.4167 13.0281 13.25 12.2583 13.25L2.73205 13.25C1.96225 13.25 1.48113 12.4167 1.86603 11.75L6.62917 3.5Z"/>
                                                </svg>
                                                <svg @click="descendingData()" class="cursor-pointer"  width="12" height="12" viewBox="0 0 15 15" :fill="sort === 'desc' && orderBy === 'type' ? 'black' : 'white'" xmlns="http://www.w3.org/2000/svg">
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
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="members.length < 1">
                                    <th colspan="7" class="italic font-normal text-center">Empty</th>
                                </tr>
                                <tr v-for="(member, index) in members.data" :key="index">
                                    <th class="lg:pl-6 xl:pl-12">{{ (members.current_page - 1) * members.per_page + index + 1 }}</th>
                                    <td class="py-8 font-semibold">{{ member.name }}</td>
                                    <td>{{ member.email }}</td>
                                    <td class="capitalize">
                                        <!-- <div :class="`badge paid`"> -->
                                            <span>{{ member.type }}</span>
                                        <!-- </div> -->
                                    </td>
                                    <td>
                                        <div>
                                            <span :class="member.status === 1 ? 'active-badge' : 'inactive-badge'">{{ member.status === 1 ? 'Active' : 'Inactive' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            <button class="swap-action-button mr-1 hover:bg-teal-600/10" @click="updateMemberStatus(member.id, member.status)" style="font-size: 0.5rem" title="Change Status">
                                                <svg width="17" height="13" viewBox="0 0 17 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M17 3.71429L13.2222 0V2.78571H6.61111V4.64286H13.2222V7.42857M3.77778 5.57143L0 9.28571L3.77778 13V10.2143H10.3889V8.35714H3.77778V5.57143Z" fill="#008767"/>
                                                </svg>

                                            </button>
                                            <button class="delete-action-button hover:bg-red-600/10" @click="deleteMember(member.id)" style="font-size: 0.5rem" title="Delete Member">
                                                <svg width="13" height="17" viewBox="0 0 13 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M13 0.944444H9.75L8.82143 0H4.17857L3.25 0.944444H0V2.83333H13M0.928571 15.1111C0.928571 15.6121 1.12423 16.0925 1.47252 16.4468C1.8208 16.801 2.29317 17 2.78571 17H10.2143C10.7068 17 11.1792 16.801 11.5275 16.4468C11.8758 16.0925 12.0714 15.6121 12.0714 15.1111V3.77778H0.928571V15.1111Z" fill="#DF0404"/>
                                                </svg>
                                            </button>
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
                                <span class="font-medium font-semibold"> {{ members.from }} </span>
                                to
                                <span class="font-medium font-semibold"> {{ members.to }}</span>
                                of
                                <span class="font-medium font-semibold"> {{ members.total }} </span>
                                results
                            </p>
                        </div>
                        <div class="hidden md:block">
                            <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                                <a v-if="members.links[0].url" :href="`${members.links[0].url}&status=${selectedStatus}&size=${selectedItemsPerPage}`" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                    <span class="sr-only">Previous</span>
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                <!-- Loop through the links -->
                                <template v-for="(link, index) in members.links">
                                    <a
                                        v-if="index !== 0 && index !== members.links.length - 1"
                                        :href="`${link.url}&status=${selectedStatus}&size=${selectedItemsPerPage}`"
                                        :key="`${link.url}&status=${selectedStatus}`"
                                        v-html="link.label"
                                        :aria-current="link.active ? 'page' : null"
                                        class="relative inline-flex items-center px-4 py-2 text-sm font-semibold"
                                        :class="[
                                            'text-gray-900 ring-1 ring-inset ring-gray-300 focus:z-20 focus:outline-offset-0',
                                            { 'bg-indigo-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600': link.active }
                                        ]"
                                    ></a>
                                    
                                    <span v-else-if="index === members.links.length - 2 && members.links.length > 3">...</span>

                                </template>

                                <a v-if="members.links[members.links.length - 1].url" :href="`${members.links[members.links.length - 1].url}&status=${selectedStatus}&size=${selectedItemsPerPage}`" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
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
                                <a v-if="members.links[0].url" :href="`${members.links[0].url}&status=${selectedStatus}`" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                    <span class="sr-only">Previous</span>
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                <a v-if="members.links[members.links.length - 1].url" :href="`${members.links[members.links.length - 1].url}&status=${selectedStatus}`" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                    <span class="sr-only">Next</span>
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </div>

                            <div v-if="members.total > 10" class="flex w-full flex-wrap items-stretch">
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
                                    :href="`/accounts?page=${pageNumber}&status=${selectedStatus}`">
                                    
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
                <div v-if="members.total > 0" class="my-4 text-center">
                    <small> Page {{ members.current_page }} / {{ members.last_page }} </small> 
                </div>
            </div> 
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
    .active-badge {
        border-radius: 8px;
        border: 1px solid #00B087;
        background: rgba(26, 210, 199, 0.20);
        width: fit-content;
        padding: 4px 20px;
        justify-content: center;
        align-items: center;
        color: #00B087;
    }
    .blue-shadow {
        box-shadow: 0 0 60px rgba(226, 236, 249, 0.5);
    }
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
    .inactive-badge {
        border-radius: 8px;
        border: 1px solid #DF0404;
        background: rgba(255, 197, 197, 0.30);
        width: fit-content;
        padding: 4px 15px;
        justify-content: center;
        align-items: center;
        color: #DF0404;
    }
    .swap-action-button {
        cursor: pointer;
        border-radius: 6px;
        border: 1px solid #00B087;
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