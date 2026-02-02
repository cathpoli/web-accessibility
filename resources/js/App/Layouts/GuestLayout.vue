<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/inertia-vue3';
import Tap from "@tapfiliate/tapfiliate-js";

const account_id = import.meta.env.VITE_TAPFILIATE_ACCOUNT_ID

Tap.init(account_id, { integration: 'javascript' });
// Tap.detect();

</script>

<template>
    <vue-notification-list position="top-right"></vue-notification-list>
    <div id="admin-login" class="min-h-screen flex justify-center items-center">
        <!-- <div>
            <Link href="/">
                <ApplicationLogo class="w-20 h-20 fill-current text-gray-500" />
            </Link>
        </div> -->

        <div class="w-full max-w-lg p-8 pt-0 md:p-10 bg-transparent md:bg-white/30 rounded-2xl md:shadow-lg">
            <slot />
        </div>
    </div>
</template>

<style>
    #admin-login {
        background: linear-gradient(0deg, #DFF8F4, #FFFFFF);
    }
</style>


