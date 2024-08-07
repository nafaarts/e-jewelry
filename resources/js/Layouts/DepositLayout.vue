<script setup>
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Card from "@/Components/Card.vue";
import moment from "moment";
import { currencyFormatter } from "@/utils/currencyFormatter";
import Table from "@/Components/Table.vue";
import { router } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import SwalConfig from "@/utils/sweetalert.conf";

const props = defineProps({
    account: Object,
});

const confirmToggleStatus = () => {
    Swal.fire({
        title: "Konfirmasi",
        html: `<span>Apakah anda yakin untuk beralih status akun?</span>`,
        showCancelButton: true,
        cancelButtonText: "Tidak",
        confirmButtonText: "Ya",
        ...SwalConfig,
    }).then((result) => {
        if (result.isConfirmed) {
            router.patch(route("deposits.update", props.account), {
                onSuccess: () => {
                    Swal.fire({
                        title: "Berhasil",
                        icon: "success",
                        text: "Status berhasil diubah!",
                        ...SwalConfig,
                    });
                },
            });
        }
    });
};

const confirmDelete = () => {
    Swal.fire({
        title: "Konfirmasi",
        html: `<span>Apakah anda yakin menghapus akun ini?</span>`,
        showCancelButton: true,
        cancelButtonText: "Tidak",
        confirmButtonText: "Ya",
        ...SwalConfig,
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("deposits.destroy", props.account), {
                onSuccess: () => {
                    Swal.fire({
                        title: "Berhasil",
                        icon: "success",
                        text: "Akun Deposit berhasil dihapus!",
                        ...SwalConfig,
                    });
                },
            });
        }
    });
};
</script>

<template>
    <div class="grid grid-cols-2 md:grid-cols-5 w-full gap-3 mb-3">
        <Card label="Jumlah Transaksi">
            <h4 class="text-2xl">{{ account.transactions_count }}</h4>
        </Card>
        <Card label="Jumlah Emas (Gr)">
            <div class="flex gap-1 items-end">
                <h4 class="text-2xl">{{ account.gold_balance ?? 0 }}</h4>
                <span class="text-gray-400">Gr</span>
            </div>
        </Card>
        <Card
            class="col-span-2 md:col-span-3"
            label="Jumlah Saldo"
            variant="primary"
        >
            <h4 class="text-2xl">
                {{ currencyFormatter.format(account.money_balance) }}
            </h4>
        </Card>
    </div>

    <div class="flex flex-col-reverse md:flex-row gap-3">
        <div class="w-full md:w-[400px] gap-3">
            <div
                class="bg-white overflow-hidden sm:rounded-lg border p-5 space-y-6"
            >
                <table class="text-left">
                    <tr>
                        <th>Kode Akun</th>
                        <td class="px-3">:</td>
                        <td>
                            <span class="font-bold text-orange-500">
                                {{ account.account_number }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Kastamer</th>
                        <td class="px-3">:</td>
                        <td>{{ account.costumer?.name }}</td>
                    </tr>
                    <tr>
                        <th>No. Telp</th>
                        <td class="px-3">:</td>
                        <td>
                            {{ account.costumer?.phone_number }}
                        </td>
                    </tr>
                    <tr>
                        <th>No. Indentitas</th>
                        <td class="px-3">:</td>
                        <td>
                            {{ account.costumer?.indentity_number ?? "-" }}
                        </td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td class="px-3">:</td>
                        <td>{{ account.costumer?.address }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td class="px-3">:</td>
                        <td>
                            <div class="flex items-center text-xs">
                                <div
                                    :class="{
                                        'bg-green-500': account.is_active,
                                        'bg-yellow-500': !account.is_active,
                                    }"
                                    class="h-2.5 w-2.5 rounded-full mr-2"
                                ></div>
                                {{
                                    account.is_active ? "AKTIF" : "TIDAK AKTIF"
                                }}
                            </div>
                        </td>
                    </tr>
                </table>

                <table class="text-left">
                    <tr>
                        <th scope="row">Catatan :</th>
                    </tr>
                    <tr>
                        <td>
                            <span class="text-gray-500 whitespace-pre-line">
                                {{ account.remarks ?? "-" }}
                            </span>
                        </td>
                    </tr>
                </table>

                <hr />

                <table class="text-left">
                    <tr>
                        <th scope="row">Dibuat Pada :</th>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <span class="text-gray-500 whitespace-pre-line">
                                {{
                                    moment(account.created_at).format(
                                        "DD MMMM YYYY HH:mm"
                                    )
                                }}
                                ({{ account.created_by?.name }})
                            </span>
                        </td>
                    </tr>
                </table>
            </div>

            <div
                class="bg-white overflow-hidden sm:rounded-lg border p-5 space-y-4 mt-3"
            >
                <div class="flex justify-between w-full">
                    <div class="flex items-center gap-2">
                        <Link :href="route('deposits.index')">
                            <SecondaryButton>
                                <i class="fas fa-fw fa-arrow-left"></i>
                            </SecondaryButton>
                        </Link>
                    </div>

                    <div v-if="$page.props.auth.user.role === 'ADMIN'">
                        <SecondaryButton
                            @click="confirmToggleStatus"
                            title="Toggle Status"
                            class="me-2"
                        >
                            <i
                                class="fas fa-fw"
                                :class="{
                                    'fa-toggle-off': account.is_active == 0,
                                    'fa-toggle-on': account.is_active == 1,
                                }"
                            ></i>
                        </SecondaryButton>

                        <DangerButton
                            @click="confirmDelete"
                            title="Hapus"
                            :disabled="account.transactions_count > 0"
                        >
                            <i class="fas fa-fw fa-trash"></i>
                        </DangerButton>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full">
            <slot />
        </div>
    </div>
</template>
