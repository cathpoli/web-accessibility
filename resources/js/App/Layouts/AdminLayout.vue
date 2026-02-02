<script setup>
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { ref, computed, onMounted } from 'vue';

//sidebar is hidden at default
const showWebSidebar = ref(false);
const showMobileSidebar = ref(false);

// load sidebar state from local storage on component mount
onMounted(() => {
  const savedState = localStorage.getItem('sidebarState');
  if (savedState) {
    const { isWebExpanded } = JSON.parse(savedState);
    showWebSidebar.value = isWebExpanded;
  }
});

// toggles the sidebar on bigger screens and saves it in the local storage
const toggleSidebar = () => {
  showWebSidebar.value = !showWebSidebar.value;
  saveSidebarState();
};

// expands the sidebar through classes
const sidebarClasses = computed(() => ({
  sidebar: true,
  'w-[54px]': !showWebSidebar.value,
  'w-[280px]': showWebSidebar.value,
}));

// toggles the sidebar on small screens
const toggleMobileSidebar = () => {
  showMobileSidebar.value = !showMobileSidebar.value;
};

// show mobile sidebar through classes
const mobileSidebarClasses = computed(() => ({
  '-left-0': !showMobileSidebar.value,
  '-left-[100rem]': showMobileSidebar.value,
}));

// save sidebar state to local storage
const saveSidebarState = () => {
  const state = {
    isWebExpanded: showWebSidebar.value,
  };
  localStorage.setItem('sidebarState', JSON.stringify(state));
};

// Hide the mobile sidebar by default
toggleMobileSidebar();

</script>

