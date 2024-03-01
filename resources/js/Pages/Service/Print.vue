<script setup>
import InvoiceWrapper from '@/Components/Invoice/InvoiceWrapper.vue';
import moment from 'moment';
import { currencyFormatter } from "@/utils/currencyFormatter";

const props = defineProps({
    service: Object,
    config: Object
})

</script>

<template>
    <Head :title="'Service | #' + service.service_number" />

    <InvoiceWrapper :size="config.invoice_service_paper_size" :backRoute="route('services.show', service.id)">
        <img class="absolute top-0 left-0 h-fit" :src="`/storage/${config.invoice_service_header_image}`" alt="header">

        <div>
            <h1 class="text-md font-bold underline text-center my-3">PERBAIKAN</h1>

            <div class="flex py-2">
                <div class="w-1/2">
                    <h3 class="text-xs font-bold mb-2">Detail</h3>
                    <table>
                        <tr>
                            <td class="text-xs text-gray-500">Nomor</td>
                            <td class="text-xs px-2">:</td>
                            <td class="text-xs" v-text="service.service_number" />
                        </tr>
                        <tr>
                            <td class="text-xs text-gray-500">Tanggal</td>
                            <td class="text-xs px-2">:</td>
                            <td class="text-xs" v-text="moment(service.updated_at).format('DD MMMM YYYY')" />
                        </tr>
                        <tr>
                            <td class="text-xs text-gray-500">Est. Selesai</td>
                            <td class="text-xs px-2">:</td>
                            <td class="text-xs"
                                v-text="service.estimated_date ? moment(service.estimated_date).format('DD MMMM YYYY') : '-'" />
                        </tr>
                    </table>
                </div>
                <div class="w-1/2">
                    <h3 class="text-xs font-bold mb-2">Pelanggan</h3>
                    <table>
                        <tr>
                            <td class="text-xs text-gray-500">Nama</td>
                            <td class="text-xs px-2">:</td>
                            <td class="text-xs">{{ service.costumer.name }}</td>
                        </tr>
                        <tr>
                            <td class="text-xs text-gray-500">Alamat</td>
                            <td class="text-xs px-2">:</td>
                            <td class="text-xs">{{ service.costumer.address }}</td>
                        </tr>
                        <tr>
                            <td class="text-xs text-gray-500">No. HP</td>
                            <td class="text-xs px-2">:</td>
                            <td class="text-xs">{{ service.costumer.phone_number }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="py-2">
                <h3 class="text-xs font-bold mb-2">Detail Perbaikan</h3>
                <div class="relative">
                    <div class="text text-gray-600">
                        <p>{{ service.remarks }}</p>
                    </div>
                    <ul class="list">
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                        <li></li>
                    </ul>
                </div>
            </div>

            <div class="flex justify-between py-2">
                <div class="w-1/2">
                    <table>
                        <tr>
                            <td class="text-xs text-gray-500">Kategori</td>
                            <td class="text-xs px-2">:</td>
                            <td class="text-xs">{{ service.category.name }}</td>
                        </tr>
                        <tr>
                            <td class="text-xs text-gray-500">Berat Perhiasan</td>
                            <td class="text-xs px-2">:</td>
                            <td class="text-xs">{{ service.weight }} Gr</td>
                        </tr>
                    </table>
                </div>
                <div class="w-1/2">
                    <table>
                        <tr>
                            <td class="text-xs text-gray-500">Jumlah Biaya</td>
                            <td class="text-xs px-2">:</td>
                            <td class="text-sm font-bold">{{ currencyFormatter.format(service.cost) }}</td>
                        </tr>
                        <tr v-show="service.paid_amount !== service.cost">
                            <td class="text-xs text-gray-500">Panjar</td>
                            <td class="text-xs px-2">:</td>
                            <td class="text-sm">{{ currencyFormatter.format(service.paid_amount) }}</td>
                        </tr>
                        <tr v-show="service.paid_amount !== service.cost">
                            <td class="text-xs text-gray-500">Sisa Bayar</td>
                            <td class="text-xs px-2">:</td>
                            <td class="text-sm">{{ currencyFormatter.format(service.cost - service.paid_amount) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="flex-1 flex justify-between py-2">
            <div class="flex flex-col justify-end w-2/3">
                <div class="mb-4">
                    <h3 class="text-[10px] font-medium mb-1">Perhatian:</h3>
                    <p class="text-gray-500 text-[10px] whitespace-pre-line" v-text="config.invoice_service_note" />
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

<style scoped>
.list {
    color: #555;
    padding: 0 !important;
    width: 100%;
}

.list li {
    list-style: none;
    border-bottom: 1px dotted rgb(107 114 128);
    text-indent: 25px;
    height: 20px;
    text-transform: capitalize;
}

.text {
    position: absolute;
    top: 0;
}

.text p {
    white-space: pre-line !important;
    line-height: 20px;
    font-size: 12px;
}
</style>
