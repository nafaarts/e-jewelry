<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Swal from "sweetalert2";
import SwalConfig from "@/utils/sweetalert.conf";
import Select from "@/Components/Select.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import { currencyFormatter } from "@/utils/currencyFormatter";
import { ref } from 'vue'
import { router, useForm } from "@inertiajs/vue3";
import moment from "moment";
import status from "@/Constant/OrderStatus";

const props = defineProps({
    order: Object,
})

const updateStatus = ref(false)

const form = useForm({
    type: 'status',
    status: props.order.status,
});

const paidForm = useForm({
    type: 'paid-full',
});

const takenForm = useForm({
    type: 'taken',
});

const confirmPaidFull = () => {
    Swal.fire({
        title: "Konfirmasi",
        html: `<span>Apakah anda yakin untuk konfirmasi lunas?</span>`,
        showCancelButton: true,
        cancelButtonText: "Tidak",
        confirmButtonText: "Ya",
        ...SwalConfig,
    }).then((result) => {
        if (result.isConfirmed) {
            paidForm.patch(route("orders.update", props.order.id), {
                onSuccess: () => {
                    Swal.fire({
                        title: "Berhasil",
                        icon: "success",
                        text: "Pembayaran berhasil dilunaskan!",
                        ...SwalConfig,
                    });
                },
            });
        }
    });
}

const confirmDateTaken = () => {
    Swal.fire({
        title: "Konfirmasi",
        html: `<span>Apakah anda yakin untuk konfirmasi pengambilan?</span>`,
        showCancelButton: true,
        cancelButtonText: "Tidak",
        confirmButtonText: "Ya",
        ...SwalConfig,
    }).then((result) => {
        if (result.isConfirmed) {
            takenForm.patch(route("orders.update", props.order.id), {
                onSuccess: () => {
                    Swal.fire({
                        title: "Berhasil",
                        icon: "success",
                        text: "Pengambilan berhasil dikonfirmasi!",
                        ...SwalConfig,
                    });
                },
            });
        }
    });

}

const confirmStatusUpdate = () => {
    Swal.fire({
        title: "Konfirmasi",
        html: `<span>Apakah anda yakin mengganti status menjadi <strong>${form.status}</strong> ?</span>`,
        showCancelButton: true,
        cancelButtonText: "Tidak",
        confirmButtonText: "Ya",
        ...SwalConfig,
    }).then((result) => {
        if (result.isConfirmed) {
            if (['SELESAI'].includes(form.status) && (props.order.jewelry === null)) {
                Swal.fire({
                    title: "Gagal",
                    icon: "error",
                    html: `<span>Silahkan tambahkan barang sebelum mengubah status menjadi <strong>${form.status}</strong> </span>`,
                    ...SwalConfig,
                });
                updateStatus.value = false;
                return;
            }

            form.patch(route("orders.update", props.order.id), {
                onSuccess: () => {
                    Swal.fire({
                        title: "Berhasil",
                        icon: "success",
                        text: "Status tempahan berhasil diubah!",
                        ...SwalConfig,
                    });

                    updateStatus.value = false;
                },
            });
        }
    });
};