<template>
    <vue-notification-list position="top-right"></vue-notification-list>
    <div class="flex min-h-screen bg-gray-100">
        <div class="hidden md:block sidebar min-h-screen border-r bg-slate-800 hover:shadow-lg transition-all" :class="sidebarClasses">
            <div class="flex h-screen flex-col justify-between pb-6 overflow-hidden">
                <div>
                    <div class="w-max mx-auto p-1 pl-1.5">
                        <a href="/admin/dashboard" class="flex items-center gap-3">
                            <svg class="mx-auto" width="40" height="38" viewBox="0 0 52 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20.1281 32.2668C23.9429 32.2668 27.5591 32.2668 31.3615 32.2668C29.4742 28.9831 27.6529 25.8141 25.7452 22.4945C23.8487 25.7936 22.0282 28.9615 20.1281 32.2668ZM10.3373 49.5125C8.38052 48.7636 6.31481 48.1952 4.51489 47.1637C1.33876 45.3435 0.024286 42.3051 0.000202018 38.718C-0.0151607 36.4223 0.848067 34.3202 1.77326 32.2693C4.59353 26.0176 8.1765 20.1875 11.8359 14.4163C15.0576 9.33511 23.8611 8.08329 27.6609 14.8475C28.8429 16.9514 30.0712 19.029 31.275 21.1205C33.2648 24.5769 35.2536 28.0336 37.2391 31.4926C37.4573 31.8725 37.5963 32.2572 38.2075 32.075C39.7945 31.6021 40.9776 30.2789 40.7101 28.6478C40.4612 27.1314 39.9244 25.5992 39.2081 24.2361C37.6189 21.2127 35.8582 18.279 34.1289 15.3317C32.4131 12.4068 30.6448 9.5097 27.9397 7.35819C25.6218 5.51451 22.9927 4.71107 20.0551 5.24474C18.5703 5.5145 17.1172 5.9607 15.6498 6.32709C16.6622 5.05515 17.7682 3.84176 19.0494 2.85751C23.8925 -0.863529 29.2917 -0.350361 33.3893 4.16166C34.153 5.0028 34.9434 5.85601 35.5222 6.82343C38.926 12.5126 42.327 18.2051 45.6141 23.9627C47.5667 27.3825 46.957 31.8832 44.3614 34.8637C42.5463 36.9475 40.1999 37.8319 37.496 37.8362C31.0057 37.8465 24.5147 37.8505 18.0244 37.8161C17.2067 37.8117 16.8976 38.0786 16.8337 38.8732C16.6932 40.6235 17.3676 41.9196 19.0126 42.5338C19.938 42.879 20.9513 43.129 21.9326 43.1554C25.1416 43.241 28.3557 43.2641 31.5651 43.1964C34.3691 43.1371 37.196 43.1147 39.9664 42.7359C45.6083 41.9647 48.7425 38.9435 49.951 33.486C50.0773 32.9157 50.1678 32.337 50.3123 31.5618C50.5298 31.9293 50.6904 32.1028 50.7422 32.3048C51.0764 33.5995 51.476 34.8861 51.6895 36.2019C52.6233 41.961 49.4913 46.7256 43.8644 48.0781C42.408 48.428 40.9002 48.7172 39.4103 48.7472C33.1594 48.8734 26.9056 48.857 20.6539 48.958C14.2012 49.062 8.04991 41.7381 12.4938 34.4867C15.8021 29.0882 18.8301 23.5172 22.0202 18.0447C22.4311 17.3398 22.2997 16.9899 21.6523 16.6183C20.3967 15.898 19.1323 15.8405 17.9788 16.7098C17.2165 17.2845 16.4779 17.9796 15.96 18.7731C14.8083 20.5381 13.7565 22.3734 12.7463 24.2251C10.8226 27.7514 8.70193 31.1994 7.12589 34.8795C4.72727 40.4804 6.00617 44.8812 10.6448 48.7977C10.8864 49.0016 11.1164 49.2193 11.5578 49.6154C10.9657 49.5715 10.6214 49.6212 10.3373 49.5125Z" fill="url(#paint0_linear_4_20)"/>
                                <defs>
                                    <linearGradient id="paint0_linear_4_20" x1="0" y1="49.6154" x2="51.8801" y2="0.413455" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#03C7B4"/>
                                        <stop offset="1" stop-color="#2088E8"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                            <svg class="mx-auto my-5" width="140" height="25" viewBox="0 0 180 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.0238342 30.8289L13.447 0.242235H18.4328L31.856 30.8289H26.231L23.1629 23.4371H8.71695L5.52095 30.8289H0.0238342ZM10.6346 18.8491H21.1174L15.876 6.74188L10.6346 18.8491Z" fill="white"/>
                                <path d="M31.6003 19.4853C31.6003 17.3188 32.1116 15.4071 33.1343 13.4954C34.1571 11.7112 35.5633 10.1819 37.353 9.16234C39.1428 8.01534 41.1882 7.50556 43.4894 7.50556C45.5348 7.50556 47.3246 7.8879 48.7308 8.52512C50.137 9.16234 51.5433 10.1819 52.6938 11.4563L49.37 15.0248C48.4751 14.1327 47.5802 13.368 46.6854 12.8582C45.7905 12.3484 44.6399 12.0935 43.4894 12.0935C41.5718 12.0935 40.0377 12.8582 38.7593 14.2601C37.4809 15.662 36.8417 17.4462 36.8417 19.4853C36.8417 21.5244 37.4809 23.3086 38.7593 24.7105C40.0377 26.1124 41.6996 26.8771 43.745 26.8771C44.8956 26.8771 45.9183 26.6222 46.941 26.1124C47.8359 25.6026 48.8586 24.838 49.7535 23.9459L52.9495 27.132C51.6711 28.5339 50.3927 29.5534 48.8586 30.3181C47.4524 31.0827 45.6626 31.4651 43.4894 31.4651C41.1882 31.4651 39.2706 30.9553 37.353 29.8083C35.5633 28.7888 34.1571 27.2594 33.1343 25.4752C32.1116 23.5635 31.6003 21.6519 31.6003 19.4853Z" fill="white"/>
                                <path d="M56.4014 19.4853C56.4014 17.3188 56.9127 15.2797 57.8076 13.4954C58.7025 11.7112 60.1087 10.1819 61.7706 9.16234C63.5604 8.14279 65.478 7.50556 67.5234 7.50556C68.9297 7.50556 70.3359 7.76045 71.4865 8.14278C72.637 8.52512 73.6598 9.16233 74.5546 9.927C75.4495 10.6917 76.0887 11.5838 76.7279 12.6033C77.3671 13.6229 77.7506 14.7699 78.0063 16.0443C78.262 17.3188 78.3898 18.5932 78.3898 19.8677C78.3898 20.5049 78.3898 21.0147 78.262 21.2695H61.3871C61.6428 23.0538 62.4098 24.5831 63.5604 25.6026C64.711 26.6222 66.245 27.132 67.907 27.132C69.1854 27.132 70.2081 26.8771 71.2308 26.4947C72.2535 26.1124 73.1484 25.3478 74.1711 24.4557L77.2393 27.132C74.8103 30.0632 71.7422 31.4651 67.7791 31.4651C65.6058 31.4651 63.5604 30.9553 61.7706 29.9357C59.9809 28.9162 58.5746 27.5143 57.5519 25.7301C57.0406 23.8184 56.4014 21.7793 56.4014 19.4853ZM61.6428 17.8285H73.4041C73.2762 16.0443 72.637 14.6424 71.6143 13.4954C70.5916 12.3484 69.3132 11.8387 67.5234 11.8387C65.9894 11.8387 64.5831 12.3484 63.5604 13.4954C62.6655 14.515 61.8985 16.0443 61.6428 17.8285Z" fill="white"/>
                                <path d="M79.4126 30.8289L92.8358 0.242235H97.8215L111.245 30.8289H105.492L102.424 23.4371H87.9778L84.7818 30.8289H79.4126ZM90.0233 18.8491H100.506L95.2647 6.74188L90.0233 18.8491Z" fill="white"/>
                                <path d="M116.358 30.8279V0.496126H127.736C130.037 0.496126 132.338 0.878451 134.256 1.64312C136.301 2.40778 137.963 3.42734 139.369 4.82922C140.776 6.23111 141.926 7.76044 142.693 9.67211C143.46 11.5838 143.844 13.4954 143.844 15.662C143.844 17.8285 143.46 19.7402 142.693 21.6519C141.926 23.5635 140.776 25.0929 139.369 26.4947C137.963 27.8966 136.301 28.9162 134.256 29.6809C132.21 30.4455 130.037 30.8279 127.736 30.8279H116.358ZM121.727 25.985H127.736C130.932 25.985 133.489 24.9654 135.406 23.0538C137.324 21.1421 138.347 18.5932 138.347 15.662C138.347 12.6033 137.324 10.1819 135.406 8.27021C133.489 6.35855 130.932 5.33901 127.736 5.33901H121.727V25.985Z" fill="white"/>
                                <path d="M147.807 30.8289L161.23 0.242235H166.216L179.639 30.8289H174.014L170.946 23.4371H156.5L153.304 30.8289H147.807ZM158.418 18.8491H168.901L163.659 6.74188L158.418 18.8491Z" fill="white"/>
                            </svg>
                        </a>
                    </div>
                    <hr class="p-3 w-full border-t-white/10 mb-10 mx-auto">
                    <ul class="mt-6 tracking-wide">
                        <li class="min-w-max rounded hover:bg-slate-700">
                            <a :href="route('admin.dashboard')" aria-label="dashboard" class="sidebar-dashboard sidebar-item relative flex items-center space-x-4 px-3 py-3">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" width="24px" viewBox="0 0 24 24"><title>dashboard</title><path d="M13,3V9H21V3M13,21H21V11H13M3,21H11V15H3M3,13H11V3H3V13Z"/></svg>
                                <span class="-mr-1 font-medium">Dashboard</span>
                            </a>
                        </li>
                        <li class="min-w-max rounded hover:bg-slate-700">
                            <a :href="route('admin.clients.index')" aria-label="clients" class="sidebar-clients sidebar-item relative flex items-center space-x-4 px-3 py-3">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" width="24px" viewBox="0 0 24 24"><title>clients</title><path d="M12,5.5A3.5,3.5 0 0,1 15.5,9A3.5,3.5 0 0,1 12,12.5A3.5,3.5 0 0,1 8.5,9A3.5,3.5 0 0,1 12,5.5M5,8C5.56,8 6.08,8.15 6.53,8.42C6.38,9.85 6.8,11.27 7.66,12.38C7.16,13.34 6.16,14 5,14A3,3 0 0,1 2,11A3,3 0 0,1 5,8M19,8A3,3 0 0,1 22,11A3,3 0 0,1 19,14C17.84,14 16.84,13.34 16.34,12.38C17.2,11.27 17.62,9.85 17.47,8.42C17.92,8.15 18.44,8 19,8M5.5,18.25C5.5,16.18 8.41,14.5 12,14.5C15.59,14.5 18.5,16.18 18.5,18.25V20H5.5V18.25M0,20V18.5C0,17.11 1.89,15.94 4.45,15.6C3.86,16.28 3.5,17.22 3.5,18.25V20H0M24,20H20.5V18.25C20.5,17.22 20.14,16.28 19.55,15.6C22.11,15.94 24,17.11 24,18.5V20Z"/></svg>
                                <span class="-mr-1 font-medium">Clients</span>
                            </a>
                        </li>
                        <li class="min-w-max rounded hover:bg-slate-700">
                            <a :href="route('admin.payments.index')" aria-label="payments" class="sidebar-payments sidebar-item relative flex items-center space-x-4 px-3 py-3">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" width="24px" viewBox="0 0 24 24"><title>payments</title><path d="M20 4H4A2 2 0 0 0 2 6V18A2 2 0 0 0 4 20H20A2 2 0 0 0 22 18V6A2 2 0 0 0 20 4M20 11H4V8H20Z"/></svg>
                                <span class="-mr-1 font-medium">Payments</span>
                            </a>
                        </li>
                        <li class="min-w-max rounded hover:bg-slate-700">
                            <a :href="route('admin.invoices.index')" aria-label="invoices" class="sidebar-invoices sidebar-item relative flex items-center space-x-4 px-3 py-3">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" width="24px" viewBox="0 0 24 24"><title>invoices</title><path d="M3,22L4.5,20.5L6,22L7.5,20.5L9,22L10.5,20.5L12,22L13.5,20.5L15,22L16.5,20.5L18,22L19.5,20.5L21,22V2L19.5,3.5L18,2L16.5,3.5L15,2L13.5,3.5L12,2L10.5,3.5L9,2L7.5,3.5L6,2L4.5,3.5L3,2M18,9H6V7H18M18,13H6V11H18M18,17H6V15H18V17Z"/></svg>
                                <span class="-mr-1 font-medium">Invoices</span>
                            </a>
                        </li>
                        <li class="min-w-max rounded hover:bg-slate-700">
                            <a :href="route('admin.coupons.index')" aria-label="coupons" class="sidebar-coupons sidebar-item relative flex items-center space-x-4 px-3 py-3">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" width="24px" viewBox="0 0 24 24"><title>coupons</title><path d="M4,4A2,2 0 0,0 2,6V10C3.11,10 4,10.9 4,12A2,2 0 0,1 2,14V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V14A2,2 0 0,1 20,12C20,10.89 20.9,10 22,10V6C22,4.89 21.1,4 20,4H4M15.5,7L17,8.5L8.5,17L7,15.5L15.5,7M8.81,7.04C9.79,7.04 10.58,7.83 10.58,8.81A1.77,1.77 0 0,1 8.81,10.58C7.83,10.58 7.04,9.79 7.04,8.81A1.77,1.77 0 0,1 8.81,7.04M15.19,13.42C16.17,13.42 16.96,14.21 16.96,15.19A1.77,1.77 0 0,1 15.19,16.96C14.21,16.96 13.42,16.17 13.42,15.19A1.77,1.77 0 0,1 15.19,13.42Z"/></svg>
                                <span class="-mr-1 font-medium">Coupons</span>
                            </a>
                        </li>
                        <li class="min-w-max rounded hover:bg-slate-700">
                            <a :href="route('admin.whitelists.index')" aria-label="whitelists" class="sidebar-whitelists sidebar-item relative flex items-center space-x-4 px-3 py-3">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M140-160q-24 0-42-18t-18-42v-520q0-24 18-42t42-18h680q24 0 42 18t18 42v520q0 24-18 42t-42 18H140Zm0-496h680v-84H140v84Zm298 344 212-212-44-44-168 168-84-84-44 44 128 128Z"/></svg>
                                <span class="-mr-1 font-medium">Whitelists</span>
                            </a>
                        </li>
                        <li class="min-w-max rounded hover:bg-slate-700">
                            <a :href="route('admin.profile')" aria-label="profile" class="sidebar-profile sidebar-item relative flex items-center space-x-4 px-3 py-3">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" width="24px" viewBox="0 0 24 24"><title>profile</title><path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z"/></svg>
                                <span class="-mr-1 font-medium">Profile</span>
                            </a>
                        </li>
                        <li class="min-w-max rounded hover:bg-slate-700">
                            <a :href="route('admin.site-widget.index')" aria-label="Site Widget" class="sidebar-site-widget sidebar-item relative flex items-center space-x-4 px-3 py-3">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480.08-734q-30.08 0-51.58-21.42-21.5-21.421-21.5-51.5 0-30.08 21.42-51.58 21.421-21.5 51.5-21.5 30.08 0 51.58 21.42 21.5 21.421 21.5 51.5 0 30.08-21.42 51.58-21.421 21.5-51.5 21.5ZM372-80v-533H120v-60h720v60H588v533h-60v-260h-96v260h-60Z"/></svg>
                                <span class="-mr-1 font-medium">Site Widget</span>
                            </a>
                        </li>
                        <li class="min-w-max rounded hover:bg-slate-700">
                            <a :href="route('admin.audits.index')" aria-label="Audit History" class="sidebar-site-widget sidebar-item relative flex items-center space-x-4 px-3 py-3">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" width="24px" viewBox="0 0 24 24"><title>text-box-check-outline</title><path d="M17,21L14.25,18L15.41,16.84L17,18.43L20.59,14.84L21.75,16.25M12.8,21H5C3.89,21 3,20.11 3,19V5C3,3.89 3.89,3 5,3H19C20.11,3 21,3.89 21,5V12.8C20.39,12.45 19.72,12.2 19,12.08V5H5V19H12.08C12.2,19.72 12.45,20.39 12.8,21M12,17H7V15H12M14.68,13H7V11H17V12.08C16.15,12.22 15.37,12.54 14.68,13M17,9H7V7H17" /></svg>
                                <span class="-mr-1 font-medium">Audits</span>
                            </a>
                        </li>
                        <li class="min-w-max rounded hover:bg-slate-700">
                            <a :href="route('admin.installation.index')" aria-label="installation" class="sidebar-installation sidebar-item relative flex items-center space-x-4 px-3 py-3">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M679-466 466-679l213-213 213 213-213 213Zm-559-72v-301h301v301H120Zm418 418v-301h301v301H538Zm-418 0v-301h301v301H120Zm60-478h181v-181H180v181Zm502 51 129-129-129-129-129 129 129 129Zm-84 367h181v-181H598v181Zm-418 0h181v-181H180v181Zm181-418Zm192-78ZM361-361Zm237 0Z"/></svg>
                                <span class="-mr-1 font-medium">Installations</span>
                            </a>
                        </li>
                        <li class="min-w-max rounded hover:bg-slate-700">
                        <a :href="route('admin.documentation.index')" aria-label="documentation" class="sidebar-documentation sidebar-item relative flex items-center space-x-4 px-3 py-3">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" width="24px" viewBox="0 0 24 24"><title>documentation</title><path d="M13,9H18.5L13,3.5V9M6,2H14L20,8V20A2,2 0 0,1 18,22H6C4.89,22 4,21.1 4,20V4C4,2.89 4.89,2 6,2M15,18V16H6V18H15M18,14V12H6V14H18Z"/></svg>
                                <span class="-mr-1 font-medium">Documentation</span>
                            </a>
                        </li>
                        <!-- <li class="min-w-max rounded hover:bg-slate-700">
                            <a :href="route('admin.sample.index')" aria-label="samplepage" class="sidebar-sample-page sidebar-item relative flex items-center space-x-4 px-3 py-3">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" width="24px" viewBox="0 0 24 24"><title>sample-page</title><path d="M21 2H3C1.9 2 1 2.9 1 4V20C1 21.1 1.9 22 3 22H21C22.1 22 23 21.1 23 20V4C23 2.9 22.1 2 21 2M21 20H3V6H21V20Z"/></svg>
                                <span class="-mr-1 font-medium">Sample Page</span>
                            </a>
                        </li> -->

                    </ul>
                </div>
            </div>
        </div>
        <div class="drawer-content flex flex-col bg-gray-100 w-full">
            <!-- Navbar -->
            <div class="w-full navbar flex justify-between">
                <!-- Mobile Sidebar Hamburger -->
                <div class="block md:hidden">
                    <button class="btn btn-square text-black border-none bg-teal-600/10 hover:bg-teal-600/50" @click="toggleMobileSidebar">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" fill="black"/>
                        </svg>
                    </button>
                </div>
                <div class="hidden md:block">
                    <button class="btn btn-square text-black border-none bg-teal-600/10 hover:bg-teal-600/50" @click="toggleSidebar">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" fill="black"/>
                        </svg>
                    </button>
                </div>

                <div class="gap-2">
                    <!-- <div class="form-control">
                        <input type="text" placeholder="Search" class="input input-bordered" />
                    </div> -->
                    <div class="dropdown dropdown-end">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="inline-flex items-center px-3 py-2 text-sm leading-4 font-medium rounded-md hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                    >
                                        <p class="flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" width="24px" viewBox="0 0 24 24"><title>account</title><path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z" fill="#3282F2"/></svg>
                                            {{ $page.props.auth.user.name }}
                                        </p>

                                        <svg
                                            height="20px"
                                            width="20px"
                                            class="mb-1"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20"
                                            fill="#02C9B2"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M7,10L12,15L17,10H7Z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <DropdownLink :href="route('admin.profile')"> Profile </DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button">
                                    Log Out
                                </DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                </div>
            </div>
            <div id="mobile-sidebar" class="mobile-sidebar block md:hidden transition-all" :class="mobileSidebarClasses">
                <ul class="menu p-4 bg-slate-800 text-white">
                    <!-- Mobile Sidebar content here -->

                    <li class="rounded hover:bg-slate-700 text-white">
                        <a :href="route('admin.dashboard')" class="sidebar-dashboard sidebar-item">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" width="24px" viewBox="0 0 24 24"><title>view-dashboard</title><path d="M13,3V9H21V3M13,21H21V11H13M3,21H11V15H3M3,13H11V3H3V13Z"/></svg>
                            Dashboard
                        </a>
                    </li>
                    <li class="rounded hover:bg-slate-700">
                        <a :href="route('admin.clients.index')" class="sidebar-clients sidebar-item">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" width="24px" viewBox="0 0 24 24"><title>account-group</title><path d="M12,5.5A3.5,3.5 0 0,1 15.5,9A3.5,3.5 0 0,1 12,12.5A3.5,3.5 0 0,1 8.5,9A3.5,3.5 0 0,1 12,5.5M5,8C5.56,8 6.08,8.15 6.53,8.42C6.38,9.85 6.8,11.27 7.66,12.38C7.16,13.34 6.16,14 5,14A3,3 0 0,1 2,11A3,3 0 0,1 5,8M19,8A3,3 0 0,1 22,11A3,3 0 0,1 19,14C17.84,14 16.84,13.34 16.34,12.38C17.2,11.27 17.62,9.85 17.47,8.42C17.92,8.15 18.44,8 19,8M5.5,18.25C5.5,16.18 8.41,14.5 12,14.5C15.59,14.5 18.5,16.18 18.5,18.25V20H5.5V18.25M0,20V18.5C0,17.11 1.89,15.94 4.45,15.6C3.86,16.28 3.5,17.22 3.5,18.25V20H0M24,20H20.5V18.25C20.5,17.22 20.14,16.28 19.55,15.6C22.11,15.94 24,17.11 24,18.5V20Z"/></svg>
                            Clients
                        </a>
                    </li>
                    <li class="rounded hover:bg-slate-700">
                        <a :href="route('admin.payments.index')" class="sidebar-payments sidebar-item">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" width="24px" viewBox="0 0 24 24"><title>credit-card</title><path d="M20 4H4A2 2 0 0 0 2 6V18A2 2 0 0 0 4 20H20A2 2 0 0 0 22 18V6A2 2 0 0 0 20 4M20 11H4V8H20Z"/></svg>
                            Payments
                        </a>
                    </li>
                    <li class="rounded hover:bg-slate-700">
                        <a :href="route('admin.invoices.index')" class="sidebar-invoices sidebar-item">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" width="24px" viewBox="0 0 24 24"><title>receipt-text</title><path d="M3,22L4.5,20.5L6,22L7.5,20.5L9,22L10.5,20.5L12,22L13.5,20.5L15,22L16.5,20.5L18,22L19.5,20.5L21,22V2L19.5,3.5L18,2L16.5,3.5L15,2L13.5,3.5L12,2L10.5,3.5L9,2L7.5,3.5L6,2L4.5,3.5L3,2M18,9H6V7H18M18,13H6V11H18M18,17H6V15H18V17Z"/></svg>
                            Invoices
                        </a>
                    </li>
                    <li class="rounded hover:bg-slate-700">
                        <a :href="route('admin.coupons.index')" class="sidebar-coupons sidebar-item">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" width="24px" viewBox="0 0 24 24"><title>ticket-percent</title><path d="M4,4A2,2 0 0,0 2,6V10C3.11,10 4,10.9 4,12A2,2 0 0,1 2,14V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V14A2,2 0 0,1 20,12C20,10.89 20.9,10 22,10V6C22,4.89 21.1,4 20,4H4M15.5,7L17,8.5L8.5,17L7,15.5L15.5,7M8.81,7.04C9.79,7.04 10.58,7.83 10.58,8.81A1.77,1.77 0 0,1 8.81,10.58C7.83,10.58 7.04,9.79 7.04,8.81A1.77,1.77 0 0,1 8.81,7.04M15.19,13.42C16.17,13.42 16.96,14.21 16.96,15.19A1.77,1.77 0 0,1 15.19,16.96C14.21,16.96 13.42,16.17 13.42,15.19A1.77,1.77 0 0,1 15.19,13.42Z"/></svg>
                            Coupons
                        </a>
                    </li>
                    <li class="rounded hover:bg-slate-700">
                        <a :href="route('admin.profile')" class="sidebar-profile sidebar-item">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" width="24px" viewBox="0 0 24 24"><title>account</title><path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z"/></svg>
                            Profile
                        </a>
                    </li>
                    <li class="rounded hover:bg-slate-700">
                        <a :href="route('admin.site-widget.index')" class="sidebar-site-widget sidebar-item">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24"><path d="M480.08-734q-30.08 0-51.58-21.42-21.5-21.421-21.5-51.5 0-30.08 21.42-51.58 21.421-21.5 51.5-21.5 30.08 0 51.58 21.42 21.5 21.421 21.5 51.5 0 30.08-21.42 51.58-21.421 21.5-51.5 21.5ZM372-80v-533H120v-60h720v60H588v533h-60v-260h-96v260h-60Z"/></svg>
                            Site Widget
                        </a>
                    </li>
                    <li class="rounded hover:bg-slate-700">
                        <a :href="route('admin.audits.index')" class="sidebar-site-widget sidebar-item">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" width="24px" viewBox="0 0 24 24"><title>text-box-check-outline</title><path d="M17,21L14.25,18L15.41,16.84L17,18.43L20.59,14.84L21.75,16.25M12.8,21H5C3.89,21 3,20.11 3,19V5C3,3.89 3.89,3 5,3H19C20.11,3 21,3.89 21,5V12.8C20.39,12.45 19.72,12.2 19,12.08V5H5V19H12.08C12.2,19.72 12.45,20.39 12.8,21M12,17H7V15H12M14.68,13H7V11H17V12.08C16.15,12.22 15.37,12.54 14.68,13M17,9H7V7H17" /></svg>
                            Audits
                        </a>
                    </li>
                    <li class="rounded hover:bg-slate-700">
                        <a :href="route('admin.installation.index')" class="sidebar-installation sidebar-item">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" width="24px" viewBox="0 0 24 24"><title>cog</title><path d="M12,15.5A3.5,3.5 0 0,1 8.5,12A3.5,3.5 0 0,1 12,8.5A3.5,3.5 0 0,1 15.5,12A3.5,3.5 0 0,1 12,15.5M19.43,12.97C19.47,12.65 19.5,12.33 19.5,12C19.5,11.67 19.47,11.34 19.43,11L21.54,9.37C21.73,9.22 21.78,8.95 21.66,8.73L19.66,5.27C19.54,5.05 19.27,4.96 19.05,5.05L16.56,6.05C16.04,5.66 15.5,5.32 14.87,5.07L14.5,2.42C14.46,2.18 14.25,2 14,2H10C9.75,2 9.54,2.18 9.5,2.42L9.13,5.07C8.5,5.32 7.96,5.66 7.44,6.05L4.95,5.05C4.73,4.96 4.46,5.05 4.34,5.27L2.34,8.73C2.21,8.95 2.27,9.22 2.46,9.37L4.57,11C4.53,11.34 4.5,11.67 4.5,12C4.5,12.33 4.53,12.65 4.57,12.97L2.46,14.63C2.27,14.78 2.21,15.05 2.34,15.27L4.34,18.73C4.46,18.95 4.73,19.03 4.95,18.95L7.44,17.94C7.96,18.34 8.5,18.68 9.13,18.93L9.5,21.58C9.54,21.82 9.75,22 10,22H14C14.25,22 14.46,21.82 14.5,21.58L14.87,18.93C15.5,18.67 16.04,18.34 16.56,17.94L19.05,18.95C19.27,19.03 19.54,18.95 19.66,18.73L21.66,15.27C21.78,15.05 21.73,14.78 21.54,14.63L19.43,12.97Z"/></svg>
                            Installation
                        </a>
                    </li>
                    <li class="rounded hover:bg-slate-700">
                        <a :href="route('admin.documentation.index')" class="sidebar-documentation sidebar-item">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" width="24px" viewBox="0 0 24 24"><title>file-document</title><path d="M13,9H18.5L13,3.5V9M6,2H14L20,8V20A2,2 0 0,1 18,22H6C4.89,22 4,21.1 4,20V4C4,2.89 4.89,2 6,2M15,18V16H6V18H15M18,14V12H6V14H18Z"/></svg>
                            Documentation
                        </a>
                    </li>
                    <!-- <li class="rounded hover:bg-slate-700">
                        <a :href="route('admin.sample.index')" class="sidebar-sample-page sidebar-item">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" width="24px" viewBox="0 0 24 24"><title>application-outline</title><path d="M21 2H3C1.9 2 1 2.9 1 4V20C1 21.1 1.9 22 3 22H21C22.1 22 23 21.1 23 20V4C23 2.9 22.1 2 21 2M21 20H3V6H21V20Z"/></svg>
                            Sample Page
                        </a>
                    </li> -->
                </ul>
            </div>
            <main>
                <slot />
            </main>

            <!-- Page content here -->
        </div>
    </div>
</template>

<style scoped>
    .drawer-content {
        background: linear-gradient(90deg, #9CD3D0 -341.67%, #FFFFFF 101.77%);
    }

    .navbar {
        background: #DFF8F4;
    }

    #mobile-sidebar {
        background: #1E293B;
        height: fit-content;
        width: 100%;
        position: absolute;
        top: 62px;
        z-index: 99;
    }

    .sidebar-item {
        color: white;
        fill: white;
    }
</style>
