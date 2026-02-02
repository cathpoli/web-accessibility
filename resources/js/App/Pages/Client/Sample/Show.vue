<script setup>
import AuthenticatedLayout from '@/App/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/inertia-vue3';
defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});

const openModal = () => {
    // Get the modal element
    var modal = document.getElementById("authentication-modal")
    modal.classList.remove("hidden")
    modal.style.display = 'block'
    modal.style.left = "40rem"
}

const closeModal = () => {
    // Get the modal element
    var modal = document.getElementById("authentication-modal")
    modal.style.display = 'none'
}
</script>
<template>
    <Head title="Profile" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Sample Page</h2>
        </template>
        <div class="p-5 lg:py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="overflow-hidden bg-white shadow sm:rounded-lg">
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
                                <!-- Modal toggle -->
                                <button @click="openModal" data-modal-target="authentication-modal" data-modal-toggle="authentication-modal" class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center" type="button">
                                    Toggle modal
                                </button>

                                <!-- Main modal -->
                                <div id="authentication-modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] md:h-full">
                                    <div class="relative w-full h-full max-w-md md:h-auto">
                                        <!-- Modal content -->
                                        <div class="relative bg-white rounded-lg shadow">
                                            <button @click="closeModal" type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-hide="authentication-modal">
                                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                                <span class="sr-only">Close modal</span>
                                            </button>
                                            <div class="px-6 py-6 lg:px-8">
                                                <h3 class="mb-4 text-xl font-medium text-gray-900">Sample modal</h3>
                                                <form class="space-y-6" action="#">
                                                    <div>
                                                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 ">Enter text</label>
                                                        <input type="email" name="text" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="name@company.com" required>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
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
    </AuthenticatedLayout>
</template>
