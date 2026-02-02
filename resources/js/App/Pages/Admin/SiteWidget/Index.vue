<script setup>
import AdminLayout from '@/App/Layouts/AdminLayout.vue'
import { Head } from "@inertiajs/inertia-vue3";
import WidgetCustomization from '@/Components/WidgetCustomization.vue'

const props = defineProps({
    domain: Object,
});

</script>

<template>
    <Head title="Site Widget" />

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Site Widget
            </h2>
        </template>

        <div class="p-5 lg:py-12">
            <div class="mb-4">
                <h1 class="text-3xl font-bold blue-text">Site Widget</h1>
            </div>

            <div class="bg-white rounded-lg p-6 mb-4">
                <h1 class="pb-0 font-bold text-xl">AceADA Widget Customization</h1>
                <div>
                    <p> This page is dedicated for customizing the widgets on <strong> admin.acecomply.com </strong> and <strong> register.aceada.com</strong>. You may have to refresh the page after saving.</p>
                </div>
            </div>

            <div class="max-w-8xl mb-4">
                <WidgetCustomization :domainId="domain.id" :domainName="domain.name" />
            </div>
        </div>
    </AdminLayout>
</template>
