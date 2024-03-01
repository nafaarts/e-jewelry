<script setup>
import InvoiceWrapper from '@/Components/Invoice/InvoiceWrapper.vue';
import moment from 'moment';
import { currencyFormatter } from "@/utils/currencyFormatter";
import Table from '@/Components/Table.vue';

const props = defineProps({
    sale: Object,
    config: Object
})

</script>

<template>
    <Head :title="'Pembelian | #' + sale.sale_number" />

    <InvoiceWrapper :size="config.invoice_sale_paper_size" :backRoute="route('sales.show', sale.id)">
        <img class="absolute top-0 left-0 h-fit" :src="`/storage/${config.invoice_sale_header_image}`" alt="header">

        <div>
            <h1 class="text-md font-bold underline text-center my-3">PEMBELIAN</h1>

            <div class="flex py-2">
                <div class="w-1/2">
                    <h3 class="text-xs font-bold mb-2">Detail</h3>
                    <table>
                        <tr>
                            <td class="text-xs text-gray-500">Nomor</td>
                            <td class="text-xs px-2">:</td>
                            <td class="text-xs" v-text="sale.sale_number" />
                        </tr>
                        <tr>
                            <td class="text-xs text-gray-500">Tanggal</td>
                            <td class="text-xs px-2">:</td>
                            <td class="text-xs" v-text="moment(sale.updated_at).format('DD MMMM YYYY')" />
                        </tr>
                    </table>
                </div>
                <div class="w-1/2">
                    <h3 class="text-xs font-bold mb-2">Pelanggan</h3>
                    <table>
                        <tr>
                            <td class="text-xs text-gray-500">Nama</td>
                            <td class="text-xs px-2">:</td>
                            <td class="text-xs">{{ sale.costumer.name }}</td>
                        </tr>
                        <tr>
                            <td class="text-xs text-gray-500">Alamat</td>
                            <td class="text-xs px-2">:</td>
                            <td class="text-xs">{{ sale.costumer.address }}</td>
                        </tr>
                        <tr>
                            <td class="text-xs text-gray-500">No. HP</td>
                            <td class="text-xs px-2">:</td>
                            <td class="text-xs">{{ sale.costumer.phone_number }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="flex py-2">
                <table class="text-[10px] border-collapse w-full">
                    <tr>
                        <th scope="row" rowspan="2" class="border border-gray-500 bg-gray-200">Kadar</th>
                        <th scope="row" rowspan="2" class="border border-gray-500 bg-gray-200">Nama</th>
                        <th scope="row" colspan="2" class="border border-gray-500 bg-gray-200">Berat</th>
                        <th scope="row" rowspan="2" class="border border-gray-500 bg-gray-200">Harga</th>
                    </tr>
                    <tr>
                        <th scope="col" class="border border-gray-500 bg-gray-200">Gram</th>
                        <th scope="col" class="border border-gray-500 bg-gray-200">Milie</th>
                    </tr>

                    <tr v-for="jewelry in sale.sold_items" :key="jewelry.id" class="border-b">
                        <td class="p-1 border border-gray-500">
                            <p class="text-center">
                                {{ `${jewelry.price.carat} (${jewelry.price.rate}%)` }}
                            </p>
                        </td>
                        <td class="p-1 border border-gray-500">
                            <div class="flex items-center">
                                <div class="pl-3">
                                    <div class="font-medium text-gray-900 whitespace-nowrap">
                                        {{ jewelry.name }}
                                    </div>
                                    <div class="font-normal text-gray-500">
                                        {{ jewelry.jewelry_code }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="p-1 border border-gray-500">
                            <p class="text-center">
                                {{ jewelry.weight }}
                            </p>
                        </td>
                        <td class="p-1 border border-gray-500">
                            <p class="text-center">
                                {{ jewelry.weight * 1000 }}
                            </p>
                        </td>
                        <td class="p-1 border border-gray-500">
                            <div class="font-medium text-gray-900 whitespace-nowrap text-center">
                                {{ currencyFormatter.format(jewelry.sell_price) }}
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2" class="p-1 border border-gray-500"></td>
                        <td colspan="2" class="p-1 border border-gray-500 text-center">Total</td>
                        <td class="p-1 border border-gray-500 text-center">
                            <span class="text-md font-bold">{{ currencyFormatter.format(sale.total_amount) }}</span>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="flex py-2">
                <div class="mb-4">
                    <h3 class="text-[10px] font-medium mb-1">Catatan:</h3>
                    <p class="text-gray-500 text-[10px] whitespace-pre-line" v-text="sale.remarks ?? '-'" />
                </div>
            </div>
        </div>

        <div class="flex-1 flex justify-between py-2">
            <div class="flex flex-col justify-end w-2/3 pr-2">
                <div class="mb-4">
                    <h3 class="text-[10px] font-medium mb-1">Perhatian:</h3>
                    <p class="text-gray-500 text-[10px] whitespace-pre-line" v-text="config.invoice_sale_note" />
                </div>

                <div>
                    <p class="text-gray-500 text-[10px] italic"
                        v-text="`${moment().format('MMMM Do YYYY, h:mm:ss')} | ${$page.props.auth.user.name}`" />
                </div>
            </div>
            <div class="flex flex-col items-end justify-end w-1/3">
                <div class="border-b border-gray-500 w-36 mb-6 me-6">
                    <small class="text-xs text-gray-400">TTD</small>
                </div>
            </div>
        </div>
    </InvoiceWrapper>
</template>