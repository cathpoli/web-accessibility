<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import Tap from "@tapfiliate/tapfiliate-js";
import { Link } from "@inertiajs/inertia-vue3";

const showingNavigationDropdown = ref(false);

const account_id = import.meta.env.VITE_TAPFILIATE_ACCOUNT_ID;
Tap.init(account_id, { integration: "javascript" });
// Tap.detect();

const notificationCount = ref(0);
const notifications = ref([]
//     [
//     {
//         message: "Hey Jay",
//         isRead: false, // Set to true if the notification is read
//         timestamp: new Date("2023-10-15T10:30:00"), // Sample timestamp (replace with your actual date)
//     },
//     {
//         message: "New message from Sarah",
//         isRead: true, // Set to true if the notification is read
//         timestamp: new Date("2023-10-15T14:45:00"), // Sample timestamp (replace with your actual date)
//     },
//     {
//         message: "You have a meeting at 3 PM",
//         isRead: false, // Set to true if the notification is read
//         timestamp: new Date("2023-10-16T15:00:00"), // Sample timestamp (replace with your actual date)
//     },
//     // Add more notifications as needed
// ]
);
const modalVisible = ref(false);

const notificationIconRef = ref(null);
const notificationModalRef = ref(null);

// Function to toggle the notification modal
const toggleNotificationModal = () => {
    modalVisible.value = !modalVisible.value;
};

// Close the modal when clicking outside of it
const closeOnOutsideClick = (event) => {
    if (
        notificationIconRef.value &&
        notificationModalRef.value &&
        modalVisible.value &&
        !notificationIconRef.value.contains(event.target) &&
        !notificationModalRef.value.contains(event.target)
    ) {
        modalVisible.value = false;
    }
};

onMounted(() => {
    // Add a click event listener to the document
    document.addEventListener("mousedown", closeOnOutsideClick);
});

onUnmounted(() => {
    // Remove the click event listener when the component is unmounted
    document.removeEventListener("mousedown", closeOnOutsideClick);
});
</script>

