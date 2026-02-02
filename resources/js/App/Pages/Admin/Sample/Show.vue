<script setup>
import AdminLayout from '@/App/Layouts/AdminLayout.vue'
import { Head } from '@inertiajs/inertia-vue3';
import { PaperClipIcon } from '@heroicons/vue/20/solid'
</script>

<template>
    <Head title="Profile" />

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Sample Page</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="overflow-hidden bg-white shadow-lg rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Sample Page</h3>
                        <p class="mt-1 text-sm text-gray-500">For Testing Only.</p>
                    </div>
                    <div class="border-t border-gray-200">
                        <dl>
                            <div class="bg-gray-50 px-4 py-5">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                                Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                            </div>
                            <div class="bg-gray-50 px-4 py-5">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                                Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                            </div>
                            <div class="bg-gray-50 px-4 py-5">
                                <img src="../../../../../../public/assets/images/sample-image.jpg">
                            </div>
                            <div class="bg-gray-50 px-4 py-5">
                                <iframe width="100%" height="629" src="https://www.youtube.com/embed/53kB0GKAd2k" title="What is Vue.js? | Vue.js Explained in 2 Minutes For BEGINNERS." frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                            </div>
                            <div class="bg-gray-50 px-4 py-5">
                                <div class="slider">
                                    <div class="slide-track">
                                        <div class="slide">
                                            <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/557257/1.png" height="100" width="250" alt="" class="image-animation"/>
                                        </div>
                                        <div class="slide">
                                            <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/557257/2.png" height="100" width="250" alt="" class="image-animation"/>
                                        </div>
                                        <div class="slide">
                                            <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/557257/3.png" height="100" width="250" alt="" class="image-animation"/>
                                        </div>
                                        <div class="slide">
                                            <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/557257/4.png" height="100" width="250" alt="" class="image-animation"/>
                                        </div>
                                        <div class="slide">
                                            <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/557257/5.png" height="100" width="250" alt="" class="image-animation"/>
                                        </div>
                                        <div class="slide">
                                            <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/557257/6.png" height="100" width="250" alt="" class="image-animation"/>
                                        </div>
                                        <div class="slide">
                                            <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/557257/7.png" height="100" width="250" alt="" class="image-animation"/>
                                        </div>
                                        <div class="slide">
                                            <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/557257/1.png" height="100" width="250" alt="" class="image-animation"/>
                                        </div>
                                        <div class="slide">
                                            <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/557257/2.png" height="100" width="250" alt="" class="image-animation"/>
                                        </div>
                                        <div class="slide">
                                            <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/557257/3.png" height="100" width="250" alt="" class="image-animation"/>
                                        </div>
                                        <div class="slide">
                                            <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/557257/4.png" height="100" width="250" alt="" class="image-animation"/>
                                        </div>
                                        <div class="slide">
                                            <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/557257/5.png" height="100" width="250" alt="" class="image-animation"/>
                                        </div>
                                        <div class="slide">
                                            <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/557257/6.png" height="100" width="250" alt="" class="image-animation"/>
                                        </div>
                                        <div class="slide">
                                            <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/557257/7.png" height="100" width="250" alt="" class="image-animation"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style>

    .sidebar-sample-page {
        color: #02C9B2;
        fill: #02C9B2;
    }

</style>