const confirmDelete = (name) => {
    Swal.fire({
        title: "Konfirmasi",
        html: `<span>Hapus data service <strong>${name}</strong> ?</span>`,
        showCancelButton: true,
        cancelButtonText: "Tidak",
        confirmButtonText: "Ya",
        ...SwalConfig,
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("orders.destroy", props.order.id), {
                onSuccess: () => {
                    Swal.fire({
                        title: "Berhasil",
                        icon: "success",
                        text: "Tempahan berhasil dihapus!",
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

        <Head title="Detail Tempahan" />

        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Detail Tempahan
                </h2>
            </div>
        </template>

        <div class="bg-white overflow-hidden sm:rounded-lg border p-4 sm:p-8 space-y-4 mb-4">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="w-full md:w-1/2 space-y-6">
                    <table class="text-left">
                        <tr>
                            <th>Kode Tempahan</th>
                            <td class="px-3">:</td>
                            <td>
                                <span class="font-bold">
                                    {{ order.order_number }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td class="px-3">:</td>
                            <td>{{ order.category?.name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Berat Perhiasan</th>
                            <td class="px-3">:</td>
                            <td>{{ order.weight }} Gr</td>
                        </tr>
                        <tr>
                            <th>Biaya/Ongkos</th>
                            <td class="px-3">:</td>
                            <td>{{ currencyFormatter.format((order.weight / order.saved_price.weight) *
                                (order.saved_price.sell_price + order.saved_price.cost)) }}
                                <span v-if="order.cost" class="text-gray-500">
                                    {{ `+ ${currencyFormatter.format(order.cost)}` }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Total dibayar</th>
                            <td class="px-3">:</td>
                            <td>
                                <div class="flex gap-2">
                                    <span>{{ currencyFormatter.format(order.paid_amount) }}</span>
                                    <div class="flex items-center bg-green-500 rounded text-white px-2"
                                        v-if="order.paid_amount === order.total_price">
                                        <small>Lunas</small>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="order.paid_amount !== order.total_price">
                            <th>Total sisa</th>
                            <td class="px-3">:</td>
                            <td class="font-medium text-red-600">{{ currencyFormatter.format(order.total_price -
                                order.paid_amount) }}</td>
                        </tr>
                        <tr>
                            <th>Estimasi Selesai</th>
                            <td class="px-3">:</td>
                            <td>{{ order.estimated_date ? moment(order.estimated_date).format("DD MMMM YYYY") : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Diambil</th>
                            <td class="px-3">:</td>
                            <td>{{ order.date_taken ? moment(order.date_taken).format("DD MMMM YYYY") : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td class="px-3">:</td>
                            <td>
                                <div class="flex gap-2">
                                    <span>{{ order.status }}</span>
                                    <button @click="() => updateStatus = !updateStatus"
                                        class="text-orange-400 hover:text-orange-600 cursor-pointer">
                                        <i :class="`fas fa-fw fa-${updateStatus ? 'arrow-down' : 'edit'}`"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </table>

                    <div v-if="updateStatus" class="space-y-3">
                        <InputLabel for="status" value="Status" />
                        <Select v-model="form.status">
                            <option value="">
                                - Pilih status -
                            </option>
                            <option v-for="(item, index) in status" :key="index" :value="item">
                                {{ item }}
                            </option>
                        </Select>
                        <div class="flex gap-2">
                            <PrimaryButton type="button" @click="confirmStatusUpdate">
                                <i class="fas fa-fw fa-check"></i>
                            </PrimaryButton>
                            <SecondaryButton type="button" @click="() => updateStatus = false">
                                <i class="fas fw-fw fa-times"></i>
                            </SecondaryButton>
                        </div>
                    </div>

                    <table class="text-left">
                        <tr>
                            <th scope="row">Catatan</th>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <span class="text-gray-500 whitespace-pre-line">
                                    {{ order.remarks }}
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
                            <td>{{ order.costumer?.name }}</td>
                        </tr>
                        <tr>
                            <th>Nomor Indentitas</th>
                            <td class="px-3">:</td>
                            <td>{{ order.costumer?.indentity_number ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Nomor Telepon</th>
                            <td class="px-3">:</td>
                            <td>{{ order.costumer?.phone_number }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td class="px-3">:</td>
                            <td>{{ order.costumer?.address }}</td>
                        </tr>
                    </table>

                    <table class="text-left">
                        <tr>
                            <th>Dibuat pada: </th>
                            <td class="px-3">:</td>
                            <td>
                                {{ moment(order.created_at).format(
                                    "DD MMMM YYYY HH:mm"
                                ) }}
                                <span class="text-gray-600">
                                    ({{ order.created_by?.name }})
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Diupdate pada: </th>
                            <td class="px-3">:</td>
                            <td>
                                {{ moment(order.updated_at).format(
                                    "DD MMMM YYYY HH:mm"
                                ) }}
                                <span class="text-gray-600">
                                    ({{ order.updated_by?.name }})
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-4 mb-4">
            <div class="bg-white overflow-hidden sm:rounded-lg border p-4 sm:p-8 w-full md:w-1/2 space-y-4">
                <h2 class="font-bold">Riwayat Harga dan Kadar</h2>
                <table class="text-left">
                    <tr>
                        <th>Kadar</th>
                        <td class="px-3">:</td>
                        <td>{{ order.saved_price.name }} - {{ order.saved_price.carat }} ({{ order.saved_price.rate }}%)
                        </td>
                    </tr>
                    <tr>
                        <th>Berat</th>
                        <td class="px-3">:</td>
                        <td>{{ order.saved_price.weight }} Gr <span class="text-gray-500">/ {{ order.saved_price.category
                        }}</span></td>
                    </tr>
                    <tr>
                        <th>Harga Jual</th>
                        <td class="px-3">:</td>
                        <td>{{ currencyFormatter.format(order.saved_price.sell_price + order.saved_price?.cost) }}</td>
                    </tr>
                    <tr>
                        <th>Diupdate pada</th>
                        <td class="px-3">:</td>
                        <td>{{ moment(order.saved_price.updated_at).format(
                            "DD MMMM YYYY HH:mm"
                        ) }}</td>
                    </tr>
                </table>
            </div>
            <div class="flex flex-col bg-white overflow-hidden sm:rounded-lg border p-4 sm:p-8 w-full md:w-1/2 space-y-4">
                <div class="flex justify-between items-center">
                    <h2 class="font-bold">Detail Barang</h2>
                    <Link as="small" v-show="order.jewelry !== null" :href="route('sales.create', {
                        order_id: order.id
                    })" class="text-gray-500 hover:text-orange-400 cursor-pointer">
                    Checkout <i class="fas fa-fw fa-arrow-right"></i>
                    </Link>
                </div>
                <div v-if="order.jewelry !== null"
                    class="flex-1 flex gap-2 border p-2 border-gray-200 bg-gray-50 rounded-md mb-2">
                    <div class="hidden md:flex items-center justify-center overflow-hidden">
                        <img :src="order.jewelry.photo ? `/storage/${order.jewelry.photo}` : '/images/image-placeholder.png'"
                            class="h-20 rounded aspect-square" alt="jewelry">
                    </div>
                    <div class="flex items-center">
                        <table class="text-left">
                            <tr>
                                <th>Kode Barang</th>
                                <td class="px-3">:</td>
                                <td>{{ order.jewelry.jewelry_code }}</td>
                            </tr>
                            <tr>
                                <th>Nama</th>
                                <td class="px-3">:</td>
                                <td>{{ order.jewelry.name }}</td>
                            </tr>
                            <tr>
                                <th>Berat dan Kadar</th>
                                <td class="px-3">:</td>
                                <td>{{ order.jewelry.weight }} Gr / {{ order.saved_price.name }} - {{
                                    order.jewelry.price.carat }} ({{
        order.jewelry.price.rate }}%)</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div v-else class="flex-1 flex flex-col justify-between items-start">
                    <div class="flex flex-col">
                        <small class="text-gray-500">Belum ada barang ditambahkan.</small>
                        <small class="text-gray-500">tekan <strong>tombol dibawah</strong> untuk menambahkan barang.</small>
                    </div>
                    <Link as="button" :href="route('jewelries.create', {
                        order_id: order.id
                    })" v-if="$page.props.auth.user.role === 'ADMIN'"
                        class="bg-gray-200 hover:bg-gray-300 transition px-2 py-1 uppercase text-xs rounded">
                    <i class="fas fa-fw fa-plus"></i>
                    Tambah Barang dan Konfirmasi Selesai
                    </Link>
                    <small v-else class="text-red-500">
                        <i class="fas fa-fw fa-warning"></i>
                        tambah barang hanya bisa dilakukan oleh admin.
                    </small>
                </div>
            </div>
        </div>

        <div class=" bg-white overflow-hidden sm:rounded-lg border p-4 sm:p-8 space-y-4">
            <div class="flex flex-col md:flex-row justify-between gap-3">
                <Link :href="route('orders.index')">
                <SecondaryButton class="w-full h-full">
                    <i class="fas fa-fw fa-arrow-left me-2 md:m-0"></i> <span class="md:hidden">Kembali</span>
                </SecondaryButton>
                </Link>
                <div class="flex flex-col md:flex-row gap-3">
                    <SecondaryButton v-if="!order.date_taken && order.paid_amount === order.total_price"
                        @click="confirmDateTaken">
                        <i class="fas fa-fw fa-hand me-2"></i> Konfirmasi Pengambilan
                    </SecondaryButton>
                    <SecondaryButton v-if="order.paid_amount !== order.total_price" @click="confirmPaidFull">
                        <i class="fas fa-fw fa-check me-2"></i> Tetapkan Lunas
                    </SecondaryButton>
                    <Link :href="route('orders.print', order.id)">
                    <SecondaryButton class="w-full md:w-fit">
                        <i class="fas fa-fw fa-download me-2"></i> Cetak Faktur
                    </SecondaryButton>
                    </Link>
                    <DangerButton @click="confirmDelete(order?.order_number)" v-if="$page.props.auth.user.role === 'ADMIN'">
                        <i class="fas fa-fw fa-trash me-2 md:m-0"></i> <span class="md:hidden">Hapus</span>
                    </DangerButton>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
