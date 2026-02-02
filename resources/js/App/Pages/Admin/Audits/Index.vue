<script setup>
import AdminLayout from '@/App/Layouts/AdminLayout.vue'
const selectedDomain = '';
</script>

<template>
    <Head title="Audits" />
    <AdminLayout>
        <div class="py-12">
            <div class="max-w-8xl px-6">
                <div class="flex flex-col md:flex-row items-center md:items-start justify-between space-y-4 md:space-y-0">
                    <div>
                        <h1 class="text-3xl font-bold blue-text">Run Your Audit</h1>
                    </div>

                    <form class="min-w-3/12">   
                        <label for="search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Run Audit</label>
                        <div class="relative flex">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-globe" viewBox="0 0 16 16" id="IconChangeColor"> 
                                    <path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm7.5-6.923c-.67.204-1.335.82-1.887 1.855A7.97 7.97 0 0 0 5.145 4H7.5V1.077zM4.09 4a9.267 9.267 0 0 1 .64-1.539 6.7 6.7 0 0 1 .597-.933A7.025 7.025 0 0 0 2.255 4H4.09zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a6.958 6.958 0 0 0-.656 2.5h2.49zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5H4.847zM8.5 5v2.5h2.99a12.495 12.495 0 0 0-.337-2.5H8.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5H4.51zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5H8.5zM5.145 12c.138.386.295.744.468 1.068.552 1.035 1.218 1.65 1.887 1.855V12H5.145zm.182 2.472a6.696 6.696 0 0 1-.597-.933A9.268 9.268 0 0 1 4.09 12H2.255a7.024 7.024 0 0 0 3.072 2.472zM3.82 11a13.652 13.652 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5H3.82zm6.853 3.472A7.024 7.024 0 0 0 13.745 12H11.91a9.27 9.27 0 0 1-.64 1.539 6.688 6.688 0 0 1-.597.933zM8.5 12v2.923c.67-.204 1.335-.82 1.887-1.855.173-.324.33-.682.468-1.068H8.5zm3.68-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.65 13.65 0 0 1-.312 2.5zm2.802-3.5a6.959 6.959 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5h2.49zM11.27 2.461c.247.464.462.98.64 1.539h1.835a7.024 7.024 0 0 0-3.072-2.472c.218.284.418.598.597.933zM10.855 4a7.966 7.966 0 0 0-.468-1.068C9.835 1.897 9.17 1.282 8.5 1.077V4h2.355z" id="mainIconPathAttribute" fill="#737373"></path> 
                                </svg>
                            </div>
                            <div class="rounded-l-lg teal-border">
                                <input type="search" id="search" class="border-0 rounded-l-lg block h-full w-full p-3 pl-10 text-sm text-gray-900 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="domain.com" required>
                            </div>
                            <div class="teal-bg rounded-r-lg">
                                <button type="submit" class="min-w-full btn text-black font-medium rounded-none rounded-r-lg text-sm px-4 py-2 teal-bg border-0 hover:bg-blue-800 hover:text-white focus:ring-4 focus:outline-none focus:ring-blue-300">Run Audit</button>
                            </div>
                        </div>
                    </form>
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
                                <select v-model="selectedDomain" @change="filterAuditByDomain()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-44 float-right p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="">All Audits (2)</option>
                                    <option value="1">www.uv-brite.com</option>
                                    <option value="2">www.mypaww.com</option>
                                </select>
                                <div class="flex">
                                    <div class="rounded-l-lg border border-gray-300">
                                        <input type="search" id="search" class="border-0 rounded-l-lg block h-full w-full p-3 text-sm text-gray-900 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="domain.com" required>
                                    </div>
                                    <div class="teal-bg rounded-r-lg">
                                        <button type="submit" class="min-w-full btn text-white font-medium text-sm px-4 py-2 bg-primary rounded-none rounded-r-lg border-0 hover:bg-blue-800 hover:text-white focus:ring-4 focus:outline-none focus:ring-blue-300">
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
                            </div>
                        </div>

                    </header>
                    <div class="overflow-x-scroll mt-4 lg:mt-8">
                        <table class="table table-zebra w-full w-full">
                            <thead>
                                <tr>
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
                                    <td class="lg:pl-6 xl:pl-12 font-bold">1</td>
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
                                        <Link href="" class="download-action-button hover:bg-sky-600/10 text-sky-600 capitalize tooltip" data-tip="Download Audit">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path d="M5,20H19V18H5M19,9H15V3H9V9H5L12,16L19,9Z" />
                                            </svg>
                                        </Link>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="lg:pl-6 xl:pl-12 font-bold">2</td>
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
                                        <Link href="" class="download-action-button hover:bg-sky-600/10 text-sky-600 capitalize tooltip" data-tip="Download Audit">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path d="M5,20H19V18H5M19,9H15V3H9V9H5L12,16L19,9Z" />
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
    </AdminLayout>
</template>

<style>
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
        z-index: 2;
    }

    .table td:first-child {
        position: sticky;
        left: 0;
        z-index: 1; /* Use a lower z-index for td to allow header to be on top */
        background-color: #fff; /* Reset background color */
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
</style>