<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Swal from "sweetalert2";
import SwalConfig from "@/utils/sweetalert.conf";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import { currencyFormatter } from "@/utils/currencyFormatter";
import Table from "@/Components/Table.vue";
import ImageCover from "@/Components/ImageCover.vue";
import { router } from "@inertiajs/vue3";
import moment from "moment";
import { computed } from "vue";

const props = defineProps({
    sale: Object,
});

const discountPrice = computed(() => {
    if (props.sale.is_percent_discount) {
        return props.sale.total_amount * (props.sale.discount / 100);
    }

    return props.sale.discount;
});

const confirmDelete = (name) => {
    Swal.fire({
        title: "Konfirmasi",
        html: `<p>Hapus penjualan dengan kode <strong>${name}</strong> ?</p><small>status barang akan <strong>tersedia kembali</strong>.</small>`,
        showCancelButton: true,
        cancelButtonText: "Tidak",
        confirmButtonText: "Ya",
        ...SwalConfig,
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("sales.destroy", props.sale.id), {
                onSuccess: () => {
                    Swal.fire({
                        title: "Berhasil",
                        icon: "success",
                        text: "Data penjualan berhasil dihapus!",
                        ...SwalConfig,
                    });
                },
            });
        }
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Detail Penjualan" />

        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Detail Penjualan
                </h2>
            </div>
        </template>

        <div
            class="bg-white overflow-hidden sm:rounded-lg border p-4 sm:p-8 mb-4"
        >
            <div class="flex flex-col md:flex-row gap-4 mb-8">
                <div class="w-full md:w-1/2 space-y-6">
                    <table class="text-left">
                        <tr>
                            <th>Kode Penjualan</th>
                            <td class="px-3">:</td>
                            <td>
                                <span class="font-bold text-orange-500">
                                    {{ sale.sale_number }}
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <th>Harga</th>
                            <td class="px-3">:</td>
                            <td>
                                <div class="flex gap-2">
                                    <span>{{
                                        currencyFormatter.format(
                                            sale.total_amount
                                        )
                                    }}</span>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <th>
                                Discount
                                <span v-show="sale.is_percent_discount"
                                    >({{ sale.discount }}%)</span
                                >
                            </th>
                            <td class="px-3">:</td>
                            <td>
                                <div class="flex gap-2">
                                    <span>{{
                                        currencyFormatter.format(discountPrice)
                                    }}</span>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <th>Total Harga</th>
                            <td class="px-3">:</td>
                            <td>
                                <div class="flex gap-2">
                                    <strong>{{
                                        currencyFormatter.format(
                                            sale.total_amount - discountPrice
                                        )
                                    }}</strong>
                                </div>
                            </td>
                        </tr>
                    </table>

                    <table class="text-left">
                        <tr>
                            <th scope="row">Catatan</th>
                            <td class="px-3">:</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <span class="text-gray-500 whitespace-pre-line">
                                    {{ sale.remarks ?? "-" }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="w-full md:w-1/2 space-y-6">
                    <table class="text-left">
                        <tr>
                            <th>Nama Kostumer</th>
                            <td class="px-3">:</td>
                            <td>{{ sale.costumer?.name }}</td>
                        </tr>
                        <tr>
                            <th>Nomor Indentitas</th>
                            <td class="px-3">:</td>
                            <td>
                                {{ sale.costumer?.indentity_number ?? "-" }}
                            </td>
                        </tr>
                        <tr>
                            <th>Nomor Telepon</th>
                            <td class="px-3">:</td>
                            <td>{{ sale.costumer?.phone_number }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td class="px-3">:</td>
                            <td>{{ sale.costumer?.address }}</td>
                        </tr>
                    </table>

                    <table class="text-left">
                        <tr>
                            <th>Dibuat pada:</th>
                            <td class="px-3">:</td>
                            <td>
                                {{
                                    moment(sale.created_at).format(
                                        "DD MMMM YYYY HH:mm"
                                    )
                                }}
                                <span class="text-gray-600">
                                    ({{ sale.created_by?.name }})
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Diupdate pada:</th>
                            <td class="px-3">:</td>
                            <td>
                                {{
                                    moment(sale.updated_at).format(
                                        "DD MMMM YYYY HH:mm"
                                    )
                                }}
                                <span class="text-gray-600">
                                    ({{ sale.updated_by?.name }})
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <Table>
                <template #head>
                    <tr>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">
                            Nama
                        </th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">
                            Deskripsi
                        </th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">
                            Berat
                        </th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">
                            Kadar
                        </th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">
                            Harga
                        </th>
                    </tr>
                </template>

                <tr
                    v-for="jewelry in sale.sold_items"
                    :key="jewelry.id"
                    class="border-b"
                >
                    <td class="px-4 py-2">
                        <div class="flex items-center">
                            <ImageCover
                                class="w-10 h-10 rounded-full bg-zinc-300"
                                :src="
                                    jewelry.photo
                                        ? '/storage/' + jewelry.photo
                                        : '/images/image-placeholder.png'
                                "
                            />
                            <div class="pl-3">
                                <div
                                    class="font-medium text-gray-900 whitespace-nowrap"
                                >
                                    {{ jewelry.name }}
                                </div>
                                <div class="font-normal text-gray-500">
                                    {{ jewelry.jewelry_code }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-2">
                        <p class="w-fit max-w-56 truncate">
                            {{ jewelry.remarks }}
                        </p>
                    </td>
                    <td class="px-4 py-2">
                        <p class="w-fit max-w-56 truncate">
                            {{ jewelry.weight }} Gram
                        </p>
                    </td>
                    <td class="px-4 py-2">
                        <p class="w-fit max-w-56 truncate">
                            {{
                                `${jewelry.price.carat} (${jewelry.price.rate}%)`
                            }}
                        </p>
                    </td>
                    <td class="px-4 py-2">
                        <div
                            class="font-medium text-gray-900 whitespace-nowrap"
                        >
                            {{ currencyFormatter.format(jewelry.sell_price) }}
                        </div>
                    </td>
                </tr>
            </Table>
        </div>

        <div
            class="bg-white overflow-hidden sm:rounded-lg border p-4 sm:p-8 space-y-4"
        >
            <div class="flex flex-col md:flex-row justify-between gap-3">
                <Link :href="route('sales.index')">
                    <SecondaryButton class="w-full h-full">
                        <i class="fas fa-fw fa-arrow-left me-2 md:m-0"></i>
                        <span class="md:hidden">Kembali</span>
                    </SecondaryButton>
                </Link>
                <div class="flex flex-col md:flex-row gap-3">
                    <a :href="route('sales.print', sale.id)" target="_blank">
                        <SecondaryButton>
                            <i class="fas fa-fw fa-download me-2"></i> Cetak
                            Faktur
                        </SecondaryButton>
                    </a>
                    <DangerButton
                        @click="confirmDelete(sale?.sale_number)"
                        v-if="$page.props.auth.user.role === 'ADMIN'"
                    >
                        <i class="fas fa-fw fa-trash me-2 md:m-0"></i>
                        <span class="md:hidden">Hapus</span>
                    </DangerButton>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
