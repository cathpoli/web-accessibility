<script setup>
import AdminLayout from '@/App/Layouts/AdminLayout.vue'
import { Head } from "@inertiajs/inertia-vue3";
import WidgetCustomization from '@/Components/WidgetCustomization.vue'

const props = defineProps({
    domain: Object,
});

</script>

<template>
    <Head title="Whitelist Widget Configuration" />

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Whitelist Widget Configuration
            </h2>
        </template>

        <div class="p-5 lg:py-12">
            <div class="mb-4">
                <h1 class="text-3xl font-bold blue-text">Whitelist Widget Configuration</h1>
            </div>

            <div class="bg-white rounded-lg p-6 mb-4">
                <h1 class="pb-0 font-bold text-xl"><a :href="`https://${domain.name}`" class="font-bold text-blue-500" target="_blank"> {{ domain.name }} </a> Widget Configuration</h1>
                <div>
                    <p>
                        This page is for customizing the widgets on a <a :href="`https://${domain.name}`" target="_blank" class="font-bold">{{ domain.name }} </a>
                    </p>
                </div>
            </div>

            <div class="max-w-8xl mb-4">
                <WidgetCustomization :domainId="domain.id" :domainName="domain.name" />
            </div>
        </div>
    </AdminLayout>
</template>
