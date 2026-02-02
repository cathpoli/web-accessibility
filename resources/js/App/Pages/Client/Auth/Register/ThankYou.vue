<script setup>
import { onMounted, ref } from 'vue'
import RegisterLayout from '@/App/Layouts/RegisterLayout.vue';
import Tap from "@tapfiliate/tapfiliate-js";

var timer = ref(10)
const account_id = import.meta.env.VITE_TAPFILIATE_ACCOUNT_ID
const props = defineProps({
    redirect: String,
    customerId: String,
    //amount: Number,
});

Tap.init(account_id);
Tap.trial(props.customerId)

onMounted(() => {
    const redirectInterval = setInterval(() => {
        if (timer.value === 0) {
            window.location.href = props.redirect
            clearInterval(redirectInterval);
        } else {
            timer.value -= 1
        }
    }, 1000)
})
</script>

<template>
    <RegisterLayout>
        <Head title="Welcome" />

        <!-- <div class="p-5 lg:py-12"> -->
            <!-- <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6"> -->
                <div class="z-10 w-full md:max-w-3xl bg-white drop-shadow-xl rounded-3xl p-5 py-10 md:py-20 text-center mt-20">
                    <h1 class="blue-text font-bold text-4xl md:text-5xl"> Great Work! </h1>
                    <h2 class="text-base md:text-2xl mt-3 font-semibold"> You have successfully registered your first domain. <br> You may now enjoy our services. </h2>
                    <div class="flex flex-col md:flex-row items-center justify-center mt-14 space-y-3 md:space-y-0">
                        <a :href="props.redirect" id="proceed-to-dashboard" class="btn teal-btn capitalize">Proceed to Dashboard</a>
                        <small class="m-0 md:ml-4"> Will automatically redirect in {{ timer }} </small>
                    </div>
                </div>

                <svg id="flash-big-svg" width="75" height="129" viewBox="0 0 75 129" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M10.9571 126C25.3611 109.26 39.1301 93.3291 52.8178 76.0789C59.3466 67.8511 65.9082 58.0831 72 49.5551C58.552 48.4567 51.2168 49.6896 37.8122 48.4608C37.8122 48.4608 43.2107 22.011 46.3913 3C26.6883 21.9003 10.7331 54.0404 3 72.211C12.4572 72.7189 22.1264 73.0989 31.6 72.8608C24.9582 90.6366 17.8285 108.311 10.9571 126Z" stroke="#DFF8F4" stroke-width="5.33333" stroke-miterlimit="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <svg id="sparkle-big-svg" width="145" height="193" viewBox="0 0 145 193" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M81.0502 179.146C81.7742 151.822 70.6329 124.573 46.2031 108.128C36.7432 101.76 25.5367 99.4414 14.5001 97.5143C59.3369 95.979 74.9026 49.1326 81.141 12.8672C78.9747 45.3926 96.0088 80.525 130.82 83.7669C96.1664 104.118 84.273 141.464 81.0502 179.146Z" stroke="white" stroke-width="5.33333" stroke-miterlimit="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            <!-- </div> -->
        <!-- </div> -->
    </RegisterLayout>
</template>

<style>
    #flash-big-svg {
        position: fixed;
        right: 0rem;
        margin-right: 1rem;
    }

    #sparkle-big-svg {
        position: fixed;
        left: 0rem;
        margin-top: 23rem;
    }
    @media screen and (min-width: 1440px) {
        #flash-big-svg {
            position: fixed;
            right: 25rem;
            margin-right: 1rem;
        }
    }
    @media screen and (min-width: 1440px) {
        #sparkle-big-svg {
            position: fixed;
            left: 25rem;
            margin-top: 23rem;
        }
    }
</style>
