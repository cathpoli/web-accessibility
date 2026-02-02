<script setup>
import AuthenticatedLayout from '@/App/Layouts/AuthenticatedLayout.vue';
import CardForm from '@/Components/CardForm.vue';
import { Head } from '@inertiajs/inertia-vue3';

const props = defineProps({
    stripe_key: String,
    intent: Object,
});
</script>

<template>
    <Head title="Add Card" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add Card</h2>
        </template>

        <div class="p-5 lg:py-12">
            <div class="flex flex-col sm:justify-center items-center bg-gray-100">
                <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                    <CardForm :stripe_key="stripe_key" :intent="intent" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
