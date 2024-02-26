<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

import SidebarLink from "@/Components/SidebarLink.vue";
import ImageCover from "@/Components/ImageCover.vue";
import { Link, usePage } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import SwalConfig from "@/utils/sweetalert.conf";

const dropdown = ref(null);

const handleClickOutside = (event) => {
    if (dropdown.value && !dropdown.value.contains(event.target)) {
        showingNavigationDropdown.value = false;
    }
};

onMounted(() => {
    document.addEventListener("click", handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener("click", handleClickOutside);
});

const page = usePage();
const user = page.props.auth.user;

const showingNavigationDropdown = ref(false);
const toggleNavigationDropdown = () => {
    showingNavigationDropdown.value = !showingNavigationDropdown.value;
};

const showingSidebar = ref(false);
const toggleSidebar = () => {
    showingSidebar.value = !showingSidebar.value;
};

if (page.props.flash.message) {
    Swal.fire({
        title: "Info",
        icon: "info",
        text: page.props.flash.message,
        ...SwalConfig,
    });
}
</script>

<template>
    <div class="flex flex-col h-screen">
        <nav class="z-50 w-full bg-white border-b border-gray-200">
            <div class="px-3 py-3 lg:px-5 lg:pl-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center justify-start">
                        <button @click="toggleSidebar" data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar"
                            aria-controls="logo-sidebar" type="button"
                            class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                            <span class="sr-only">Open sidebar</span>
                            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path clip-rule="evenodd" fill-rule="evenodd"
                                    d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                                </path>
                            </svg>
                        </button>
                        <Link :href="route('dashboard')" class="flex ml-2 md:mr-24">
                        <ApplicationLogo class="h-8 mr-3" />
                        <span class="self-center text-md font-semibold whitespace-nowrap text-zinc-700 dark:text-white">
                            Toko Mas Bina Nusa
                        </span>
                        </Link>
                    </div>
                    <div class="flex items-center">
                        <div class="flex items-center ml-3" ref="dropdown">
                            <div>
                                <button @click="toggleNavigationDropdown" type="button"
                                    class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600 w-8 h-8 overflow-hidden"
                                    aria-expanded="false" data-dropdown-toggle="dropdown-user">
                                    <span class="sr-only">Open user menu</span>
                                    <ImageCover class="w-full h-full" :src="user.photo
                                        ? '/storage/' + user.photo
                                        : '/images/default-user.png'
                                        " />
                                </button>
                            </div>
                            <transition name="fade">
                                <div v-if="showingNavigationDropdown"
                                    class="z-50 absolute top-0 end-0 mt-16 mr-2 text-base list-none bg-white divide-y divide-gray-100 rounded border"
                                    style="min-width: 200px" id="dropdown-user">
                                    <div class="px-4 py-3" role="none">
                                        <p class="text-sm text-gray-900 dark:text-white" role="none">
                                            {{ user.name }}
                                        </p>
                                        <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300"
                                            role="none">
                                            {{ user.email }}
                                        </p>
                                    </div>
                                    <ul class="py-1" role="none">
                                        <Link as="li" :href="route('profile.edit')"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer"
                                            role="menuitem" @click="toggleNavigationDropdown">
                                        Profil
                                        </Link>
                                        <Link as="li" :href="route('logout')" method="post"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer"
                                            role="menuitem" @click="toggleNavigationDropdown">
                                        Keluar
                                        </Link>
                                    </ul>
                                </div>
                            </transition>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <div class="flex flex-row flex-1 min-h-0 overflow-hidden">
            <aside id="logo-sidebar" :class="{
                hidden: !showingSidebar,
                'translate-x-0': showingSidebar,
            }"
                class="md:block p-6 z-40 w-64 max-h-100 overflow-y-auto no-scrollbar transition-transform -translate-x-full bg-orange-50 border-r border-gray-200 md:translate-x-0"
                aria-label="Sidebar">
                <div>
                    <h3 className="mb-4 ml-4 text-sm font-semibold text-gray-500">
                        Main
                    </h3>

                    <ul className="mb-6 flex flex-col gap-1.5">
                        <SidebarLink :link="route('dashboard')" label="Dashboard" icon="gauge"
                            :active="route().current('dashboard')" />
                    </ul>
                </div>
                <div v-if="user.role === 'ADMIN'">
                    <h3 className="mb-4 ml-4 text-sm font-semibold text-gray-500">
                        Master Data
                    </h3>

                    <ul className="mb-6 flex flex-col gap-1.5">
                        <SidebarLink :link="route('jewelries.index')" label="Barang" icon="gem"
                            :active="route().current('jewelries*')" />
                        <SidebarLink :link="route('categories.index')" label="Kategori" icon="list"
                            :active="route().current('categories*')" />
                        <SidebarLink :link="route('employees.index')" label="Karyawan" icon="user-tie"
                            :active="route().current('employees*')" />
                        <SidebarLink :link="route('suppliers.index')" label="Supplier" icon="boxes"
                            :active="route().current('suppliers*')" />
                        <SidebarLink :link="route('costumers.index')" label="Kostumer" icon="users"
                            :active="route().current('costumers*')" />
                        <SidebarLink :link="route('safeboxes.index')" label="Brankas" icon="vault"
                            :active="route().current('safeboxes*')" />
                        <SidebarLink :link="route('prices.index')" label="Harga dan Kadar" icon="percentage"
                            :active="route().current('prices*')" />
                        <!--  -->
                    </ul>
                </div>
                <div>
                    <h3 className="mb-4 ml-4 text-sm font-semibold text-gray-500">
                        Kasir
                    </h3>

                    <ul className="mb-6 flex flex-col gap-1.5">
                        <SidebarLink :link="route('sales.index')" label="Penjualan" icon="money-bill"
                            :active="route().current('sales*')" />
                        <SidebarLink :link="route('orders.index')" label="Tempahan" icon="hammer"
                            :active="route().current('orders*')" />
                        <SidebarLink :link="route('services.index')" label="Service" icon="refresh"
                            :active="route().current('services*')" />
                    </ul>
                </div>
                <div>
                    <h3 className="mb-4 ml-4 text-sm font-semibold text-gray-500">
                        Tools
                    </h3>

                    <ul className="mb-6 flex flex-col gap-1.5">
                        <SidebarLink :link="route('report.index')" label="Laporan" icon="file"
                            :active="route().current('report*')" />
                        <SidebarLink :link="route('setting')" label="Pengaturan" icon="gear"
                            :active="route().current('setting*')" />
                    </ul>
                </div>
            </aside>

            <div :class="{
                'absolute h-screen top-0 pt-20': showingSidebar,
            }" class="p-4 md:p-6 bg-zinc-100 flex-1 overflow-y-auto no-scrollbar  text-xs md:text-base">
                <transition name="fade">
                    <div v-if="showingSidebar" @click="toggleSidebar" class="fixed inset-0 bg-black bg-opacity-25"></div>
                </transition>

                <header v-if="$slots.header">
                    <slot name="header" />
                    <hr class="my-4" />
                </header>

                <slot />
            </div>
        </div>
    </div>
</template>

<style>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
