<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Table from "@/Components/Table.vue";
import Pagination from "@/Components/Pagination.vue";
import SearchInput from "@/Components/SearchInput.vue";
import { router } from "@inertiajs/vue3";
import { reactive, watch } from "vue";
import debounce from "lodash/debounce";
import moment from "moment";
import Swal from "sweetalert2";
import SwalConfig from "@/utils/sweetalert.conf";
import TableHeader from "@/Components/TableHeader.vue";

let props = defineProps({
    categories: Object,
    filters: Object,
});

let filters = reactive({
    search: props.filters?.search ?? "",
    order: props.filters?.order ?? "",
});

watch(
    filters,
    debounce(function (value) {
        const data = ["search", "order"].reduce((acc, key) => {
            return value[key] ? { ...acc, [key]: value[key] } : acc;
        }, {});
        router.get(route("categories.index"), data, {
            preserveState: true,
            replace: true,
        });
    }, 500)
);

const confirmDelete = (id, name) => {
    Swal.fire({
        title: "Konfirmasi",
        html: `<span>Apakah anda yakin menghapus kategori <strong>${name}</strong> ?</span>`,
        showCancelButton: true,
        cancelButtonText: "Tidak",
        confirmButtonText: "Ya",
        ...SwalConfig,
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("categories.destroy", id), {
                onSuccess: () => {
                    Swal.fire({
                        title: "Berhasil",
                        icon: "success",
                        text: "Kategori berhasil dihapus!",
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
        <Head title="Kategori" />

        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Kategori
                </h2>
                <Link
                    as="button"
                    :href="route('categories.create')"
                    class="bg-orange-200 hover:bg-orange-300 transition px-2 py-1 uppercase text-xs rounded"
                >
                    <i class="fas fa-fw fa-plus"></i>
                    Tambah Kategori
                </Link>
            </div>
        </template>

        <SearchInput class="mb-3" v-model="filters.search" />

        <div class="bg-white overflow-hidden sm:rounded-lg border">
            <Table>
                <template #head>
                    <TableHeader
                        v-model="filters.order"
                        :items="[
                            { name: 'Nama', label: 'name', sort: true },
                            {
                                name: 'Jumlah',
                                label: 'jewelries_count',
                                sort: true,
                            },
                            { name: 'Catatan', label: 'remarks', sort: false },
                            {
                                name: 'Ditambah pada',
                                label: 'created_at',
                                sort: true,
                            },
                            { name: 'Aksi', sort: false },
                        ]"
                    />
                </template>
                <tr v-if="categories.data.length == 0">
                    <td colspan="6" class="px-4 py-14 text-center">
                        <p>Tidak ada data!</p>
                    </td>
                </tr>
                <tr
                    class="bg-white border-b"
                    v-for="category in categories.data"
                    :key="category.id"
                >
                    <td class="px-4 py-2">
                        <div
                            class="font-medium text-gray-900 whitespace-nowrap"
                        >
                            {{ category.name }}
                        </div>
                    </td>
                    <td class="px-4 py-2">
                        {{ category.jewelries_count }} barang
                    </td>
                    <td class="px-4 py-2">
                        <p class="w-fit max-w-56 truncate">
                            {{ category.remarks || "-" }}
                        </p>
                    </td>
                    <td class="px-4 py-2">
                        {{
                            moment(category.created_at).format(
                                "DD MMMM YYYY HH:mm"
                            )
                        }}
                    </td>
                    <td class="px-4 py-2">
                        <div class="flex gap-3">
                            <Link
                                as="button"
                                :href="route('categories.edit', category.id)"
                                class="p-1 transition bg-yellow-200 hover:bg-yellow-300 text-gray-900 rounded"
                            >
                                <i class="fas fa-fw fa-edit"></i>
                            </Link>
                            <button
                                @click="
                                    confirmDelete(category.id, category.name)
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

        <Pagination :links="categories.links" class="mt-5" />
    </AuthenticatedLayout>
</template>
