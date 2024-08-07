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
    services: Object,
    filters: Object,
});

let search = ref(props.filters?.search || "");

watch(
    search,
    debounce(function (value) {
        let data = value === "" ? {} : { search: value };
        router.get(route("services.index"), data, {
            preserveState: true,
            replace: true,
        });
    }, 500)
);
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Perbaikan" />

        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Perbaikan
                </h2>
                <Link
                    as="button"
                    :href="route('services.create')"
                    class="bg-orange-200 hover:bg-orange-300 transition px-2 py-1 uppercase text-xs rounded"
                >
                    <i class="fas fa-fw fa-plus"></i>
                    Tambah Perbaikan
                </Link>
            </div>
        </template>

        <SearchInput class="mb-3" v-model="search" />

        <div class="bg-white overflow-hidden sm:rounded-lg border">
            <Table>
                <template #head>
                    <tr>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">
                            Kode Perbaikan
                        </th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">
                            Status
                        </th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">
                            Kostumer
                        </th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">
                            Biaya
                        </th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">
                            Sisa Bayar
                        </th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">
                            Ditambah Pada
                        </th>
                        <th scope="col" class="px-4 py-3 whitespace-nowrap">
                            Aksi
                        </th>
                    </tr>
                </template>
                <tr v-if="services.data.length == 0">
                    <td colspan="7" class="px-4 py-14 text-center">
                        <p>Tidak ada data!</p>
                    </td>
                </tr>

                <tr
                    class="bg-white border-b"
                    v-for="service in services.data"
                    :key="service.id"
                >
                    <td class="px-4 py-2">
                        <div
                            class="font-medium text-gray-900 whitespace-nowrap"
                        >
                            {{ service.service_number }}
                        </div>
                    </td>
                    <td class="px-4 py-2">
                        <p class="w-fit max-w-xs truncate">
                            {{ service.status }}
                        </p>
                    </td>
                    <td class="px-4 py-2">
                        <p class="w-fit max-w-xs truncate">
                            {{ service.costumer?.name }}
                        </p>
                    </td>
                    <td class="px-4 py-2">
                        <p class="w-fit font-medium max-w-xs truncate">
                            {{ currencyFormatter.format(service.cost) }}
                        </p>
                    </td>
                    <td class="px-4 py-2">
                        <div v-if="service.paid_amount === service.cost">
                            <div
                                class="flex items-center bg-green-500 rounded text-white px-2 w-fit"
                            >
                                <small
                                    ><i class="fas fa-fw fa-check"></i>
                                    Lunas</small
                                >
                            </div>
                        </div>
                        <p
                            class="w-fit max-w-xs truncate font-medium text-red-600"
                            v-else
                        >
                            {{
                                currencyFormatter.format(
                                    service.cost - service.paid_amount
                                )
                            }}
                        </p>
                    </td>
                    <td class="px-4 py-2">
                        {{
                            moment(service.created_at).format(
                                "DD MMMM YYYY HH:mm"
                            )
                        }}
                    </td>
                    <td class="px-4 py-2">
                        <div class="flex gap-3">
                            <Link
                                as="button"
                                :href="route('services.show', service)"
                                class="py-1 px-2 transition bg-green-200 hover:bg-green-300 text-gray-900 rounded"
                            >
                                <i class="fas fa-fw fa-eye"></i> Lihat
                            </Link>
                        </div>
                    </td>
                </tr>
            </Table>
        </div>

        <Pagination :links="services.links" class="mt-5" />
    </AuthenticatedLayout>
</template>
