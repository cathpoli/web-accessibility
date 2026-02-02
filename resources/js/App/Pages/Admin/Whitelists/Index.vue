<script setup>
import AdminLayout from '@/App/Layouts/AdminLayout.vue'
import { Head, useForm } from '@inertiajs/inertia-vue3';
import { ref, inject } from 'vue';
import "highlight.js/lib/common";
import hljs from "highlight.js/lib/core";
import useClipboard from "vue-clipboard3";
import { useNotificationStore } from "@dafcoe/vue-notification";

const swal = inject('$swal')
const { setNotification } = useNotificationStore();
const { toClipboard } = useClipboard();

const props = defineProps({
    url: String,
    whitelists: Object,
});

const pageNumber = ref('');
const form = useForm({});

const scriptElement = "script";
const script = `<${scriptElement}> (function() {var s = document.createElement('script');var h = document.querySelector('head') || document.body;s.setAttribute('aceada-script', '');s.src = '${props.url}/dist/aceada.js';h.appendChild(s);})(); </${scriptElement}>`

const copyScript = async () => {
    try {
        await toClipboard(`${script}`);
        // toast.success(`Script copied to clipboard`)
        setNotification({
            type: "success",
            message: `Script copied to clipboard.`,
        });
    } catch (e) {
        console.error(e);
    }
}

const remove = (id) => {
    swal.fire({
        title: 'Are you sure?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(`/admin/whitelists/${id}`, {
                onSuccess: (res) => {
                    setNotification({type: 'success', message: 'Successfull removed from whitelists.'})
                },
                onError: (err) => {
                    setNotification({type: 'warning', message: 'Something went wrong.'})
                }
            });
        }
    })
}

</script>