<template>
    <div>
        <vue-notification-list position="top-right"></vue-notification-list>
        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <img
                                    src="../../../../public/assets/images/ace-ada.ico"
                                    class="w-7"
                                />
                                <Link :href="route('dashboard')"> AceADA </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div
                                class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"
                            >
                                <NavLink
                                    :href="route('dashboard')"
                                    :active="route().current('dashboard')"
                                >
                                    Dashboard
                                </NavLink>
                                <!-- <NavLink :href="route('domains.index')" :active="route().current('domains.index')">
                                    Domains & Subscriptions
                                </NavLink> -->
                                <NavLink
                                    v-if="
                                        $page.props.auth.user.type == 'client'
                                    "
                                    :href="route('billing.index')"
                                    :active="route().current('billing.index')"
                                >
                                    Billing Information
                                </NavLink>
                                <NavLink :href="route('audit')" :active="route().current('audit')">
                                    Accessibility Audit
                                </NavLink>
                                <NavLink
                                    :href="route('installation.index')"
                                    :active="
                                        route().current('installation.index')
                                    "
                                >
                                    Installation
                                </NavLink>
                                <NavLink
                                    :href="route('documentation.index')"
                                    :active="
                                        route().current('documentation.index')
                                    "
                                >
                                    Documentation
                                </NavLink>
                                <NavLink
                                    :href="route('profile.edit')"
                                    :active="route().current('profile.edit')"
                                >
                                    Profile
                                </NavLink>
                                <!-- <NavLink :href="route('sample.index')" :active="route().current('sample.index')">
                                    Sample
                                </NavLink> -->
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <!-- Notification Dropdown -->
                            <div v-if="notifications.length" class="relative">
                                <!-- Notification Icon -->
                                <div
                                    class="cursor-pointer relative group"
                                    @click="toggleNotificationModal"
                                    ref="notificationIcon"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-gray-600"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M5.25 9a6.75 6.75 0 0113.5 0v.75c0 2.123.8 4.057 2.118 5.52a.75.75 0 01-.297 1.206c-1.544.57-3.16.99-4.831 1.243a3.75 3.75 0 11-7.48 0 24.585 24.585 0 01-4.831-1.244.75.75 0 01-.298-1.205A8.217 8.217 0 005.25 9.75V9zm4.502 8.9a2.25 2.25 0 104.496 0 25.057 25.057 0 01-4.496 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    <div v-if="notifications.length"
                                        class="h-2 w-2 bg-red-500 rounded-full absolute top-0 right-0"
                                    ></div>
                                    <!-- Notification Modal (hidden by default) -->
                                    <div
                                        class="absolute z-40 right-0 mt-2 w-72 bg-white border border-gray-300 rounded-lg shadow-lg p-4"
                                        :class="{ hidden: !modalVisible }"
                                        ref="notificationModal"
                                    >
                                        <div class="py-2">
                                            <h2
                                                class="text-xl font-semibold text-gray-800 mb-4"
                                            >
                                                Notifications {{  notifications.length ? `(${notifications.length})` : '' }}
                                            </h2>
                                            <ul
                                                id="notificationList"
                                                class="mt-2 space-y-2"
                                            >
                                                <li
                                                    v-for="(
                                                        notification, index
                                                    ) in notifications"
                                                    :key="index"
                                                    :class="{
                                                        'bg-gray-100':
                                                            !notification.isRead,
                                                    }"
                                                    class="px-2 py-1 rounded-md hover:bg-gray-200 cursor-pointer transition-colors duration-300"
                                                    @click="markAsRead(index)"
                                                >
                                                    <div
                                                        class="flex items-center"
                                                    >
                                                        <div>
                                                            <p
                                                                class="text-gray-800 text-sm"
                                                            >
                                                                {{
                                                                    notification.message
                                                                }}
                                                            </p>
                                                            <p
                                                                class="text-sm text-gray-500"
                                                            >
                                                                {{
                                                                    notification.timestamp.toLocaleString()
                                                                }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <!-- <div class="mt-4 flex justify-end">
                                            <button
                                                class="text-gray-600 hover:text-red-500"
                                                @click="clearNotifications"
                                            >
                                                Clear All
                                            </button>
                                        </div> -->
                                    </div>
                                </div>
                            </div>

                            <!-- Settings Dropdown -->
                            <div class="ml-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                            >
                                                <p>
                                                    {{
                                                        $page.props.auth.user
                                                            .name
                                                    }}
                                                </p>

                                                <svg
                                                    class="ml-2 -mr-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink
                                            :href="route('profile.edit')"
                                        >
                                            Profile
                                        </DropdownLink>
                                        <DropdownLink
                                            v-if="
                                                $page.props.auth.user.type ==
                                                'client'
                                            "
                                            :href="route('accounts.index')"
                                        >
                                            Account
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                        >
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                        >
                            Dashboard
                        </ResponsiveNavLink>
                        <!-- <ResponsiveNavLink :href="route('domains.index')" :active="route().current('domains.index')">
                            Domains & Subscriptions
                        </ResponsiveNavLink> -->
                        <ResponsiveNavLink
                            :href="route('billing.index')"
                            :active="route().current('billing.index')"
                        >
                            Billing Information
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('audit')" :active="route().current('audit')">
                            Accessibility Audit
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('installation.index')"
                            :active="route().current('installation.index')"
                        >
                            Installation
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('documentation.index')"
                            :active="route().current('documentation.index')"
                        >
                            Documentation
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('profile.edit')"
                            :active="route().current('profile.edit')"
                        >
                            Profile
                        </ResponsiveNavLink>
                        <!-- <ResponsiveNavLink :href="route('sample.index')" :active="route().current('sample.index')">
                            Sample
                        </ResponsiveNavLink> -->
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">
                                {{ $page.props.auth.user.email }}
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')">
                                Profile
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                v-if="$page.props.auth.user.type == 'client'"
                                :href="route('accounts.index')"
                            >
                                Account
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
