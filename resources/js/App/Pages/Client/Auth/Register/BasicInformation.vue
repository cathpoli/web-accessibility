<script setup>
import { onMounted, ref } from 'vue';
import RegisterLayout from '@/App/Layouts/RegisterLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import countries from './json/countryList.json';
import { VueRecaptcha, useRecaptcha } from "vue3-recaptcha-v2";

const props = defineProps({
    loginDomain: String,
});

const { resetRecaptcha } = useRecaptcha();

const recaptchaWidget = ref(null);
const recaptchaError = ref(null);
const showPassword = ref(false);
const showPasswordConfirmation = ref(false);

onMounted(() => {
    //Populate the country
    const countriesSelect = document.getElementById('countries');

    countries.forEach(country => {
        const option = document.createElement('option');
        option.text = country.name;
        option.value = country.name;
        countriesSelect.add(option);
    });

    //Accept numeric and plus sign only
    const inputElement = document.getElementById('phone_number');
    inputElement.addEventListener('input', (event) => {
        const inputValue = event.target.value;
        const sanitizedValue = inputValue.replace(/[^+\d]/g, '');
        event.target.value = sanitizedValue;
    });

});

const form = useForm({
    name: '',
    email: '',
    website_url: '',
    billing_address: '',
    billing_address_2: '',
    billing_address_3: '',
    city: '',
    state: '',
    zip: '',
    country: '',
    phone_number: '',
    password: '',
    password_confirmation: '',
    terms: false,
    recaptcha_token: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
        onError: (errors) => {
            console.error(errors)
        }
    });

    // reset recaptcha
    actionReset()
};

const togglePassword = () => {
    showPassword.value = !showPassword.value;
};

const togglePasswordConfirmation = () => {
    showPasswordConfirmation.value = !showPasswordConfirmation.value;
};

const goToLogin = () => window.location.href = `${props.loginDomain}/login`;

/** reCAPTCHA functions */
const callbackVerify = (response) => form.recaptcha_token = response

const callbackExpired = () => recaptchaError.value = 'reCAPTCHA Expired!'

const callbackFail = () => recaptchaError.value = 'reCAPTCHA Failed!'

const actionReset = () => resetRecaptcha(recaptchaWidget.value)

</script>

