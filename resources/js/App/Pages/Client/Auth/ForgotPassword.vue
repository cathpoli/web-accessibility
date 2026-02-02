<script setup>
import GuestLayout from '@/App/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <div class="mb-4 space-y-6">
            <h1 class="text-2xl">
                Forgot your password?
            </h1>  
            <p>
                No problem. Just let us know your email address and we will email you a password reset
                link that will allow you to choose a new one.
            </p>
        </div>

        <div v-if="status" class="mb-6 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div class="mt-6">
                <InputLabel for="email" value="Email" class="font-semibold"/>

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full shadow-sm border-0 rounded-md focus-within:ring-indigo-500 focus-within:ring-2 focus-within:border-transparent"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="flex items-center justify-center mt-6">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Email Password Reset Link
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
