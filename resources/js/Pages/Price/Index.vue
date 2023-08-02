<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Table from "@/Components/Table.vue";
import Pagination from "@/Components/Pagination.vue";
import SearchInput from "@/Components/SearchInput.vue";
import { router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import debounce from "lodash/debounce";
import moment from "moment";

import Swal from "sweetalert2";
import SwalConfig from "@/utils/sweetalert.conf";

import { currencyFormatter } from "@/utils/currencyFormatter";

let props = defineProps({
    prices: Object,
    filters: Object,
});

let search = ref(props.filters?.search || "");

watch(
    search,
    debounce(function (value) {
        let data = value === "" ? {} : { search: value };
        router.get(route("prices.index"), data, {
            preserveState: true,
            replace: true,
        });
    }, 500)
);

const confirmDelete = (id, date) => {
    Swal.fire({
        title: "Konfirmasi",
        html: `<span>Apakah anda yakin menghapus harga pada tanggal <strong>${date}</strong> ?</span>`,
        showCancelButton: true,
        cancelButtonText: "Tidak",
        confirmButtonText: "Ya",
        ...SwalConfig,
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("prices.destroy", id), {
                onSuccess: () => {
                    Swal.fire({
                        title: "Berhasil",
                        icon: "success",
                        text: "Harga berhasil dihapus!",
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
        <Head title="Harga dan Kadar" />

        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Harga dan Kadar
                </h2>
                <Link
                    as="button"
                    :href="route('prices.create')"
                    class="bg-orange-200 hover:bg-orange-300 transition px-2 py-1 uppercase text-xs rounded"
                >
                    <i class="fas fa-fw fa-plus"></i>
                    Tambah Harga dan Kadar
                </Link>
            </div>
        </template>

        <SearchInput class="mb-3" v-model="search" />

        <div class="bg-white overflow-hidden sm:rounded-lg border">
            <Table>
                <template #head>
                    <tr>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">
                            Karat
                        </th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">
                            Harga Jual
                        </th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">
                            Harga Beli
                        </th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">
                            Kategori
                        </th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">
                            Terakhir diubah
                        </th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">
                            Aksi
                        </th>
                    </tr>
                </template>
                <tr v-if="prices.data.length == 0">
                    <td colspan="8" class="px-4 py-14 text-center">
                        <p>Tidak ada data!</p>
                    </td>
                </tr>
                <tr
                    class="bg-white border-b"
                    v-for="price in prices.data"
                    :key="price.id"
                >
                    <td class="px-4 py-2">
                        <div
                            class="font-medium text-gray-900 whitespace-nowrap"
                        >
                            {{ price.name }}
                        </div>
                        <div class="font-normal text-gray-500">
                            {{
                                `${price.weight} Gram - ${price.carat} (${price.rate}%)`
                            }}
                        </div>
                    </td>
                    <td class="px-4 py-2">
                        <div class="text-gray-900 whitespace-nowrap">
                            {{ currencyFormatter.format(price.sell_price) }}
                            <span v-if="price.cost" class="text-gray-500">
                                {{
                                    `+ ${currencyFormatter.format(price.cost)}`
                                }}
                            </span>
                        </div>
                    </td>
                    <td class="px-4 py-2">
                        <div class="text-gray-900 whitespace-nowrap">
                            {{ currencyFormatter.format(price.buy_price) }}
                        </div>
                    </td>
                    <td class="px-4 py-2">
                        <div class="text-gray-900 whitespace-nowrap">
                            {{ price.category }}
                        </div>
                    </td>
                    <td class="px-4 py-2">
                        <div class="whitespace-nowrap">
                            {{
                                moment(price.updated_at).format(
                                    "DD MMMM YYYY HH:mm"
                                )
                            }}
                        </div>
                    </td>
                    <td class="px-4 py-2">
                        <div class="flex gap-3">
                            <Link
                                as="button"
                                :href="route('prices.edit', price.id)"
                                class="p-1 transition bg-yellow-200 hover:bg-yellow-300 text-gray-900 rounded"
                            >
                                <i class="fas fa-fw fa-edit"></i>
                            </Link>
                            <button
                                @click="
                                    confirmDelete(
                                        price.id,
                                        moment(price.updated_at).format(
                                            'DD MMMM YYYY'
                                        )
                                    )
                                "
                                class="p-1 transition bg-red-600 hover:bg-red-700 text-white rounded"
                            >
                                <i class="fas fa-fw fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </Table>
        </div>

        <Pagination :links="prices.links" class="mt-5" />
    </AuthenticatedLayout>
</template>
