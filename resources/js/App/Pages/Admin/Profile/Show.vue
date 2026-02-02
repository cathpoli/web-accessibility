<script setup>
import AdminLayout from '@/App/Layouts/AdminLayout.vue'
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm, usePage } from '@inertiajs/inertia-vue3';

const user = usePage().props.value.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <Head title="Profile" />

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profile</h2>
        </template>

        <div class="py-12">
            <div class="max-w-8xl px-6 space-y-5">
                <div>
                    <h1 class="text-3xl font-bold blue-text">Profile</h1>
                </div>
                <div class="p-4 sm:p-8 bg-white shadow-lg rounded-lg">
                    <section>
                        <header>
                            <h2 class="text-lg font-bold text-gray-900">Admin Profile Information</h2>

                            <p class="mt-1 text-sm text-gray-600">
                                Update your account's profile information and email address.
                            </p>
                        </header>

                        <form @submit.prevent="form.patch(route('admin.profile.update'))" class="mt-6 space-y-6">
                            <div>
                                <InputLabel for="name" class="font-semibold">
                                    Name <span class="text-red-500">*</span>
                                </InputLabel>

                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 teal-border bg-transparent w-full"
                                    placeholder="Enter your name"
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
                                    class="mt-1 teal-border bg-transparent w-full"
                                    v-model="form.email"
                                    placeholder="Enter your email address"
                                    autocomplete="email"
                                />

                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>

                            <!-- <div v-if="props.mustVerifyEmail && user.email_verified_at === null">
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
                            </div> -->

                            <div class="flex items-center gap-4">
                                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                                </Transition>
                            </div>
                        </form>
                    </section>
                </div>

                <!-- <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdatePasswordForm class="max-w-xl" />
                </div> -->

                <!-- <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <DeleteUserForm class="max-w-xl" />
                </div> -->
            </div>
        </div>
    </AdminLayout>
</template>

<style>
  .sidebar-profile {
    color: #02C9B2;
    fill: #02C9B2;
  }
</style>
