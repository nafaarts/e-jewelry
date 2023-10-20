<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Table from "@/Components/Table.vue";
import Pagination from "@/Components/Pagination.vue";
import SearchInput from "@/Components/SearchInput.vue";
import { router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import debounce from "lodash/debounce";
import moment from "moment";
import { currencyFormatter } from "@/utils/currencyFormatter";

let props = defineProps({
    sales: Object,
    filters: Object,
});

let search = ref(props.filters?.search || "");

watch(
    search,
    debounce(function (value) {
        let data = value === "" ? {} : { search: value };
        router.get(route("sales.index"), data, {
            preserveState: true,
            replace: true,
        });
    }, 500)
);

</script>

<template>
    <AuthenticatedLayout>

        <Head title="Penjualan" />

        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Penjualan
                </h2>
                <Link as="button" :href="route('sales.create')"
                    class="bg-orange-200 hover:bg-orange-300 transition px-2 py-1 uppercase text-xs rounded">
                <i class="fas fa-fw fa-plus"></i>
                Tambah Penjualan
                </Link>
            </div>
        </template>

        <SearchInput class="mb-3" v-model="search" />

        <div class="bg-white overflow-hidden sm:rounded-lg border">
            <Table>
                <template #head>
                    <tr>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">
                            Kode
                        </th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">
                            Kostumer
                        </th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">
                            Total harga
                        </th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">
                            Ditambah Pada
                        </th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">
                            Aksi
                        </th>
                    </tr>
                </template>
                <tr v-if="sales.data.length == 0">
                    <td colspan="5" class="px-4 py-14 text-center">
                        <p>Tidak ada data!</p>
                    </td>
                </tr>

                <tr class="bg-white border-b" v-for="sale in sales.data" :key="sale.id">
                    <td class="px-4 py-2">
                        <div class="font-medium text-gray-900 whitespace-nowrap">
                            {{ sale.sale_number }}
                        </div>
                    </td>
                    <td class="px-4 py-2">
                        <p class="w-fit max-w-xs truncate">
                            {{ sale.costumer }}
                        </p>
                    </td>
                    <td class="px-4 py-2">
                        <p class="flex gap-1 w-fit max-w-xs truncate">
                            <span class="font-bold">{{ currencyFormatter.format(sale.total_amount) }}</span>
                            <span>({{ sale.total_items }} item)</span>
                        </p>
                    </td>
                    <td class="px-4 py-2">
                        {{
                            moment(sale.created_at).format(
                                "DD MMMM YYYY HH:mm"
                            )
                        }}
                    </td>
                    <td class="px-4 py-2">
                        <div class="flex gap-3">
                            <Link as="button" :href="route('sales.show', sale)"
                                class="py-1 px-2 transition bg-green-200 hover:bg-green-300 text-gray-900 rounded">
                            <i class="fas fa-fw fa-eye"></i> Lihat
                            </Link>
                        </div>
                    </td>
                </tr>
            </Table>
        </div>

        <Pagination :links="sales.links" class="mt-5" />
    </AuthenticatedLayout>
</template>
