<script setup>
import AdminLayout from '@/App/Layouts/AdminLayout.vue'
import { Head, Link } from '@inertiajs/inertia-vue3';

defineProps({
    total_clients: Number,
    paying_clients: Number,
    active_domains: Number,
});

</script>

<template>
    <Head title="Admin Dashboard" />

    <AdminLayout>
        <div class="py-12">
            <div class="max-w-7xl px-6 md:px-8">
                <!-- Content here -->
                <div>
                    <h1 class="text-3xl font-bold blue-text">Dashboard</h1>
                </div>
                <div class="flex flex-wrap my-5 gap-5">
                    <!-- total clients -->
                    <div class="bg-white rounded rounded-xl p-8 shadow-xl mb-4 w-full md:w-52 md:p-6">
                        <div class="">
                            <h2 class="text-6xl font-semibold text-black">{{ total_clients || 0 }}</h2>
                            <p class="text-2xl font-bold blue-text">Total Clients</p>
                        </div>
                        <div class="details-btn my-1">
                            <Link :href="route('admin.clients.index')">
                                <p style="text-transform: capitalize;"> Details </p>
                            </Link>
                        </div>
                        <div class="mt-10">
                            <svg width="168px" height="48px" viewBox="0 0 168 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.95093 30.8095C9.80915 30.8572 10.5761 36.3595 15.4711 37.9195C20.3661 39.4796 22.4096 19.3302 27.9936 18.6613C33.5776 17.9924 35.5463 44.4387 40.5162 43.0596C45.486 41.6806 45.859 29.7403 52.4126 27.7954C58.9661 25.8504 58.4069 45.4452 64.309 45.995C70.2111 46.5447 70.1123 2 76.2054 2C82.2985 2 82.9146 32.277 89.3541 31.77C95.7936 31.263 95.9217 14.4053 102.835 14.4053C109.749 14.4053 111.922 29.3748 118 30.7619C124.079 32.149 122.552 47.9617 128.8 43.0596C135.048 38.1576 134.223 19.5138 140.07 18.6613C145.918 17.8088 147.851 29.7475 152.891 31.77C157.931 33.7925 160.344 43.119 165.304 43.119" stroke="#DFF8F4" stroke-width="4" stroke-linecap="round"/>
                            </svg>
                        </div>
                    </div>

                    <!-- paying clients -->
                    <div class="bg-white rounded rounded-xl p-8 shadow-xl mb-4 w-full md:w-52 md:p-6">
                        <div class="">
                            <h2 class="text-6xl font-semibold text-black">{{ paying_clients || 0 }}</h2>
                            <p class="text-2xl font-bold blue-text">Paying Clients</p>
                        </div>
                        <div class="details-btn my-1">
                            <Link :href="route('admin.clients.index')">
                                <p style="text-transform: capitalize;"> Details </p>
                            </Link>
                        </div>
                        <div class="mt-10">
                            <svg width="168px" height="48px" viewBox="0 0 168 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.95093 30.8095C9.80915 30.8572 10.5761 36.3595 15.4711 37.9195C20.3661 39.4796 22.4096 19.3302 27.9936 18.6613C33.5776 17.9924 35.5463 44.4387 40.5162 43.0596C45.486 41.6806 45.859 29.7403 52.4126 27.7954C58.9661 25.8504 58.4069 45.4452 64.309 45.995C70.2111 46.5447 70.1123 2 76.2054 2C82.2985 2 82.9146 32.277 89.3541 31.77C95.7936 31.263 95.9217 14.4053 102.835 14.4053C109.749 14.4053 111.922 29.3748 118 30.7619C124.079 32.149 122.552 47.9617 128.8 43.0596C135.048 38.1576 134.223 19.5138 140.07 18.6613C145.918 17.8088 147.851 29.7475 152.891 31.77C157.931 33.7925 160.344 43.119 165.304 43.119" stroke="#DFF8F4" stroke-width="4" stroke-linecap="round"/>
                            </svg>
                        </div>
                    </div>

                     <!-- active domains -->
                     <div class="bg-white rounded rounded-xl p-8 shadow-xl mb-4 w-full md:w-60 md:p-6">
                        <div class="">
                            <h2 class="text-6xl font-semibold text-black">{{ active_domains || 0 }}</h2>
                            <p class="text-2xl font-bold blue-text">Active Domains</p>
                        </div>
                        <div class="details-btn my-1">
                            <Link :href="route('admin.whitelists.index')">
                                <p style="text-transform: capitalize;"> Details </p>
                            </Link>
                        </div>
                        <div class="mt-10">
                            <svg width="168px" height="48px" viewBox="0 0 168 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.95093 30.8095C9.80915 30.8572 10.5761 36.3595 15.4711 37.9195C20.3661 39.4796 22.4096 19.3302 27.9936 18.6613C33.5776 17.9924 35.5463 44.4387 40.5162 43.0596C45.486 41.6806 45.859 29.7403 52.4126 27.7954C58.9661 25.8504 58.4069 45.4452 64.309 45.995C70.2111 46.5447 70.1123 2 76.2054 2C82.2985 2 82.9146 32.277 89.3541 31.77C95.7936 31.263 95.9217 14.4053 102.835 14.4053C109.749 14.4053 111.922 29.3748 118 30.7619C124.079 32.149 122.552 47.9617 128.8 43.0596C135.048 38.1576 134.223 19.5138 140.07 18.6613C145.918 17.8088 147.851 29.7475 152.891 31.77C157.931 33.7925 160.344 43.119 165.304 43.119" stroke="#DFF8F4" stroke-width="4" stroke-linecap="round"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style>
    .details-btn {
        background: #C9EEE9;
        height: fit-content;
        width: fit-content;
        padding: 5px 7px;
        border-radius: 5px;
        font-size: 14px;
    }

    .details-btn:hover {
        background: rgba(13, 148, 136, 0.5);
    }

    .sidebar-dashboard {
        color: #02C9B2;
        fill: #02C9B2;
    }
</style>