<template>
    <Head title="Whitelists" />

    <AdminLayout>
        <div class="py-12">
            <div class="max-w-8xl px-0 md:px-6">
                <div class="flex justify-between items-center mb-2">
                    <div>
                        <h1 class="text-3xl font-bold blue-text">Whitelists</h1>
                    </div>
                    <a :href="route('admin.whitelists.create')" class="btn teal-btn capitalize gap-1">
                        <svg class="hidden md:block" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M140-160q-24 0-42-18t-18-42v-520q0-24 18-42t42-18h680q24 0 42 18t18 42v520q0 24-18 42t-42 18H140Zm0-496h680v-84H140v84Zm298 344 212-212-44-44-168 168-84-84-44 44 128 128Z"/></svg>
                        New <span class="hidden md:block">Whitelist</span>
                        <svg class="block md:hidden" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M140-160q-24 0-42-18t-18-42v-520q0-24 18-42t42-18h680q24 0 42 18t18 42v520q0 24-18 42t-42 18H140Zm0-496h680v-84H140v84Zm298 344 212-212-44-44-168 168-84-84-44 44 128 128Z"/></svg>
                    </a>
                </div>

                <div class="p-6 bg-white rounded-lg">
                    <h1 class="pb-0 font-bold text-xl">Installation Guide</h1>
                    <div>
                        <p class="pt-3 pb-5">
                            Whitelisted domains <strong> do not need subscription </strong> to use our widget.
                            Simply copy the installation code below and add it inside the body tag of the website just before the end tag.
                        </p>
                        <div class="flex justify-start gap-4 text-gray-900">
                            <button
                                class="btn btn-ghost teal-border capitalize flex-none"
                                @click="copyScript"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="w-6 h-6"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184"
                                    />
                                </svg>
                                <span class="hidden lg:block">Copy Script</span>
                            </button>
                            <highlightjs class="flex-1 flex overflow-auto w-0 max-w-[100%]" autodetect :code="script" />
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden shadow-lg my-5 rounded-none md:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="table table-zebra w-full">
                                <!-- head -->
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Domain</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- row 1 -->
                                    <tr v-for="(whitelist, index) in whitelists.data" :key="whitelist.id">
                                        <td>{{ whitelists.from + index }}</td>
                                        <td>{{ whitelist.name }}</td>
                                        <td>
                                            <div class="flex justify-content-start gap-4">
                                                <a :href="`/admin/whitelists/${whitelist.id}/installation`" class="btn btn-xs capitalize text-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                                        <path fill="#ffffff" d="M480.08-734q-30.08 0-51.58-21.42-21.5-21.421-21.5-51.5 0-30.08 21.42-51.58 21.421-21.5 51.5-21.5 30.08 0 51.58 21.42 21.5 21.421 21.5 51.5 0 30.08-21.42 51.58-21.421 21.5-51.5 21.5ZM372-80v-533H120v-60h720v60H588v533h-60v-260h-96v260h-60Z"/>
                                                    </svg>
                                                    Widget
                                                </a>
                                                <button class="btn btn-xs bg-red-600 border-red-600 capitalize text-white" @click="remove(whitelist.id)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20">
                                                        <path fill="#ffffff" d="M312-144q-29.7 0-50.85-21.15Q240-186.3 240-216v-480h-48v-72h192v-48h192v48h192v72h-48v479.566Q720-186 698.85-165 677.7-144 648-144H312Zm72-144h72v-336h-72v336Zm120 0h72v-336h-72v336Z"/>
                                                    </svg>
                                                    Remove
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                        </table>

                    </div>
                    <div class="flex flex-col lg:flex-row justify-between items-center py-3 lg:p-3 space-y-2 lg:space-y-0">
                        <div>
                            <p class="text-sm text-gray-700">
                                Showing
                                <span class="font-medium font-semibold"> {{ whitelists.from }} </span>
                                to
                                <span class="font-medium font-semibold"> {{ whitelists.to }}</span>
                                of
                                <span class="font-medium font-semibold"> {{ whitelists.total }} </span>
                                results
                            </p>
                        </div>
                        <div class="hidden md:block" v-if="whitelists.total > 10">
                            <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                                <a v-if="whitelists.links[0].url" :href="whitelists.links[0].url" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                    <span class="sr-only">Previous</span>
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                <!-- Loop through the links -->
                                <template v-for="(link, index) in whitelists.links">
                                    <a
                                        v-if="index !== 0 && index !== whitelists.links.length - 1"
                                        :href="link.url"
                                        :key="link.url"
                                        v-html="link.label"
                                        :aria-current="link.active ? 'page' : null"
                                        class="relative inline-flex items-center px-4 py-2 text-sm font-semibold"
                                        :class="[
                                            'text-gray-900 ring-1 ring-inset ring-gray-300 focus:z-20 focus:outline-offset-0',
                                            { 'bg-indigo-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600': link.active }
                                        ]"
                                    ></a>

                                    <span v-else-if="index === whitelists.links.length - 2 && whitelists.links.length > 3">...</span>

                                </template>

                                <a v-if="whitelists.links[whitelists.links.length - 1].url" :href="whitelists.links[whitelists.links.length - 1].url" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                    <span class="sr-only">Next</span>
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </nav>
                        </div>


                        <!-- mobile pagination -->
                        <div class="flex space-x-5 block md:hidden">
                            <div class="inline-flex justify-center">
                                <a v-if="whitelists.links[0].url" :href="whitelists.links[0].url" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                    <span class="sr-only">Previous</span>
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                <a v-if="whitelists.links[whitelists.links.length - 1].url" :href="whitelists.links[whitelists.links.length - 1].url" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                    <span class="sr-only">Next</span>
                                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                    </svg>
                                </a>
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
                                    :href="`/admin/whitelists?page=${pageNumber}`">

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
               <small> Page {{ whitelists.current_page }} / {{ whitelists.last_page }} </small>
            </div>
        </div>
    </AdminLayout>
</template>

<style>
    #pagination {
        width: 100%;
    }
    .sidebar-whitelists {
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
        border-radius:0px;
    }
    .table-zebra tbody tr:nth-child(odd) th, .table-zebra tbody tr:nth-child(odd) td {
        border-radius:0px;
    }
</style>

