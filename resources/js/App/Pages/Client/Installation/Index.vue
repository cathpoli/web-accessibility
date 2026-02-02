<script setup>
import AuthenticatedLayout  from "@/App/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/inertia-vue3";
import { ref } from "vue";
import useClipboard from "vue-clipboard3";
import { useNotificationStore } from "@dafcoe/vue-notification";
import WidgetCustomization from '@/Components/WidgetCustomization.vue'

const { setNotification } = useNotificationStore();
const { toClipboard } = useClipboard();

const props = defineProps({
    url: String,
    domains: Object,
});

const showGeneratedScript = ref(false);
const selectedDomain = ref({});
const script = ref(null);

// I needed to add this variable since vue reads the string end tag of script element as the end of script setup.... if that makes sense
const scriptElement = "script";

/** TODO: compress / decompress functionality of the script */
const generateScript = (event) => {
    showGeneratedScript.value = false;

    let domainId = parseInt(event.target.options[event.target.selectedIndex].dataset.id)
    let domainName = event.target.options[event.target.selectedIndex].dataset.name

    selectedDomain.value = {
        id: domainId,
        name: domainName,
        token: event.target.value,
    }

    /** pretend we're processing the script */
    setTimeout(() => {
        /** reference for defer: https://www.growingwiththeweb.com/2014/02/async-vs-defer-attributes.html#script-defer */
        script.value = `<${scriptElement}> (function() {var s = document.createElement('script');var h = document.querySelector('head') || document.body;s.setAttribute('data-token', '${selectedDomain.value.token}');s.src = '${props.url}/dist/aceada.js';s.setAttribute('aceada-script', '');s.setAttribute('defer', '');h.appendChild(s);})(); </${scriptElement}>`;
        showGeneratedScript.value = true;
    }, 500);
};

const copyScript = async () => {
    try {
        await toClipboard(`${script.value}`);

        // toast.success(`Script copied to clipboard`)
        setNotification({
            type: "success",
            message: `Script copied to clipboard.`,
        });
    } catch (e) {
        console.error(e);
    }
};

</script>

<template>
    <Head title="Installation" />

    <AuthenticatedLayout >
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Installation
            </h2>
        </template>

            <div class="p-0 md:px-5 pb-3 lg:p-1">
                <div class="max-w-7xl mx-auto my-5 lg:px-8">
                    <div class="p-3 bg-white shadow-sm sm:rounded-lg md:p-6">
                        <h1 class="pb-0 font-bold text-xl">Quick Installation</h1>
                        <div class="mt-5" v-if="domains.length < 0">
                            <span class="text-xs text-red-600 italic bold">
                                You don't have subscription yet, head over the
                                domains tab to subscribe a domain to use our web
                                accessibility features.
                            </span>
                        </div>
                        <div v-else>
                            <p class="pt-3 pb-5">
                                Copy the installation code below and add it inside
                                the body tag of your website just before the end
                                tag.
                            </p>
                            <div class="flex flex-row">
                                <select
                                    class="select select-domain teal-border"
                                    @change="generateScript"
                                >
                                    <option disabled selected>
                                        Select domain to generate script
                                    </option>
                                    <option
                                        v-for="(domain, index) in domains"
                                        :key="index"
                                        :value="domain.token"
                                        :data-id="domain.id"
                                        :data-name="domain.name"
                                    >
                                        {{ domain.name }}
                                    </option>
                                </select>
                                <button
                                    class="btn btn-ghost teal-border capitalize ml-2"
                                    v-if="showGeneratedScript"
                                    @click="copyScript"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="w-6 h-6"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184"
                                        />
                                    </svg>
                                    <span class="hidden lg:block">Copy Script</span>
                                </button>
                            </div>
                            <div
                                class="flex justify-start gap-4 text-gray-900 pt-4"
                                v-if="showGeneratedScript"
                            >
                                <highlightjs class="flex-1 flex overflow-auto w-0 max-w-[100%]" autodetect :code="script" />
                            </div>
                        </div>
                    </div>

                    <WidgetCustomization :domainId="selectedDomain.id" :domainName="selectedDomain.name" v-if="showGeneratedScript" />
                </div>
            </div>
    </AuthenticatedLayout >
</template>

<style>
    .select-domain {
        width: 73%;
    }

    .select:focus {
        outline: none;
    }
</style>
