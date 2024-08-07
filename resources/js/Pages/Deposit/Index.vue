<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import SearchInput from "@/Components/SearchInput.vue";
import Table from "@/Components/Table.vue";
import Pagination from "@/Components/Pagination.vue";
import { reactive, watch } from "vue";
import moment from "moment";
import debounce from "lodash/debounce";
import { router } from "@inertiajs/vue3";

let props = defineProps({
    accounts: Object,
    filters: Object,
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
        router.get(route("deposits.index"), data, {
            preserveState: true,
            replace: true,
        });
    }, 500)
);
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Titipan" />

        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Titipan
                </h2>
                <Link
                    as="button"
                    :href="route('deposits.create')"
                    class="bg-orange-200 hover:bg-orange-300 transition px-2 py-1 uppercase text-xs rounded"
                >
                    <i class="fas fa-fw fa-plus"></i>
                    Tambah Akun Titipan
                </Link>
            </div>
        </template>

        <SearchInput class="mb-3" v-model="filters.search" />

        <div class="bg-white overflow-hidden sm:rounded-lg border">
            <Table>
                <template #head>
                    <tr>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">
                            Kode Akun
                        </th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">
                            Kostumer
                        </th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">
                            Status
                        </th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">
                            Jumlah Transaksi
                        </th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">
                            Ditambah Pada
                        </th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">
                            Aksi
                        </th>
                    </tr>
                </template>

                <tr v-if="accounts.data.length == 0">
                    <td colspan="6" class="px-4 py-14 text-center">
                        <p>Tidak ada data!</p>
                    </td>
                </tr>

                <tr
                    class="bg-white hover:bg-gray-50 border-b"
                    v-for="account in accounts.data"
                    :key="account.id"
                >
                    <td class="px-4 py-2">
                        <div
                            class="font-medium text-gray-900 whitespace-nowrap"
                        >
                            {{ account.account_number }}
                        </div>
                    </td>
                    <td class="px-4 py-2">
                        <p class="w-fit max-w-xs truncate">
                            {{ account.costumer?.name }}
                        </p>
                    </td>
                    <td class="px-4 py-2">
                        <div class="flex items-center">
                            <div
                                :class="{
                                    'bg-green-500': account.is_active,
                                    'bg-yellow-500': !account.is_active,
                                }"
                                class="h-2.5 w-2.5 rounded-full mr-2"
                            ></div>
                            {{ account.is_active ? "AKTIF" : "TIDAK AKTIF" }}
                        </div>
                    </td>
                    <td class="px-4 py-2">
                        {{ account.transactions_count }} Transaksi
                    </td>
                    <td class="px-4 py-2">
                        {{
                            moment(account.created_at).format(
                                "DD MMMM YYYY HH:mm"
                            )
                        }}
                    </td>
                    <td class="px-4 py-2">
                        <div class="flex gap-3">
                            <Link
                                as="button"
                                :href="route('deposits.show', account)"
                                class="py-1 px-2 transition bg-green-200 hover:bg-green-300 text-gray-900 rounded"
                            >
                                <i class="fas fa-fw fa-eye"></i> Lihat
                            </Link>
                        </div>
                    </td>
                </tr>
            </Table>
        </div>

        <Pagination :links="accounts.links" class="mt-5" />
    </AuthenticatedLayout>
</template>
