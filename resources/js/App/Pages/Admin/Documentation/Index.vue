<script setup>
import AdminLayout from '@/App/Layouts/AdminLayout.vue'
import BigCommerce from './Steps/BigCommerce.vue'
import Volusion from './Steps/Volusion.vue'
import Duda from './Steps/Duda.vue'
import Custom from './Steps/Custom.vue'
import Weebly from './Steps/Weebly.vue'
import Shopify from './Steps/Shopify.vue'
import HubSpot from './Steps/HubSpot.vue'
import GoogleTagManager from './Steps/GoogleTagManager.vue'
import Squarespace from './Steps/Squarespace.vue'
import Wordpress from './Steps/Wordpress.vue'
import Wix from './Steps/Wix.vue'
import Webflow from './Steps/Webflow.vue'
import Joomla from './Steps/Joomla.vue'

import { ref } from 'vue'
import { Head } from '@inertiajs/inertia-vue3';

var showWordpress = ref(false)
var showWix = ref(false)
var showWebflow = ref(false)
var showBigCommerce = ref(false)
var showCustom = ref(false)
var showYahoo = ref(false)
var showWeebly = ref(false)
var showVolusion = ref(false)
var showSquarespace = ref(false)
var showShopify = ref(false)
var showHubspot = ref(false)
var showGTM = ref(false)
var showDuda = ref(false)
var showJoomla = ref(false)

const generateDocs = (event) => {
    const key = event.target.value

    showWordpress.value = false
    showWix.value = false
    showWebflow.value = false
    showBigCommerce.value = false
    showCustom.value = false
    showYahoo.value = false
    showWeebly.value = false
    showVolusion.value = false
    showSquarespace.value = false
    showShopify.value = false
    showHubspot.value = false
    showGTM.value = false
    showDuda.value = false
    showJoomla.value = false

    switch (key) {
        case 'bigcommerce':
            showBigCommerce.value = true
            break;
        case 'volusion':
            showVolusion.value = true
            break;
        case 'duda':
            showDuda.value = true
            break;
        case 'custom':
            showCustom.value = true
            break;
        case 'weebly':
            showWeebly.value = true
            break;
        case 'shopify':
            showShopify.value = true
            break;
        case 'hubspot':
            showHubspot.value = true
            break;
        case 'gtm':
            showGTM.value = true
            break;
        case 'squarespace':
            showSquarespace.value = true
            break; 
        case 'wordpress':
            showWordpress.value = true
            break;
        case 'wix':
            showWix.value = true
            break;  
        case 'webflow':
            showWebflow.value = true
            break;
        case 'joomla':
            showJoomla.value = true
            break;   
    
        default:
            break;
    }
}

</script>
<style>
    ol {
        list-style: revert;
        padding: 10px 10px 10px 25px;
    }

    ul {
        list-style: disc outside none;
    }

    a {
        color: blue;
        background-color: transparent;
    }

    ol li {
        border: solid .5px #E4E4E4;
        border-bottom: 0px;
        padding: 15px;
        font-size: 1.25rem; /* 20px */
        line-height: 1.5rem; /* 28px */
    }
    ul li {
        border: none;
        padding: 0px;
        font-size: 16px;
    }
    .last-step {
        border-bottom: solid .5px #E4E4E4;
    }
    /*content*/
    .installation-link {
        color: blue;
        background-color: transparent;
    }
    h4 {
        color: rgb(17 24 39);
        font-weight: 600;
        font-size: 1.875rem;
        line-height: 2.25rem;
    }
    @media screen and (max-width: 768px) {
        h4 {
            text-align: center;
        }
    }
    
    .step-header {
        display: flex;
        align-items: center;
    }
    .marker-icon {
        display: inline-flex;
        min-width: 30px; /* Adjust the width and height as needed */
        height: 30px;
        background-color: #01A249; /* Add your desired background color */
        color: white;
        border-radius: 50%; /* Make it a circle by setting border-radius to 50% */;
        justify-content: center;
        align-items: center;
        margin-right: 13px;
    }
    img {
        margin: 10px 0px;
    }

    select {
        width: 100%;
    }

    .sidebar-documentation {
        color: #02C9B2;
        fill: #02C9B2;
    }
</style>
<template>
    <Head title="Installation" />

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Documentation</h2>
        </template>

        <div class="p-5 lg:py-12">
            <div>
                <h1 class="text-3xl font-bold blue-text">Documentation</h1>
            </div>
            <div class="max-w-8xl mt-5">
                <div class="bg-white rounded-2xl blue-shadow">
                    <div id="documentations-header" class="flex flex-col-reverse lg:flex-row justify-between items-center p-3 pt-10 md:p-10 md:pb-0">
                        <div class="pb-10">
                            <header class="text-center lg:text-start">
                                <h2 class="text-3xl font-semibold text-gray-900">Integrate AceADA to Your Website</h2>
                                <p class="mt-1 text-lg text-gray-600">
                                    Before you begin, you'll need your AceADA Script. <br class="xl:hidden">
                                    You can find it under the <a :href="route('installation.index')" class="installation-link">Installation</a> tab in your AceADA Client Portal.
                                </p>
                            </header>

                            <div class="pt-4 flex flex-row">
                                <select class="select select-primary lg:max-w-xs teal-border mx-auto lg:mx-0" @change="generateDocs">
                                    <option disabled selected>Select Integration</option>
                                    <option value="bigcommerce">BigCommerce</option>
                                    <option value="custom">Custom Website (Tailwind & Bootstrap)</option>
                                    <option value="duda">Duda</option>
                                    <option value="gtm">Google Tag Manager</option>
                                    <option value="hubspot">HubSpot</option>
                                    <option value="joomla">Joomla</option>
                                    <option value="shopify">Shopify</option>
                                    <option value="squarespace">Squarespace</option>
                                    <option value="volusion">Volusion</option>
                                    <option value="webflow">Webflow</option>
                                    <option value="weebly">Weebly</option>
                                    <option value="wix">WiX</option>
                                    <option value="wordpress">Wordpress</option>
                                </select>
                            </div>
                        </div>

                        <div class="">
                            <img class="relative right-5 md:right-0" src="../../../../../../public\assets\images\admin-documentations-header-image.png" alt="Documentations Header Image"> 
                        </div>
                    </div>
                </div>

                <div class="mt-5">
                    <BigCommerce v-if="showBigCommerce"></BigCommerce>
                    <Volusion v-if="showVolusion"></Volusion>
                    <Duda v-if="showDuda"></Duda>
                    <Custom v-if="showCustom"></Custom>
                    <Weebly v-if="showWeebly"></Weebly>
                    <Shopify v-if="showShopify"></Shopify>
                    <HubSpot v-if="showHubspot"></HubSpot>
                    <GoogleTagManager v-if="showGTM"></GoogleTagManager>
                    <Squarespace v-if="showSquarespace"></Squarespace>
                    <Wordpress v-if="showWordpress"></Wordpress>
                    <Wix v-if="showWix"></Wix>
                    <Webflow v-if="showWebflow"></Webflow>
                    <Joomla v-if="showJoomla"></Joomla>
                </div>
            </div>
        </div>

    </AdminLayout>
</template>