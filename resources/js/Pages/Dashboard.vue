<script setup>
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Card from "@/Components/Card.vue";
// import PriceChart from "@/Chart/PriceChart.vue";
// import { currencyFormatter } from "@/utils/currencyFormatter";
// import moment from "moment";

defineProps({
    jewelriesCount: Number,
    soldJewelriesCount: Number,
    suppliersCount: Number,
    employeesCount: Number,
    costumersCount: Number,
});
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Dashboard" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="hidden md:flex flex-col md:flex-row w-full gap-3">
            <div class="flex gap-3 w-full md:w-1/2">
                <Card class="w-full md:w-1/2 mb-3" label="Jumlah Barang">
                    <div class="flex gap-1 items-end">
                        <h4 class="text-2xl">{{ soldJewelriesCount }}</h4>
                        <span class="text-gray-400"
                            >/ {{ jewelriesCount }}</span
                        >
                    </div>
                </Card>
                <Card class="w-full md:w-1/2 mb-3" label="Jumlah Supplier">
                    <h4 class="text-2xl">{{ suppliersCount }}</h4>
                </Card>
            </div>
            <div class="flex gap-3 w-full md:w-1/2">
                <Card class="w-full md:w-1/2 mb-3" label="Jumlah Karyawan">
                    <h4 class="text-2xl">{{ employeesCount }}</h4>
                </Card>
                <Card class="w-full md:w-1/2 mb-3" label="Jumlah Kostumer">
                    <h4 class="text-2xl">{{ costumersCount }}</h4>
                </Card>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3 md:hidden">
            <Link :href="route('sales.create')">
                <div class="text-center bg-orange-300 p-4 rounded">
                    <i class="text-4xl fas fa-fw fa-money-bill"></i>
                    <h3 class="mt-2">Penjualan</h3>
                </div>
            </Link>
            <Link :href="route('orders.create')">
                <div class="text-center bg-orange-300 p-4 rounded">
                    <i class="text-4xl fas fa-fw fa-hammer"></i>
                    <h3 class="mt-2">Tempahan</h3>
                </div>
            </Link>
            <Link :href="route('services.create')">
                <div class="text-center bg-orange-300 p-4 rounded">
                    <i class="text-4xl fas fa-fw fa-refresh"></i>
                    <h3 class="mt-2">Perbaikan</h3>
                </div>
            </Link>
            <Link :href="route('deposits.index')">
                <div class="text-center bg-orange-300 p-4 rounded">
                    <i class="text-4xl fas fa-fw fa-money-bill-transfer"></i>
                    <h3 class="mt-2">Titipan</h3>
                </div>
            </Link>
        </div>
    </AuthenticatedLayout>
</template>
