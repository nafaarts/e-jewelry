<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DepositLayout from "@/Layouts/DepositLayout.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";

import { currencyFormatter } from "@/utils/currencyFormatter";
import moment from "moment";
import Swal from "sweetalert2";
import SwalConfig from "@/utils/sweetalert.conf";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    account: Object,
    transaction: Object,
});

const cancelTransaction = () => {
    // deposits.transactions

    Swal.fire({
        title: "Konfirmasi",
        html: `<p>Batalkan transaksi ini?</p>`,
        showCancelButton: true,
        cancelButtonText: "Tidak",
        confirmButtonText: "Ya",
        ...SwalConfig,
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(
                route("deposits.transactions.destroy", [
                    props.account,
                    props.transaction,
                ]),
                {
                    onSuccess: () => {
                        Swal.fire({
                            title: "Berhasil",
                            icon: "success",
                            text: "Transaksi berhasil dibatalkan!",
                            ...SwalConfig,
                        });
                    },
                }
            );
        }
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="`#${account.account_number} | Detail Titipan`" />

        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Detail Titipan
                </h2>
                <Link
                    as="button"
                    :href="route('deposits.transactions.create', account)"
                    :disabled="!account.is_active"
                    class="bg-orange-200 hover:bg-orange-300 transition px-2 py-1 uppercase text-xs rounded disabled:bg-orange-200 disabled:cursor-not-allowed"
                >
                    <i class="fas fa-fw fa-plus"></i>
                    Buat Transaksi
                </Link>
            </div>
        </template>

        <DepositLayout :account="account">
            <div
                class="bg-white overflow-hidden sm:rounded-lg border p-5 space-y-6"
            >
                <h6 class="font-bold">Detail Transaksi</h6>

                <hr />

                <table class="text-left">
                    <tr>
                        <th scope="col">Nomor Transaksi</th>
                        <td class="px-2">:</td>
                        <td class="font-bold">
                            {{ transaction.transaction_number }}
                        </td>
                    </tr>
                    <tr v-if="transaction.is_canceled">
                        <th scope="col">Status</th>
                        <td class="px-2">:</td>
                        <td>
                            <span class="text-red-500 text-xs font-bold">
                                <i class="fas fa-fw fa-cancel"></i>
                                DIBATALKAN
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="col">Tanggal</th>
                        <td class="px-2">:</td>
                        <td>
                            {{
                                moment(transaction.created_at).format(
                                    "DD MMMM YYYY HH:mm"
                                )
                            }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="col">Pramuniaga</th>
                        <td class="px-2">:</td>
                        <td>{{ transaction.created_by?.name }}</td>
                    </tr>
                </table>

                <table class="text-left">
                    <tr>
                        <th scope="col">Jenis</th>
                        <td class="px-2">:</td>
                        <td
                            class="font-bold"
                            :class="{
                                'text-green-600': transaction.type === 'CREDIT',
                                'text-red-600': transaction.type === 'DEBIT',
                            }"
                        >
                            {{
                                transaction.type === "CREDIT"
                                    ? "TITIP"
                                    : "AMBIL"
                            }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="col">Kategori</th>
                        <td class="px-2">:</td>
                        <td>
                            {{
                                transaction.category === "MONEY"
                                    ? "UANG"
                                    : "EMAS"
                            }}
                        </td>
                    </tr>
                    <tr v-if="transaction.category === 'GOLD'">
                        <th scope="col">Berat</th>
                        <td class="px-2">:</td>
                        <td
                            :class="{
                                'text-green-600': transaction.type === 'CREDIT',
                                'text-red-600': transaction.type === 'DEBIT',
                            }"
                        >
                            {{ transaction.weight }} Gr
                        </td>
                    </tr>
                    <tr v-else>
                        <th scope="col">Jumlah</th>
                        <td class="px-2">:</td>
                        <td
                            :class="{
                                'text-green-600': transaction.type === 'CREDIT',
                                'text-red-600': transaction.type === 'DEBIT',
                            }"
                        >
                            {{ currencyFormatter.format(transaction.amount) }}
                        </td>
                    </tr>
                    <tr>
                        <th scope="col">Biaya</th>
                        <td class="px-2">:</td>
                        <td>
                            {{ currencyFormatter.format(transaction.cost) }}
                        </td>
                    </tr>
                </table>

                <table class="text-left">
                    <tr>
                        <th scope="col">Catatan</th>
                        <td class="px-2">:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <span class="text-gray-500 whitespace-pre-line">
                                {{ transaction.remarks ?? "-" }}
                            </span>
                        </td>
                    </tr>
                </table>

                <hr />

                <div class="flex items-center justify-between">
                    <Link :href="route('deposits.show', account)">
                        <SecondaryButton title="Kembali">
                            <i class="fas fa-fw fa-arrow-left me-2 md:m-0"></i>
                            <span class="md:hidden">Kembali</span>
                        </SecondaryButton>
                    </Link>

                    <div v-if="!transaction.is_canceled">
                        <a
                            :href="
                                route('deposits.transactions.print', [
                                    account,
                                    transaction,
                                ])
                            "
                            target="_blank"
                        >
                            <SecondaryButton class="me-2" title="Print Faktur">
                                <i class="fas fa-fw fa-print"></i>
                            </SecondaryButton>
                        </a>
                        <DangerButton
                            @click="cancelTransaction"
                            v-if="$page.props.auth.user.role === 'ADMIN'"
                            title="Batalkan Transaksi"
                        >
                            <i class="fas fa-fw fa-cancel me-2 md:m-0"></i>
                        </DangerButton>
                    </div>
                </div>
            </div>
        </DepositLayout>
    </AuthenticatedLayout>
</template>
