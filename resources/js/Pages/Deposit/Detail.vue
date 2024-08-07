<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import moment from "moment";
import { currencyFormatter } from "@/utils/currencyFormatter";
import Table from "@/Components/Table.vue";
import SearchInput from "@/Components/SearchInput.vue";
import Pagination from "@/Components/Pagination.vue";
import { reactive, watch } from "vue";
import debounce from "lodash/debounce";
import { router } from "@inertiajs/vue3";

import DepositLayout from "@/Layouts/DepositLayout.vue";

const props = defineProps({
    account: Object,
    transactions: Object,
});

const filters = reactive({
    search: props.filters?.search || "",
    order: props.filters?.order || "",
});

watch(
    filters,
    debounce(function (value) {
        const data = ["search", "order"].reduce((acc, key) => {
            return value[key] ? { ...acc, [key]: value[key] } : acc;
        }, {});
        router.get(route("deposits.show", props.account), data, {
            preserveState: true,
            replace: true,
        });
    }, 500)
);
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
            <SearchInput class="mb-3" v-model="filters.search" />

            <div class="bg-white overflow-hidden sm:rounded-lg border">
                <Table>
                    <template #head>
                        <tr>
                            <th scope="col" class="px-4 py-3 whitespace-nowrap">
                                Nomor Transaksi
                            </th>
                            <th scope="col" class="px-4 py-3 whitespace-nowrap">
                                Jenis
                            </th>
                            <th scope="col" class="px-4 py-3 whitespace-nowrap">
                                Jumlah
                            </th>
                            <th scope="col" class="px-4 py-3 whitespace-nowrap">
                                Kategori
                            </th>
                            <th scope="col" class="px-4 py-3 whitespace-nowrap">
                                Tanggal
                            </th>
                            <th scope="col" class="px-4 py-3 whitespace-nowrap">
                                Aksi
                            </th>
                        </tr>
                    </template>

                    <tr v-if="transactions.data.length == 0">
                        <td colspan="6" class="px-4 py-24 text-center">
                            <p>Tidak ada data!</p>
                        </td>
                    </tr>

                    <tr
                        class="bg-white hover:bg-gray-50 border-b"
                        :class="{
                            'bg-red-50 hover:bg-red-50 opacity-50':
                                transaction.is_canceled,
                        }"
                        v-for="transaction in transactions.data"
                        :key="transaction.id"
                    >
                        <td class="px-4 py-2">
                            <div
                                class="font-medium text-gray-900 whitespace-nowrap"
                            >
                                {{ transaction.transaction_number }}
                            </div>
                        </td>
                        <td class="px-4 py-2">
                            {{
                                transaction.type === "CREDIT"
                                    ? "TITIP"
                                    : "AMBIL"
                            }}
                        </td>
                        <td class="px-4 py-2">
                            <div
                                class="font-medium text-gray-900 whitespace-nowrap"
                                :class="{
                                    'text-green-600':
                                        transaction.type === 'CREDIT',
                                    'text-red-600':
                                        transaction.type === 'DEBIT',
                                }"
                            >
                                {{
                                    transaction.category === "MONEY"
                                        ? currencyFormatter.format(
                                              transaction.amount
                                          )
                                        : `${transaction.weight} Gr`
                                }}
                            </div>
                        </td>
                        <td class="px-4 py-2">
                            {{
                                transaction.category === "GOLD"
                                    ? "EMAS"
                                    : "UANG"
                            }}
                        </td>
                        <td class="px-4 py-2">
                            <div class="whitespace-nowrap">
                                {{
                                    moment(transaction.created_at).format(
                                        "DD MMMM YYYY HH:mm"
                                    )
                                }}
                            </div>
                        </td>
                        <td class="px-4 py-2">
                            <div class="flex gap-3">
                                <Link
                                    as="button"
                                    :href="
                                        route('deposits.transactions.show', [
                                            account,
                                            transaction,
                                        ])
                                    "
                                    class="py-1 px-2 transition bg-green-200 hover:bg-green-300 text-gray-900 rounded"
                                >
                                    <i class="fas fa-fw fa-eye"></i>
                                </Link>
                            </div>
                        </td>
                    </tr>
                </Table>
            </div>

            <Pagination :links="transactions.links" class="mt-5 mb-3" />
        </DepositLayout>
    </AuthenticatedLayout>
</template>
