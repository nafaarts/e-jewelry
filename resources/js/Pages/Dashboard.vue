<script setup>
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Card from "@/Components/Card.vue";
import PriceChart from "@/Chart/PriceChart.vue";
import { currencyFormatter } from "@/utils/currencyFormatter";
import moment from "moment";

defineProps({
    jewelries_count: Number,
    suppliers_count: Number,
    employees_count: Number,
    costumers_count: Number,
    latest_price: Object,
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

        <div class="flex flex-col md:flex-row w-full gap-3">
            <div class="flex gap-3 w-full md:w-1/2">
                <Card class="w-full md:w-1/2 mb-3" label="Jumlah Barang">
                    <h4 class="text-2xl">{{ jewelries_count }}</h4>
                </Card>
                <Card class="w-full md:w-1/2 mb-3" label="Jumlah Supplier">
                    <h4 class="text-2xl">{{ suppliers_count }}</h4>
                </Card>
            </div>
            <div class="flex gap-3 w-full md:w-1/2">
                <Card class="w-full md:w-1/2 mb-3" label="Jumlah Karyawan">
                    <h4 class="text-2xl">{{ employees_count }}</h4>
                </Card>
                <Card class="w-full md:w-1/2 mb-3" label="Jumlah Kostumer">
                    <h4 class="text-2xl">{{ costumers_count }}</h4>
                </Card>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-3">
            <div class="w-full md:w-1/2">
                <div class="flex flex-col">
                    <!-- <Card class="mb-3">
                        <PriceChart />
                    </Card>
                    <Card label="Harga Emas" line="true">
                        <template #action>
                            <Link
                                :href="route('prices.create')"
                                class="text-xs text-zinc-400 hover:text-orange-500 animate-pulse hover:underline transition-all"
                            >
                                <i class="fas fa-fw fa-refresh"></i>
                                Update Harga
                            </Link>
                        </template>
                        <div class="flex justify-between items-center mb-1">
                            <h5>
                                <i
                                    class="fa-solid fa-circle-chevron-up text-green-400"
                                ></i>
                                Jual :
                            </h5>
                            <p>
                                <strong>{{
                                    currencyFormatter.format(latest_price.sell)
                                }}</strong>
                            </p>
                        </div>
                        <div class="flex justify-between items-center mb-1">
                            <h5>
                                <i
                                    class="fa-solid fa-circle-dot text-gray-400"
                                ></i>
                                Netral :
                            </h5>
                            <p>
                                <strong>{{
                                    currencyFormatter.format(
                                        latest_price.neutral
                                    )
                                }}</strong>
                            </p>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <h5>
                                <i
                                    class="fa-solid fa-circle-chevron-down text-blue-400"
                                ></i>
                                Beli :
                            </h5>
                            <p>
                                <strong>{{
                                    currencyFormatter.format(latest_price.buy)
                                }}</strong>
                            </p>
                        </div>
                        <i class="text-xs text-zinc-400">
                            Terakhir diperbaharui pada:
                            <strong
                                >{{
                                    moment(latest_price.updated_at).format(
                                        "DD MMMM YYYY HH:mm"
                                    )
                                }}
                                WIB</strong
                            >
                        </i>
                    </Card> -->
                </div>
            </div>

            <!-- <div class="w-full md:w-1/2">
                <div class="flex gap-3">
                    <Card class="w-full md:w-1/2 mb-3" label="Penjualan">
                        <h4 class="text-2xl">59</h4>
                    </Card>
                    <Card class="w-full md:w-1/2 mb-3" label="Service">
                        <h4 class="text-2xl">59</h4>
                    </Card>
                </div>
                <div class="flex gap-3">
                    <Card class="w-full md:w-1/2 mb-3" label="Titip">
                        <h4 class="text-2xl">59</h4>
                    </Card>
                    <Card class="w-full md:w-1/2 mb-3" label="Tempahan">
                        <h4 class="text-2xl">59</h4>
                    </Card>
                </div>
            </div> -->
        </div>
    </AuthenticatedLayout>
</template>
