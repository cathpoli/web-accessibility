<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/inertia-vue3';

const props = defineProps({
    mustVerifyEmail: Boolean,
    status: String,
});

const user = usePage().props.value.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
    billing_address: user.billing_address,
    billing_address_2: user.billing_address_2,
    billing_address_3: user.billing_address_3,
    city: user.city,
    state: user.state,
    zip: user.zip,
    country: user.country,
    phone_number: user.phone_number,
});
</script>

<template>
    <section>
        <header>
            <h2 class="font-bold text-xl">Profile Information</h2>

            <p class="mt-1">
                Update your account's profile information and email address.
            </p>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))" class="mt-6 space-y-6">
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <InputLabel for="name" class="font-semibold">
                        Name <span class="text-red-500">*</span>
                    </InputLabel>

                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full teal-border"
                        v-model="form.name"
                        autofocus
                        autocomplete="name"
                    />

                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                <div>
                    <InputLabel for="email" class="font-semibold">
                        Email <span class="text-red-500">*</span>
                    </InputLabel>

                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full teal-border"
                        v-model="form.email"
                        autocomplete="email"
                    />

                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div v-if="props.mustVerifyEmail && user.email_verified_at === null">
                        <p class="text-sm mt-2 text-gray-800">
                            Your email address is unverified.
                            <Link
                                :href="route('verification.send')"
                                method="post"
                                as="button"
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Click here to re-send the verification email.
                            </Link>
                        </p>

                        <div
                            v-show="props.status === 'verification-link-sent'"
                            class="mt-2 font-medium text-sm text-green-600"
                        >
                            A new verification link has been sent to your email address.
                        </div>
                    </div>

                    <div class="mt-4">
                        <InputLabel for="address" class="font-semibold">
                            Billing Address 1 <span class="text-red-500">*</span>
                        </InputLabel>

                        <TextInput
                            id="address"
                            type="text"
                            class="mt-1 block w-full teal-border"
                            v-model="form.billing_address"
                            autocomplete="address"
                        />

                        <InputError class="mt-2" :message="form.errors.billing_address" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="address" value="Billing Address 2" />

                        <TextInput
                            id="address"
                            type="text"
                            class="mt-1 block w-full teal-border"
                            v-model="form.billing_address_2"
                            autocomplete="address"
                        />

                        <InputError class="mt-2" :message="form.errors.billing_address_2" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="address" value="Billing Address 3" />

                        <TextInput
                            id="address"
                            type="text"
                            class="mt-1 block w-full teal-border"
                            v-model="form.billing_address_3"
                            autocomplete="address"
                        />

                        <InputError class="mt-2" :message="form.errors.billing_address_3" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="city" value="City" />

                        <TextInput
                            id="city"
                            type="text"
                            class="mt-1 block w-full teal-border"
                            v-model="form.city"
                            autocomplete="city"
                        />

                        <InputError class="mt-2" :message="form.errors.city" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="state" value="State" />

                        <TextInput
                            id="state"
                            type="text"
                            class="mt-1 block w-full teal-border"
                            v-model="form.state"
                            autocomplete="state"
                        />

                        <InputError class="mt-2" :message="form.errors.state" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="zip" value="Zip" />

                        <TextInput
                            id="zip"
                            type="text"
                            class="mt-1 block w-full teal-border"
                            v-model="form.zip"
                            autocomplete="zip"
                        />

                        <InputError class="mt-2" :message="form.errors.zip" />
                    </div>
                    <div class="mt-4">
                        <InputLabel for="country" value="Country" />

                        <TextInput
                            id="country"
                            type="text"
                            class="mt-1 block w-full teal-border"
                            v-model="form.country"
                            autocomplete="country"
                        />

                        <InputError class="mt-2" :message="form.errors.country" />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="phone_number" class="font-semibold">
                            Phone Number <span class="text-red-500">*</span>
                        </InputLabel>

                        <TextInput
                            id="phone_number"
                            type="text"
                            class="mt-1 block w-full teal-border"
                            v-model="form.phone_number"
                            autocomplete="phone_number"
                        />

                        <InputError class="mt-2" :message="form.errors.phone_number" />
                    </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing" class="btn teal-btn">Save</PrimaryButton>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
