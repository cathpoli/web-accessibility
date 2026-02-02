<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import RegisterLayout from '@/App/Layouts/RegisterLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

const props = defineProps({
    canResetPassword: Boolean,
    status: String,
    registerDomain: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
    type: 'client',
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

const togglePassword = () => {
    if (document.getElementById('show-password').checked) {
        const password = document.querySelectorAll(".password");
        for(let i = 0; i < password.length; i++) {
            const type = password[i].getAttribute("type") === "password" ? "text" : "password";
            password[i].setAttribute("type", type);
        }
    } else {
        const password = document.querySelectorAll(".password");
        for(let i = 0; i < password.length; i++) {
            password[i].setAttribute("type", "password");
        }
    }
};

const goToRegister = () => window.location.href = `${props.registerDomain}`;

</script>

<template>
    <RegisterLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="w-full md:w-1/3 md:py-6 md:px-8 bg-white md:shadow-md md:rounded-lg">            
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

            <div>
                <InputLabel for="email" value="Email" class="font-semibold"/>

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full teal-border"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" class="font-semibold"/>

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full password teal-border"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block mt-4 grid grid-cols-2 gap-2">
                <div>
                    <label class="flex items-center">
                        <Checkbox name="remember" class="teal-border text-teal-600 focus:ring-teal-500" v-model:checked="form.remember" />
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                </div>

                <div>
                    <label class="flex justify-end">
                        <Checkbox name="remember" id="show-password" class="teal-border text-teal-600 focus:ring-teal-500" @change="togglePassword"/>
                        <span class="ml-2 text-sm text-gray-600">Show Password</span>
                    </label>
                </div>
            </div>
            
            <PrimaryButton class="w-full btn teal-btn mt-2" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Log in
            </PrimaryButton>

            <div class="flex justify-between items-center mt-4">
                <Link
                    v-if="canResetPassword"
                    @click="goToRegister"
                    class="mr-12 text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Create new account
                </Link>
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Forgot your password?
                </Link>
            </div>
        </form>
    </RegisterLayout>
</template>
