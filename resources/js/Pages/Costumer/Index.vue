<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Table from "@/Components/Table.vue";
import Pagination from "@/Components/Pagination.vue";
import SearchInput from "@/Components/SearchInput.vue";
import ImageCover from "@/Components/ImageCover.vue";
import { router } from "@inertiajs/vue3";
import { reactive, watch } from "vue";
import debounce from "lodash/debounce";
import moment from "moment";

import Swal from "sweetalert2";
import SwalConfig from "@/utils/sweetalert.conf";
import TableHeader from "@/Components/TableHeader.vue";

let props = defineProps({
    costumers: Object,
    filters: Object,
});

const filters = reactive({
    search: props.filters?.search ?? "",
    order: props.filters?.order ?? "",
});

watch(
    filters,
    debounce(function (value) {
        const data = ["search", "order"].reduce((acc, key) => {
            return value[key] ? { ...acc, [key]: value[key] } : acc;
        }, {});
        router.get(route("costumers.index"), data, {
            preserveState: true,
            replace: true,
        });
    }, 500)
);

const confirmDelete = (id, name) => {
    Swal.fire({
        title: "Konfirmasi",
        html: `<span>Apakah anda yakin menghapus data <strong>${name}</strong> ?</span>`,
        showCancelButton: true,
        cancelButtonText: "Tidak",
        confirmButtonText: "Ya",
        ...SwalConfig,
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route("costumers.destroy", id), {
                onSuccess: () => {
                    Swal.fire({
                        title: "Berhasil",
                        icon: "success",
                        text: "Kastamer berhasil dihapus!",
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
        <Head title="Kastamer" />

        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Kastamer
                </h2>
                <Link
                    as="button"
                    :href="route('costumers.create')"
                    class="bg-orange-200 hover:bg-orange-300 transition px-2 py-1 uppercase text-xs rounded"
                >
                    <i class="fas fa-fw fa-plus"></i>
                    Tambah Kastamer
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
                                name: 'Kode Costumer',
                                label: 'costumer_code',
                                sort: true,
                            },
                            {
                                name: 'Nomor Telepon',
                                label: 'phone_number',
                                sort: false,
                            },
                            { name: 'Alamat', label: 'address', sort: false },
                            {
                                name: 'Ditambah pada',
                                label: 'created_at',
                                sort: true,
                            },
                            { name: 'Aksi', sort: false },
                        ]"
                    />
                </template>
                <tr v-if="costumers.data.length == 0">
                    <td colspan="6" class="px-4 py-14 text-center">
                        <p>Tidak ada data!</p>
                    </td>
                </tr>
                <tr
                    class="bg-white border-b"
                    v-for="costumer in costumers.data"
                    :key="costumer.id"
                >
                    <td class="px-4 py-2">
                        <div class="flex items-center">
                            <ImageCover
                                class="w-10 h-10 rounded-full bg-zinc-300"
                                :src="
                                    costumer.photo
                                        ? 'storage/' + costumer.photo
                                        : '/images/default-user.png'
                                "
                            />
                            <div class="pl-3">
                                <div
                                    class="font-medium text-gray-900 whitespace-nowrap"
                                >
                                    {{ costumer.name }}
                                </div>
                                <div class="font-normal text-gray-500">
                                    {{ costumer.indentity_number }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-2">
                        <div
                            class="font-medium text-gray-900 whitespace-nowrap"
                        >
                            {{ costumer.costumer_code }}
                        </div>
                    </td>
                    <td class="px-4 py-2">
                        <div class="whitespace-nowrap">
                            {{ costumer.phone_number }}
                        </div>
                    </td>
                    <td class="px-4 py-2">
                        <p class="w-fit max-w-xs truncate">
                            {{ costumer.address }}
                        </p>
                    </td>
                    <td class="px-4 py-2">
                        <div class="whitespace-nowrap">
                            {{
                                moment(costumer.created_at).format(
                                    "DD MMMM YYYY HH:mm"
                                )
                            }}
                        </div>
                    </td>
                    <td class="px-4 py-2">
                        <div class="flex gap-3">
                            <Link
                                as="button"
                                :href="route('costumers.edit', costumer.id)"
                                class="p-1 transition bg-yellow-200 hover:bg-yellow-300 text-gray-900 rounded"
                            >
                                <i class="fas fa-fw fa-edit"></i>
                            </Link>
                            <button
                                @click="
                                    confirmDelete(costumer.id, costumer.name)
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

        <Pagination :links="costumers.links" class="mt-5" />
    </AuthenticatedLayout>
</template>
