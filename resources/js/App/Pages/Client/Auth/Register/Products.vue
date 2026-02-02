<script setup>
import RegisterLayout from '@/App/Layouts/RegisterLayout.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import InputError from '@/Components/InputError.vue';
import { useNotificationStore } from '@dafcoe/vue-notification'

const { setNotification } = useNotificationStore()

const props = defineProps({
    plans: Object,
});

const form = useForm({
    slug: '',
    coupon: null,
});

const select = (slug) => {
    const buttons = document.getElementsByTagName('button');

    for (let i = 0; i < buttons.length; i++) {
        buttons[i].disabled = true;
        buttons[i].classList.add('bg-purple-300');
    }

    form.slug = slug;
    form.coupon = document.getElementById('coupon').value;
    form.post(route('products.select'), {
        onError: (error) => {
            for (let i = 0; i < buttons.length; i++) {
                buttons[i].disabled = false;
                buttons[i].classList.remove('bg-purple-300');
            }

            for (var key in error) {
                if (error.hasOwnProperty(key)) {
                    setNotification({type: "alert", message: error[key]});
                }
            }
        }
    });
};

</script>

<template>
    <RegisterLayout>
        <Head title="Plans" />

        <div class="w-full pb-6 md:w-5/6 md:py-6 md:px-6 bg-white md:shadow-md md:rounded-lg">
            <div class="flex justify-center align-center items-center my-2">
                <svg width="100" height="100" viewBox="0 0 155 155" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M53.1804 84.1888C63.2594 84.1888 72.8138 84.1888 82.86 84.1888C77.8736 75.5179 73.0616 67.1496 68.0212 58.384C63.0106 67.0955 58.2006 75.4609 53.1804 84.1888ZM27.3122 129.728C22.1421 127.751 16.6843 126.25 11.9288 123.526C3.53714 118.719 0.064166 110.696 0.000533751 101.224C-0.0400559 95.1619 2.24067 89.6111 4.68512 84.1955C12.1365 67.687 21.6031 52.2919 31.2715 37.0524C39.7837 23.6349 63.0434 20.3293 73.0828 38.191C76.2056 43.7467 79.4509 49.2328 82.6315 54.7556C87.8889 63.8827 93.1434 73.0107 98.3892 82.1445C98.9657 83.1478 99.333 84.1637 100.948 83.6823C105.141 82.4335 108.267 78.9395 107.56 74.6326C106.902 70.6282 105.484 66.5822 103.592 62.9828C99.3928 54.9992 94.7409 47.2524 90.1719 39.4697C85.6386 31.7461 80.9665 24.0959 73.8194 18.4146C67.6953 13.5461 60.7488 11.4245 52.9876 12.8338C49.0645 13.5461 45.2254 14.7243 41.3482 15.6918C44.0231 12.3331 46.9454 9.12901 50.3304 6.52998C63.1263 -3.2959 77.3915 -1.94081 88.2177 9.97375C90.2356 12.1949 92.3239 14.4479 93.853 17.0025C102.846 32.0254 111.832 47.0571 120.517 62.2608C125.676 71.2912 124.065 83.1758 117.207 91.0464C112.411 96.5489 106.212 98.8841 99.0679 98.8957C81.92 98.9228 64.7701 98.9334 47.6222 98.8425C45.4616 98.8309 44.645 99.5355 44.4763 101.634C44.1051 106.256 45.8868 109.678 50.2331 111.3C52.6781 112.212 55.3555 112.872 57.948 112.941C66.4265 113.168 74.9185 113.229 83.398 113.05C90.8063 112.893 98.2754 112.834 105.595 111.834C120.501 109.797 128.782 101.819 131.975 87.4083C132.309 85.9025 132.548 84.3744 132.93 82.3272C133.505 83.2976 133.929 83.7558 134.066 84.2893C134.949 87.708 136.005 91.1054 136.569 94.5801C139.036 109.788 130.761 122.369 115.894 125.941C112.046 126.865 108.062 127.628 104.126 127.707C87.6103 128.041 71.0871 127.997 54.5697 128.264C37.5208 128.539 21.2686 109.199 33.0098 90.0509C41.7506 75.7953 49.751 61.0845 58.1794 46.6338C59.265 44.7722 58.9179 43.8482 57.2075 42.8671C53.89 40.965 50.5493 40.8132 47.5017 43.1088C45.4876 44.6263 43.5363 46.4617 42.168 48.5572C39.1249 53.2178 36.3459 58.0641 33.6768 62.9538C28.5944 72.2655 22.9913 81.3703 18.8273 91.088C12.4899 105.878 15.8689 117.499 28.1245 127.841C28.7628 128.379 29.3706 128.954 30.5369 130C28.9724 129.884 28.0626 130.015 27.3122 129.728Z" fill="url(#paint0_linear_302_296)"></path>
                    <defs>
                        <linearGradient id="paint0_linear_302_296" x1="0" y1="130" x2="137" y2="2.74181e-07" gradientUnits="userSpaceOnUse">
                            <stop stop-color="#03C7B4"></stop>
                            <stop offset="1" stop-color="#2088E8"></stop>
                        </linearGradient>
                    </defs>
                </svg>
            </div>

            <div class="flex flex-wrap flex-row gap-10 justify-center">
                <div class="card w-80 shadow-xl" v-for="(plan, index) in plans" :key="index">
                    <div class="relative z-10 -mx-4 group md:w-6/12 md:mx-0 lg:w-full">
                        <div aria-hidden="true" class="absolute top-0 w-full h-full rounded-2xl bg-white shadow-xl transition duration-500 group-hover:scale-105 lg:group-hover:scale-110"></div>
                        <div class="relative p-6 space-y-6 lg:p-8">
                            <h3 class="text-3xl text-gray-700 font-semibold text-center">{{ plan.name }}</h3>
                            <div>
                                <div class="relative flex justify-around">
                                    <div class="flex items-end">
                                        <div class="pb-2">
                                            <span class="block text-2xl text-gray-700 font-bold">$</span>
                                        </div>
                                        <span class="text-8xl text-gray-800 font-bold leading-0">{{ parseInt(plan.price) }}</span>
                                        <span class="block text-xl text-purple-500">/ {{ plan.interval }}</span>
                                    </div>
                                </div>
                            </div>
                            <ul role="list" class="w-max space-y-4 py-6 m-auto text-gray-600">
                                <li class="space-x-2">
                                    <span>{{ plan.description }}</span>
                                </li>
                            </ul>
                            <button type="submit" title="Submit" @click="select(plan.slug)" class="subscribe-btn block w-full py-3 px-6 text-center rounded-xl transition bg-purple-600 hover:bg-purple-700 active:bg-purple-800 focus:bg-indigo-600">
                                <span class="text-white font-semibold">
                                    Subscribe
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-center mt-6 md:mt-10">
                <div class="form-control block">
                    <label class="input-group input-group-sm">
                        <span class="bg-purple-600 text-white">Coupon</span>
                        <input type="text" id="coupon" name="coupon" placeholder="Enter code here.." class="input input-bordered input-sm" />
                    </label>
                    <InputError class="mt-2" :message="form.errors.coupon" />
                </div>
            </div>
        </div>
    </RegisterLayout>
</template>
