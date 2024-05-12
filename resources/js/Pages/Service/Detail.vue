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
import status from "@/Constant/ServiceStatus";
import { ref } from "vue";
import { router, useForm } from "@inertiajs/vue3";
import moment from "moment";

const props = defineProps({
    service: Object,
});

const updateStatus = ref(false);

const form = useForm({
    type: "status",
    status: props.service.status,
});

const paidForm = useForm({
    type: "paid-full",
});

const takenForm = useForm({
    type: "taken",
});

const confirmDelete = (name) => {
    Swal.fire({
        title: "Konfirmasi",
        html: `<span>Hapus data perbaikan <strong>${name}</strong> ?</span>`,
        showCancelButton: true,
        cancelButtonText: "Tidak",
        confirmButtonText: "Ya",
        ...SwalConfig,
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("services.destroy", props.service.id), {
                onSuccess: () => {
                    Swal.fire({
                        title: "Berhasil",
                        icon: "success",
                        text: "Service berhasil dihapus!",
                        ...SwalConfig,
                    });
                },
            });
        }
    });
};

const confirmDateTaken = () => {
    Swal.fire({
        title: "Konfirmasi",
        html: `<span>Apakah anda yakin mengkonfirmasi pengambilan service ini?</span>`,
        showCancelButton: true,
        cancelButtonText: "Tidak",
        confirmButtonText: "Ya",
        ...SwalConfig,
    }).then((result) => {
        if (result.isConfirmed) {
            takenForm.patch(route("services.update", props.service.id), {
                onSuccess: () => {
                    Swal.fire({
                        title: "Berhasil",
                        icon: "success",
                        text: "Service berhasil diambil!",
                        ...SwalConfig,
                    });
                },
            });
        }
    });
};

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
            form.patch(route("services.update", props.service.id), {
                onSuccess: () => {
                    Swal.fire({
                        title: "Berhasil",
                        icon: "success",
                        text: "Status service berhasil diubah!",
                        ...SwalConfig,
                    });

                    updateStatus.value = false;
                },
            });
        }
    });
};

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
            paidForm.patch(route("services.update", props.service.id), {
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
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Detail Perbaikan" />

        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Detail Perbaikan
                </h2>
            </div>
        </template>

        <div
            class="bg-white overflow-hidden sm:rounded-lg border p-4 sm:p-8 space-y-4 mb-4"
        >
            <div class="flex flex-col md:flex-row gap-4">
                <div class="w-full md:w-1/2 space-y-6">
                    <table class="text-left">
                        <tr>
                            <th>Kode Perbaikan</th>
                            <td class="px-3">:</td>
                            <td>
                                <span class="font-bold">
                                    {{ service.service_number }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td class="px-3">:</td>
                            <td>{{ service.category.name }}</td>
                        </tr>
                        <tr>
                            <th>Berat Perhiasan</th>
                            <td class="px-3">:</td>
                            <td>{{ service.weight }} Gr</td>
                        </tr>
                        <tr>
                            <th>Biaya/Ongkos</th>
                            <td class="px-3">:</td>
                            <td>
                                {{ currencyFormatter.format(service.cost) }}
                            </td>
                        </tr>
                        <tr>
                            <th>Total dibayar</th>
                            <td class="px-3">:</td>
                            <td>
                                <div class="flex gap-2">
                                    <span>{{
                                        currencyFormatter.format(
                                            service.paid_amount
                                        )
                                    }}</span>
                                    <div
                                        class="flex items-center bg-green-500 rounded text-white px-2"
                                        v-if="
                                            service.paid_amount === service.cost
                                        "
                                    >
                                        <small>Lunas</small>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="service.paid_amount !== service.cost">
                            <th>Total sisa</th>
                            <td class="px-3">:</td>
                            <td class="font-medium text-red-600">
                                {{
                                    currencyFormatter.format(
                                        service.cost - service.paid_amount
                                    )
                                }}
                            </td>
                        </tr>
                        <tr>
                            <th>Estimasi Selesai</th>
                            <td class="px-3">:</td>
                            <td>
                                {{
                                    service.estimated_date
                                        ? moment(service.estimated_date).format(
                                              "DD MMMM YYYY"
                                          )
                                        : "-"
                                }}
                            </td>
                        </tr>
                        <tr>
                            <th>Tanggal Diambil</th>
                            <td class="px-3">:</td>
                            <td>
                                {{
                                    service.date_taken
                                        ? moment(service.date_taken).format(
                                              "DD MMMM YYYY"
                                          )
                                        : "-"
                                }}
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td class="px-3">:</td>
                            <td>
                                <div class="flex gap-2">
                                    <span>{{ service.status }}</span>
                                    <button
                                        @click="
                                            () => (updateStatus = !updateStatus)
                                        "
                                        class="text-orange-400 hover:text-orange-600 cursor-pointer"
                                    >
                                        <i class="fas fa-fw fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </table>

                    <div v-if="updateStatus" class="space-y-3">
                        <InputLabel for="status" value="Status" />
                        <Select v-model="form.status">
                            <option value="">- Pilih status -</option>
                            <option
                                v-for="(item, index) in status"
                                :key="index"
                                :value="item"
                            >
                                {{ item }}
                            </option>
                        </Select>
                        <div class="flex gap-2">
                            <PrimaryButton
                                type="button"
                                @click="confirmStatusUpdate"
                            >
                                <i class="fas fa-fw fa-check"></i>
                            </PrimaryButton>
                            <SecondaryButton
                                type="button"
                                @click="() => (updateStatus = false)"
                            >
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
                                    {{ service.remarks }}
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
                            <td>{{ service.costumer?.name }}</td>
                        </tr>
                        <tr>
                            <th>Nomor Indentitas</th>
                            <td class="px-3">:</td>
                            <td>
                                {{ service.costumer?.indentity_number ?? "-" }}
                            </td>
                        </tr>
                        <tr>
                            <th>Nomor Telepon</th>
                            <td class="px-3">:</td>
                            <td>{{ service.costumer?.phone_number }}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td class="px-3">:</td>
                            <td>{{ service.costumer?.address }}</td>
                        </tr>
                    </table>

                    <table class="text-left">
                        <tr>
                            <th>Dibuat pada:</th>
                            <td class="px-3">:</td>
                            <td>
                                {{
                                    moment(service.created_at).format(
                                        "DD MMMM YYYY HH:mm"
                                    )
                                }}
                                <span class="text-gray-600">
                                    ({{ service.created_by?.name }})
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Diupdate pada:</th>
                            <td class="px-3">:</td>
                            <td>
                                {{
                                    moment(service.updated_at).format(
                                        "DD MMMM YYYY HH:mm"
                                    )
                                }}
                                <span class="text-gray-600">
                                    ({{ service.updated_by?.name }})
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div
            class="bg-white overflow-hidden sm:rounded-lg border p-4 sm:p-8 space-y-4"
        >
            <div class="flex flex-col md:flex-row justify-between gap-3">
                <Link :href="route('services.index')">
                    <SecondaryButton class="w-full h-full">
                        <i class="fas fa-fw fa-arrow-left me-2 md:m-0"></i>
                        <span class="md:hidden">Kembali</span>
                    </SecondaryButton>
                </Link>
                <div class="flex flex-col md:flex-row gap-3">
                    <SecondaryButton
                        v-if="
                            !service.date_taken &&
                            service.paid_amount === service.cost
                        "
                        @click="confirmDateTaken"
                    >
                        <i class="fas fa-fw fa-hand me-2"></i> Konfirmasi
                        Pengambilan
                    </SecondaryButton>
                    <SecondaryButton
                        v-if="service.paid_amount !== service.cost"
                        @click="confirmPaidFull"
                    >
                        <i class="fas fa-fw fa-check me-2"></i> Tetapkan Lunas
                    </SecondaryButton>
                    <a
                        :href="route('services.print', service.id)"
                        target="_blank"
                    >
                        <SecondaryButton class="w-full">
                            <i class="fas fa-fw fa-download me-2"></i> Cetak
                            Faktur
                        </SecondaryButton>
                    </a>
                    <DangerButton
                        @click="confirmDelete(service?.service_number)"
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
