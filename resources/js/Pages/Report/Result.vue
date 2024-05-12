<script setup>
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import moment from "moment";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
    prices: Array,
    to: String,
    from: String,
});

const period = props.to === props.from ? moment(props.from).format("DD MMMM YYYY")
    : `${moment(props.from).format("DD MMMM YYYY")} - ${moment(props.to).format("DD MMMM YYYY")}`;

const print = () => window.print();
</script>


<template>
    <AuthenticatedLayout>

        <Head title="Laporan" />

        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Laporan
                </h2>

                <div class="flex gap-2">
                    <Link :href="route('report.index')">
                    <SecondaryButton>
                        <i class="fas fa-fw fa-arrow-left"></i>
                    </SecondaryButton>
                    </Link>
                    <PrimaryButton @click="print">
                        <i class="fas fa-fw fa-print"></i>
                    </PrimaryButton>
                </div>
            </div>
        </template>

        <div class="p-4 sm:p-8 bg-white border sm:rounded-lg">
            <div id="printable-area">
                <table class="report-container">
                    <thead class="report-header">
                        <tr>
                            <th>
                                <div class="flex justify-between mb-6">
                                    <h2>Laporan Barang Keluar</h2>
                                    <small>{{ period }}</small>
                                </div>
                            </th>
                        </tr>
                    </thead>

                    <tfoot class="report-footer">
                        <tr>
                            <td>
                                <small class="text-gray-500">
                                    <i>Dicetak pada {{ moment().format('DD MMMM YYYY - H:m:s') }} WIB</i>
                                </small>
                            </td>
                        </tr>
                    </tfoot>

                    <tbody class="report-content">
                        <tr>
                            <td class="report-content-cell">
                                <div class="main">

                                    <template v-for="price in prices">
                                        <h5 v-text="price.name" class="mb-3 italic" />
                                        <table class="w-full mb-8 text-left" id="table-content">
                                            <thead>
                                                <tr>
                                                    <th style="width: 15%;" class="border p-1 bg-orange-200">Tanggal
                                                    </th>
                                                    <th style="width: 15%;" class="border p-1 bg-orange-200">Kode Barang
                                                    </th>
                                                    <th style="width: 35%;" class="border p-1 bg-orange-200">Nama barang
                                                    </th>
                                                    <th style="width: 5%;" class="border p-1 bg-orange-200">Qty</th>
                                                    <th style="width: 15%;" class="border p-1 bg-orange-200">Berat
                                                        (gram)
                                                    </th>
                                                    <th style="width: 15%;" class="border p-1 bg-orange-200">Kadar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <template v-if="price.soldItems?.length > 0">
                                                    <tr v-for="jewelry in price.soldItems" :key="jewelry.id">
                                                        <td class="border p-1">{{
                        moment(jewelry.date).format('DD-MM-YYYY')
                    }}
                                                        </td>
                                                        <td class="border p-1">{{ jewelry.jewelry_number }}</td>
                                                        <td class="border p-1">{{ jewelry.jewelry_name }}</td>
                                                        <td class="border p-1 text-center">1</td>
                                                        <td class="border p-1">{{ jewelry.weight }}</td>
                                                        <td class="border p-1">{{ price.rate }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" class="border p-1 text-right">
                                                            <small class="font-medium">Sub Total</small>
                                                        </td>
                                                        <td class="border p-1 text-center">{{ price.soldItems.length }}
                                                        </td>
                                                        <td colspan="2" class="border p-1 font-medium">
                                                            {{
                        price.soldItems.reduce((acc, item) => acc + item.weight, 0)
                                                            }}
                                                        </td>
                                                    </tr>
                                                </template>
                                                <tr v-else>
                                                    <td colspan="6" class="border py-4 text-center">
                                                        <small class="text-gray-400">Tidak Ada Data</small>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </template>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
table.report-container {
    page-break-after: always;
    width: 100%;
}

thead.report-header {
    display: table-header-group;
}

tfoot.report-footer {
    display: table-footer-group;
}

@media print {
    body {
        visibility: hidden;
    }

    #printable-area {
        visibility: visible;
        position: absolute;
        width: 100%;
        height: 100%;
        left: 0;
        top: 0;
    }
}
</style>