<template>
    <RegisterLayout>
        <Head title="Register" />

            <div class="container mx-auto">
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 md:grid-rows-1 lg:grid-cols-2">
                        <div>
                            <div class="grid grid-cols-1 items-center md:grid-cols-2">

                                <div>
                                    <h1 class="text-2xl font-bold"> Register your account </h1>
                                    <Link
                                        @click="goToLogin"
                                        class="">
                                        <p class="text-blue-600"> Already registered? <span class="underline font-bold hover:text-teal-600"> Sign in. </span></p>
                                    </Link>
                                </div>

                                <div class="hidden md:block mx-auto">
                                    <svg width="60" height="109" viewBox="0 0 60 109" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9.19446 105.64C20.4077 91.6712 31.1266 78.3774 41.7822 63.9826C46.8648 57.1167 51.9729 48.9655 56.7152 41.8491C46.2462 40.9325 40.5359 41.9614 30.1007 40.9359C30.1007 40.9359 34.3033 18.8642 36.7793 3C21.4409 18.7718 9.02004 45.592 3 60.7549C10.3623 61.1787 17.8896 61.4958 25.2646 61.2971C20.0941 76.1306 14.5437 90.8798 9.19446 105.64Z" stroke="#DFF8F4" stroke-width="5.33333" stroke-miterlimit="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-5 mx-auto mt-5 md:mr-5 md:grid-cols-2">
                                <div class="pr-0 lg:pr-3">
                                    <InputLabel for="name" class="font-semibold">
                                        Name <span class="text-red-500">*</span>
                                    </InputLabel>

                                    <TextInput
                                        id="name"
                                        type="text"
                                        class="mt-1 teal-border bg-transparent w-full"
                                        v-model="form.name"
                                        autofocus
                                        autocomplete="name"
                                    />
                                    <InputError class="mt-2" :message="form.errors.name" />
                                </div>

                                <div class="pr-0 lg:pr-3">
                                    <InputLabel for="email" class="font-semibold">
                                        Email <span class="text-red-500">*</span>
                                    </InputLabel>
                                    <TextInput
                                        id="email"
                                        type="email"
                                        class="mt-1 teal-border bg-transparent w-full"
                                        v-model="form.email"
                                        autocomplete="username"
                                    />
                                    <InputError class="mt-2" :message="form.errors.email" />
                                </div>

                                <div class="pr-0 lg:pr-3">
                                    <InputLabel for="Website" class="font-semibold">
                                        Website/Domain <span class="text-red-500">*</span>
                                    </InputLabel>
                                    <div class="form-control">
                                        <label class="input-group">
                                            <span class="mt-1">https://</span>
                                            <TextInput
                                                id="website"
                                                type="text"
                                                class="mt-1 teal-border bg-transparent w-full"
                                                v-model="form.website_url"
                                                autocomplete="website"
                                            />
                                        </label>
                                    </div>

                                    <InputError class="mt-2" :message="form.errors.website_url" />
                                </div>

                                <div class="pr-0 lg:pr-3">
                                    <InputLabel for="address1" class="font-semibold">
                                        Billing Address 1 <span class="text-red-500">*</span>
                                    </InputLabel>
                                    <TextInput
                                        id="address1"
                                        type="text"
                                        class="mt-1 teal-border bg-transparent w-full"
                                        v-model="form.billing_address"
                                        autocomplete="address1"
                                    />

                                    <InputError class="mt-2" :message="form.errors.billing_address" />
                                </div>

                                <div class="pr-0 lg:pr-3">
                                    <InputLabel for="address2" class="font-semibold">
                                        Billing Address 2
                                    </InputLabel>
                                    <TextInput
                                        id="address2"
                                        type="text"
                                        class="mt-1 teal-border bg-transparent w-full"
                                        v-model="form.billing_address_2"
                                        autocomplete="address2"
                                    />

                                    <InputError class="mt-2" :message="form.errors.billing_address_2" />
                                </div>

                                <div class="pr-0 lg:pr-3">
                                    <InputLabel for="address3" class="font-semibold">
                                        Billing Address 3
                                    </InputLabel>
                                    <TextInput
                                        id="address3"
                                        type="text"
                                        class="mt-1 teal-border bg-transparent w-full"
                                        v-model="form.billing_address_3"
                                        autocomplete="address3"
                                    />

                                    <InputError class="mt-2" :message="form.errors.billing_address_3" />
                                </div>

                                <div class="pr-0 lg:pr-3">
                                    <InputLabel for="city" class="font-semibold">
                                        City
                                    </InputLabel>
                                    <TextInput
                                        id="city"
                                        type="text"
                                        class="mt-1 teal-border bg-transparent w-full"
                                        v-model="form.city"
                                        autocomplete="city"
                                    />

                                    <InputError class="mt-2" :message="form.errors.city" />
                                </div>

                                <div class="pr-0 lg:pr-3">
                                    <InputLabel for="state" class="font-semibold">
                                        State
                                    </InputLabel>
                                    <TextInput
                                        id="state"
                                        type="text"
                                        class="mt-1 teal-border bg-transparent w-full"
                                        v-model="form.state"
                                        autocomplete="state"
                                    />

                                    <InputError class="mt-2" :message="form.errors.state" />
                                </div>

                                <div class="pr-0 lg:pr-3">
                                    <InputLabel for="zip" class="font-semibold">
                                        Zip
                                    </InputLabel>
                                    <TextInput
                                        id="zip"
                                        type="text"
                                        class="mt-1 teal-border bg-transparent w-full"
                                        v-model="form.zip"
                                        autocomplete="zip"
                                    />

                                    <InputError class="mt-2" :message="form.errors.zip" />
                                </div>

                                <div class="pr-0 lg:pr-3">
                                    <InputLabel for="country" class="font-semibold">
                                        Country
                                    </InputLabel>
                                    <select id="countries" class="w-full rounded-md teal-border mt-1 bg-transparent" v-model="form.country">
                                    </select>

                                    <InputError class="mt-2" :message="form.errors.country" />
                                </div>

                                <div class="pr-0 lg:pr-3">
                                    <InputLabel for="phone_number" class="font-semibold">
                                        Phone Number <span class="text-red-500">*</span>
                                    </InputLabel>
                                    <TextInput
                                        id="phone_number"
                                        type="text"
                                        class="mt-1 teal-border bg-transparent w-full"
                                        v-model="form.phone_number"
                                        autocomplete="phone_number"
                                    />

                                    <InputError class="mt-2" :message="form.errors.phone_number" />
                                </div>

                                <div class="pr-0 lg:pr-3">
                                    <InputLabel for="Password" class="font-semibold">
                                        Password <span class="text-red-500">*</span>
                                    </InputLabel>
                                    <div class="mt-1 flex items-center border border-teal-400 rounded-md focus-within:ring-indigo-500 focus-within:ring-2 focus-within:border-transparent">
                                        <TextInput
                                            id="password"
                                            :type="showPassword ? 'text' : 'password'"
                                            class="bg-transparent border-none focus:ring-0 shadow-none w-full"
                                            v-model="form.password"
                                            autocomplete="new-password"
                                        />
                                        <div id="toggle-password" class="pr-2 cursor-pointer" @click="togglePassword">
                                            <svg v-if="!showPassword" width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12.5 9.375C11.6712 9.375 10.8763 9.70424 10.2903 10.2903C9.70424 10.8763 9.375 11.6712 9.375 12.5C9.375 13.3288 9.70424 14.1237 10.2903 14.7097C10.8763 15.2958 11.6712 15.625 12.5 15.625C13.3288 15.625 14.1237 15.2958 14.7097 14.7097C15.2958 14.1237 15.625 13.3288 15.625 12.5C15.625 11.6712 15.2958 10.8763 14.7097 10.2903C14.1237 9.70424 13.3288 9.375 12.5 9.375ZM12.5 17.7083C11.1187 17.7083 9.79391 17.1596 8.81716 16.1828C7.84041 15.2061 7.29167 13.8813 7.29167 12.5C7.29167 11.1187 7.84041 9.7939 8.81716 8.81715C9.79391 7.8404 11.1187 7.29167 12.5 7.29167C13.8813 7.29167 15.2061 7.8404 16.1829 8.81715C17.1596 9.7939 17.7083 11.1187 17.7083 12.5C17.7083 13.8813 17.1596 15.2061 16.1829 16.1828C15.2061 17.1596 13.8813 17.7083 12.5 17.7083ZM12.5 4.6875C7.29167 4.6875 2.84376 7.92708 1.04167 12.5C2.84376 17.0729 7.29167 20.3125 12.5 20.3125C17.7083 20.3125 22.1563 17.0729 23.9583 12.5C22.1563 7.92708 17.7083 4.6875 12.5 4.6875Z" fill="#02C9B2"/>
                                            </svg>
                                            <svg v-else width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12.3229 9.375L15.625 12.6667C15.625 12.6146 15.625 12.5521 15.625 12.5C15.625 11.6712 15.2958 10.8763 14.7097 10.2903C14.1237 9.70424 13.3288 9.375 12.5 9.375C12.4375 9.375 12.3854 9.375 12.3229 9.375ZM7.84376 10.2083L9.45834 11.8229C9.40626 12.0417 9.37501 12.2604 9.37501 12.5C9.37501 13.3288 9.70425 14.1237 10.2903 14.7097C10.8763 15.2958 11.6712 15.625 12.5 15.625C12.7292 15.625 12.9583 15.5938 13.1771 15.5417L14.7917 17.1562C14.0938 17.5 13.3229 17.7083 12.5 17.7083C11.1187 17.7083 9.79391 17.1596 8.81716 16.1828C7.84041 15.2061 7.29167 13.8813 7.29167 12.5C7.29167 11.6771 7.50001 10.9063 7.84376 10.2083ZM2.08334 4.44792L4.45834 6.82292L4.92709 7.29167C3.20834 8.64583 1.85417 10.4167 1.04167 12.5C2.84376 17.0729 7.29167 20.3125 12.5 20.3125C14.1146 20.3125 15.6563 20 17.0625 19.4375L17.5104 19.875L20.5521 22.9167L21.875 21.5938L3.40626 3.125M12.5 7.29167C13.8813 7.29167 15.2061 7.8404 16.1829 8.81715C17.1596 9.7939 17.7083 11.1187 17.7083 12.5C17.7083 13.1667 17.5729 13.8125 17.3333 14.3958L20.3854 17.4479C21.9479 16.1458 23.1979 14.4375 23.9583 12.5C22.1563 7.92708 17.7083 4.6875 12.5 4.6875C11.0417 4.6875 9.64584 4.94792 8.33334 5.41667L10.5938 7.65625C11.1875 7.42708 11.8229 7.29167 12.5 7.29167Z" fill="#02C9B2"/>
                                            </svg>
                                        </div>
                                    </div>

                                    <InputError class="mt-2" :message="form.errors.password" />
                                </div>

                                <div class="pr-0 pb-2 lg:pr-3">
                                    <InputLabel for="password_confirmation" class="font-semibold">
                                        Confirm Password <span class="text-red-500">*</span>
                                    </InputLabel>
                                    <div class="mt-1 flex items-center border border-teal-400 rounded-md focus-within:ring-indigo-500 focus-within:ring-2 focus-within:border-transparent">
                                        <TextInput
                                            id="password_confirmation"
                                            :type="showPasswordConfirmation ? 'text' : 'password'"
                                            class="bg-transparent border-none focus:ring-0 shadow-none w-full"
                                            v-model="form.password_confirmation"
                                            autocomplete="new-password"
                                        />
                                        <div id="toggle-password" class="pr-2 cursor-pointer" @click="togglePasswordConfirmation">
                                            <svg v-if="!showPasswordConfirmation" width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12.5 9.375C11.6712 9.375 10.8763 9.70424 10.2903 10.2903C9.70424 10.8763 9.375 11.6712 9.375 12.5C9.375 13.3288 9.70424 14.1237 10.2903 14.7097C10.8763 15.2958 11.6712 15.625 12.5 15.625C13.3288 15.625 14.1237 15.2958 14.7097 14.7097C15.2958 14.1237 15.625 13.3288 15.625 12.5C15.625 11.6712 15.2958 10.8763 14.7097 10.2903C14.1237 9.70424 13.3288 9.375 12.5 9.375ZM12.5 17.7083C11.1187 17.7083 9.79391 17.1596 8.81716 16.1828C7.84041 15.2061 7.29167 13.8813 7.29167 12.5C7.29167 11.1187 7.84041 9.7939 8.81716 8.81715C9.79391 7.8404 11.1187 7.29167 12.5 7.29167C13.8813 7.29167 15.2061 7.8404 16.1829 8.81715C17.1596 9.7939 17.7083 11.1187 17.7083 12.5C17.7083 13.8813 17.1596 15.2061 16.1829 16.1828C15.2061 17.1596 13.8813 17.7083 12.5 17.7083ZM12.5 4.6875C7.29167 4.6875 2.84376 7.92708 1.04167 12.5C2.84376 17.0729 7.29167 20.3125 12.5 20.3125C17.7083 20.3125 22.1563 17.0729 23.9583 12.5C22.1563 7.92708 17.7083 4.6875 12.5 4.6875Z" fill="#02C9B2"/>
                                            </svg>
                                            <svg v-else width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12.3229 9.375L15.625 12.6667C15.625 12.6146 15.625 12.5521 15.625 12.5C15.625 11.6712 15.2958 10.8763 14.7097 10.2903C14.1237 9.70424 13.3288 9.375 12.5 9.375C12.4375 9.375 12.3854 9.375 12.3229 9.375ZM7.84376 10.2083L9.45834 11.8229C9.40626 12.0417 9.37501 12.2604 9.37501 12.5C9.37501 13.3288 9.70425 14.1237 10.2903 14.7097C10.8763 15.2958 11.6712 15.625 12.5 15.625C12.7292 15.625 12.9583 15.5938 13.1771 15.5417L14.7917 17.1562C14.0938 17.5 13.3229 17.7083 12.5 17.7083C11.1187 17.7083 9.79391 17.1596 8.81716 16.1828C7.84041 15.2061 7.29167 13.8813 7.29167 12.5C7.29167 11.6771 7.50001 10.9063 7.84376 10.2083ZM2.08334 4.44792L4.45834 6.82292L4.92709 7.29167C3.20834 8.64583 1.85417 10.4167 1.04167 12.5C2.84376 17.0729 7.29167 20.3125 12.5 20.3125C14.1146 20.3125 15.6563 20 17.0625 19.4375L17.5104 19.875L20.5521 22.9167L21.875 21.5938L3.40626 3.125M12.5 7.29167C13.8813 7.29167 15.2061 7.8404 16.1829 8.81715C17.1596 9.7939 17.7083 11.1187 17.7083 12.5C17.7083 13.1667 17.5729 13.8125 17.3333 14.3958L20.3854 17.4479C21.9479 16.1458 23.1979 14.4375 23.9583 12.5C22.1563 7.92708 17.7083 4.6875 12.5 4.6875C11.0417 4.6875 9.64584 4.94792 8.33334 5.41667L10.5938 7.65625C11.1875 7.42708 11.8229 7.29167 12.5 7.29167Z" fill="#02C9B2"/>
                                            </svg>
                                        </div>
                                    </div>

                                    <InputError class="mt-2" :message="form.errors.password_confirmation" />
                                </div>

                                <!-- <div class="pr-0 lg:pr-3">
                                    <label class="flex items-center">
                                        <Checkbox name="remember" id="show-password" class="teal-border text-teal-600 focus:ring-teal-500" @change="togglePassword"/>
                                        <span class="ml-2 text-sm text-gray-600">Show Password</span>
                                    </label>
                                </div> -->
                            </div>

                            <div class="flex justify-center pt-5">
                                <vue-recaptcha
                                    class=""
                                    theme="light"
                                    size="normal"
                                    :tabindex="0"
                                    @widgetId="recaptchaWidget = $event"
                                    @verify="callbackVerify($event)"
                                    @expired="callbackExpired()"
                                    @fail="callbackFail()"
                                />
                                <InputError class="mt-2" :message="form.errors.recaptcha_token" />
                                <div v-if="recaptchaError" class="mt-2">
                                    <p class="text-sm text-red-600">
                                        {{ recaptchaError }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex justify-center py-5 md:p-5 md:pr-16">
                                <PrimaryButton class="btn teal-btn" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Register
                                </PrimaryButton>
                            </div>
                        </div>

                        <div>
                            <div class="hidden md:flex justify-end">
                                <svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M23.7091 12.1689C18.1991 20.3544 13.6565 29.0504 8.53341 37.4515" stroke="#E7F3FF" stroke-width="5.33333" stroke-miterlimit="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M51.256 11.4666C38.4021 21.1865 26.4095 32.4583 15.186 43.986" stroke="#E7F3FF" stroke-width="5.33333" stroke-miterlimit="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M55.3287 39.2532C44.4056 40.9753 29.8903 48.1268 19.3354 52.5576" stroke="#E7F3FF" stroke-width="5.33333" stroke-miterlimit="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>


                            <div id="abstract-figure" class="hidden md:block">
                                <div id="abstract-small-box"></div>
                                <div id="abstract-blue-green-gradient" class="mx-auto lg:ml-auto lg:mr-12">
                                    <div id="abstract-inner-box"></div>
                                </div>
                                <div id="accessibility-circle">
                                    <svg width="55" height="55" viewBox="0 0 55 55" fill="white" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M27.2576 15.75C29.6717 15.75 31.6326 13.7891 31.6326 11.375C31.6326 8.96089 29.6717 7 27.2576 7C24.8435 7 22.8826 8.96089 22.8826 11.375C22.8826 13.7891 24.8435 15.75 27.2576 15.75Z M41.0078 15.8123L40.9687 15.8202L40.9374 15.828C40.8593 15.8514 40.7812 15.8749 40.7031 15.8983C39.25 16.328 32.1952 18.3123 27.2187 18.3123C22.6016 18.3123 16.1797 16.5937 14.125 16.0077C13.9219 15.9296 13.7109 15.8592 13.5 15.8046C12.0156 15.4139 11 16.9218 11 18.2968C11 19.6639 12.2266 20.3123 13.4609 20.7811V20.8046L20.8984 23.1249C21.6562 23.4139 21.8593 23.7108 21.9609 23.9687C22.2812 24.7968 22.0234 26.4373 21.9374 27.0077L21.4843 30.5233L18.9766 44.2968C18.9687 44.3358 18.9609 44.3749 18.9531 44.4139L18.9374 44.5154C18.7578 45.7733 19.6797 46.9999 21.4374 46.9999C22.9687 46.9999 23.6484 45.9452 23.9374 44.5077C23.9374 44.5077 26.125 32.1952 27.2187 32.1952C28.3124 32.1952 30.5624 44.5077 30.5624 44.5077C30.8593 45.9452 31.5312 46.9999 33.0624 46.9999C34.8202 46.9999 35.75 45.7733 35.5624 44.5077C35.5469 44.3983 35.5234 44.2968 35.5 44.1873L32.9609 30.5389L32.5078 27.0233C32.1797 24.9764 32.4452 24.2968 32.5312 24.1404C32.5312 24.1404 32.539 24.1327 32.539 24.1249C32.625 23.9687 33.0078 23.6171 33.9062 23.2811L40.8828 20.8437C40.9219 20.8358 40.9687 20.8202 41.0078 20.8046C42.2578 20.3358 43.5078 19.6873 43.5078 18.3123C43.5078 16.9373 42.4921 15.4218 41.0078 15.8123Z"/>
                                    </svg>
                                </div>

                            </div>

                            <div class="mx-auto my-5 md:mr-12 md:py-5">
                                <h2 class="text-center text-4xl font-extrabold text-blue-600 lg:text-right"> Take the first step in making your <br class="hidden md:block"> website accessible. </h2>
                            </div>
                        </div>

                        <hr class="block p-3 w-full md:hidden">

                        <div class="block mx-auto md:hidden">

                            <a href="/" class="flex items-center gap-3">
                                <svg width="45" height="43" viewBox="0 0 52 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20.1281 32.2668C23.9429 32.2668 27.5591 32.2668 31.3615 32.2668C29.4742 28.9831 27.6529 25.8141 25.7452 22.4945C23.8487 25.7936 22.0282 28.9615 20.1281 32.2668ZM10.3373 49.5125C8.38052 48.7636 6.31481 48.1952 4.51489 47.1637C1.33876 45.3435 0.024286 42.3051 0.000202018 38.718C-0.0151607 36.4223 0.848067 34.3202 1.77326 32.2693C4.59353 26.0176 8.1765 20.1875 11.8359 14.4163C15.0576 9.33511 23.8611 8.08329 27.6609 14.8475C28.8429 16.9514 30.0712 19.029 31.275 21.1205C33.2648 24.5769 35.2536 28.0336 37.2391 31.4926C37.4573 31.8725 37.5963 32.2572 38.2075 32.075C39.7945 31.6021 40.9776 30.2789 40.7101 28.6478C40.4612 27.1314 39.9244 25.5992 39.2081 24.2361C37.6189 21.2127 35.8582 18.279 34.1289 15.3317C32.4131 12.4068 30.6448 9.5097 27.9397 7.35819C25.6218 5.51451 22.9927 4.71107 20.0551 5.24474C18.5703 5.5145 17.1172 5.9607 15.6498 6.32709C16.6622 5.05515 17.7682 3.84176 19.0494 2.85751C23.8925 -0.863529 29.2917 -0.350361 33.3893 4.16166C34.153 5.0028 34.9434 5.85601 35.5222 6.82343C38.926 12.5126 42.327 18.2051 45.6141 23.9627C47.5667 27.3825 46.957 31.8832 44.3614 34.8637C42.5463 36.9475 40.1999 37.8319 37.496 37.8362C31.0057 37.8465 24.5147 37.8505 18.0244 37.8161C17.2067 37.8117 16.8976 38.0786 16.8337 38.8732C16.6932 40.6235 17.3676 41.9196 19.0126 42.5338C19.938 42.879 20.9513 43.129 21.9326 43.1554C25.1416 43.241 28.3557 43.2641 31.5651 43.1964C34.3691 43.1371 37.196 43.1147 39.9664 42.7359C45.6083 41.9647 48.7425 38.9435 49.951 33.486C50.0773 32.9157 50.1678 32.337 50.3123 31.5618C50.5298 31.9293 50.6904 32.1028 50.7422 32.3048C51.0764 33.5995 51.476 34.8861 51.6895 36.2019C52.6233 41.961 49.4913 46.7256 43.8644 48.0781C42.408 48.428 40.9002 48.7172 39.4103 48.7472C33.1594 48.8734 26.9056 48.857 20.6539 48.958C14.2012 49.062 8.04991 41.7381 12.4938 34.4867C15.8021 29.0882 18.8301 23.5172 22.0202 18.0447C22.4311 17.3398 22.2997 16.9899 21.6523 16.6183C20.3967 15.898 19.1323 15.8405 17.9788 16.7098C17.2165 17.2845 16.4779 17.9796 15.96 18.7731C14.8083 20.5381 13.7565 22.3734 12.7463 24.2251C10.8226 27.7514 8.70193 31.1994 7.12589 34.8795C4.72727 40.4804 6.00617 44.8812 10.6448 48.7977C10.8864 49.0016 11.1164 49.2193 11.5578 49.6154C10.9657 49.5715 10.6214 49.6212 10.3373 49.5125Z" fill="url(#paint0_linear_4_20)"/>
                                    <defs>
                                    <linearGradient id="paint0_linear_4_20" x1="0" y1="49.6154" x2="51.8801" y2="0.413455" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#03C7B4"/>
                                    <stop offset="1" stop-color="#2088E8"/>
                                    </linearGradient>
                                    </defs>
                                </svg>
                                <svg width="100" height="18" viewBox="0 0 180 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0.0238342 30.8289L13.447 0.242235H18.4328L31.856 30.8289H26.231L23.1629 23.4371H8.71695L5.52095 30.8289H0.0238342ZM10.6346 18.8491H21.1174L15.876 6.74188L10.6346 18.8491Z" fill="black"/>
                                    <path d="M31.6003 19.4853C31.6003 17.3188 32.1116 15.4071 33.1343 13.4954C34.1571 11.7112 35.5633 10.1819 37.353 9.16234C39.1428 8.01534 41.1882 7.50556 43.4894 7.50556C45.5348 7.50556 47.3246 7.8879 48.7308 8.52512C50.137 9.16234 51.5433 10.1819 52.6938 11.4563L49.37 15.0248C48.4751 14.1327 47.5802 13.368 46.6854 12.8582C45.7905 12.3484 44.6399 12.0935 43.4894 12.0935C41.5718 12.0935 40.0377 12.8582 38.7593 14.2601C37.4809 15.662 36.8417 17.4462 36.8417 19.4853C36.8417 21.5244 37.4809 23.3086 38.7593 24.7105C40.0377 26.1124 41.6996 26.8771 43.745 26.8771C44.8956 26.8771 45.9183 26.6222 46.941 26.1124C47.8359 25.6026 48.8586 24.838 49.7535 23.9459L52.9495 27.132C51.6711 28.5339 50.3927 29.5534 48.8586 30.3181C47.4524 31.0827 45.6626 31.4651 43.4894 31.4651C41.1882 31.4651 39.2706 30.9553 37.353 29.8083C35.5633 28.7888 34.1571 27.2594 33.1343 25.4752C32.1116 23.5635 31.6003 21.6519 31.6003 19.4853Z" fill="black"/>
                                    <path d="M56.4014 19.4853C56.4014 17.3188 56.9127 15.2797 57.8076 13.4954C58.7025 11.7112 60.1087 10.1819 61.7706 9.16234C63.5604 8.14279 65.478 7.50556 67.5234 7.50556C68.9297 7.50556 70.3359 7.76045 71.4865 8.14278C72.637 8.52512 73.6598 9.16233 74.5546 9.927C75.4495 10.6917 76.0887 11.5838 76.7279 12.6033C77.3671 13.6229 77.7506 14.7699 78.0063 16.0443C78.262 17.3188 78.3898 18.5932 78.3898 19.8677C78.3898 20.5049 78.3898 21.0147 78.262 21.2695H61.3871C61.6428 23.0538 62.4098 24.5831 63.5604 25.6026C64.711 26.6222 66.245 27.132 67.907 27.132C69.1854 27.132 70.2081 26.8771 71.2308 26.4947C72.2535 26.1124 73.1484 25.3478 74.1711 24.4557L77.2393 27.132C74.8103 30.0632 71.7422 31.4651 67.7791 31.4651C65.6058 31.4651 63.5604 30.9553 61.7706 29.9357C59.9809 28.9162 58.5746 27.5143 57.5519 25.7301C57.0406 23.8184 56.4014 21.7793 56.4014 19.4853ZM61.6428 17.8285H73.4041C73.2762 16.0443 72.637 14.6424 71.6143 13.4954C70.5916 12.3484 69.3132 11.8387 67.5234 11.8387C65.9894 11.8387 64.5831 12.3484 63.5604 13.4954C62.6655 14.515 61.8985 16.0443 61.6428 17.8285Z" fill="black"/>
                                    <path d="M79.4126 30.8289L92.8358 0.242235H97.8215L111.245 30.8289H105.492L102.424 23.4371H87.9778L84.7818 30.8289H79.4126ZM90.0233 18.8491H100.506L95.2647 6.74188L90.0233 18.8491Z" fill="black"/>
                                    <path d="M116.358 30.8279V0.496126H127.736C130.037 0.496126 132.338 0.878451 134.256 1.64312C136.301 2.40778 137.963 3.42734 139.369 4.82922C140.776 6.23111 141.926 7.76044 142.693 9.67211C143.46 11.5838 143.844 13.4954 143.844 15.662C143.844 17.8285 143.46 19.7402 142.693 21.6519C141.926 23.5635 140.776 25.0929 139.369 26.4947C137.963 27.8966 136.301 28.9162 134.256 29.6809C132.21 30.4455 130.037 30.8279 127.736 30.8279H116.358ZM121.727 25.985H127.736C130.932 25.985 133.489 24.9654 135.406 23.0538C137.324 21.1421 138.347 18.5932 138.347 15.662C138.347 12.6033 137.324 10.1819 135.406 8.27021C133.489 6.35855 130.932 5.33901 127.736 5.33901H121.727V25.985Z" fill="black"/>
                                    <path d="M147.807 30.8289L161.23 0.242235H166.216L179.639 30.8289H174.014L170.946 23.4371H156.5L153.304 30.8289H147.807ZM158.418 18.8491H168.901L163.659 6.74188L158.418 18.8491Z" fill="black"/>
                                </svg>
                            </a>
                        </div>


                    </div>


                </form>

            </div>
    </RegisterLayout>
</template>

<style>
    /*taken from aceADA.com*/
    :root {
    --space-xs: clamp(1.0666666667rem, calc(0.2777777778vw + 0.9777777778rem), 1.3333333333rem);
    --space-s: clamp(1.6rem, calc(0.4166666667vw + 1.4666666667rem), 2rem);
    --space-m: clamp(2.4rem, calc(0.625vw + 2.2rem), 3rem);
    --space-l: clamp(3.1992rem, calc(1.355vw + 2.7656rem), 4.5rem);
    --space-xl: clamp(4.2645336rem, calc(2.5890275vw + 3.4360448rem), 6.75rem);
    --space-xxl: clamp(5.6846232888rem, calc(4.6253924075vw + 4.2044977184rem), 10.125rem);
    --abstract-blue-green-gradient: blue;
    }

    #abstract-figure {
        position: relative;
        max-width: 100%;
    }

    #abstract-small-box {
        width: 17rem;
        height: 13rem;
        background-color: #031239;
        position: absolute;
        z-index: 2;
        border-radius: var(--space-s);
        bottom: 60px;
        left: 50px;
    }

    #abstract-blue-green-gradient {
        height: 520px;
        max-width: 520px;
        background: linear-gradient(45deg, #257bf2, #02c9b2, #257bf2);
        border-radius: var(--space-m);
        padding: var(--space-xl);
        padding-bottom: 0rem;
    }

    #abstract-inner-box {
        background: #e7f3ff;
        width: 100%;
        height: 100%;
        z-index: 1;
        border-top-left-radius: var(--space-s);
        border-top-right-radius: var(--space-s);
    }

    #accessibility-circle {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #19274B;
        width: 5rem;
        height: 5rem;
        margin: 20px;
        border-radius: 50%;
        position: absolute;
        bottom: 20px;
        right: 0px;
        z-index: 3;
    }

    @media screen and (max-width: 1024px) {
        #accessibility-circle {
            right: 65px;
    }
}

</style>
