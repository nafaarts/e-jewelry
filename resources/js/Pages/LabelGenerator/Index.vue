<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

import Barcode from "@/Components/Barcode.vue";
import ImageCover from "@/Components/ImageCover.vue";
import SearchInput from "@/Components/SearchInput.vue";
import Table from "@/Components/Table.vue";
import Pagination from "@/Components/Pagination.vue";
import TableHeader from "@/Components/TableHeader.vue";

import { Head, router } from "@inertiajs/vue3";
import { ref, watch, reactive } from "vue";
import debounce from "lodash/debounce";

const props = defineProps({
    jewelries: Object,
    filters: Object,
});

let filters = reactive({
    search: props.filters?.search ?? "",
    order: props.filters?.order ?? "",
});

const printArea = ref(null);
const selectedItems = ref(
    JSON.parse(localStorage.getItem("label-items") ?? "[]")
);

const selectItem = (jewelry) => {
    if (selectedItems.value.some((item) => item.id === jewelry.id)) {
        const data = selectedItems.value.filter(
            (item) => item.id !== jewelry.id
        );

        localStorage.setItem("label-items", JSON.stringify(data));
        selectedItems.value = data;
        return;
    }

    localStorage.setItem(
        "label-items",
        JSON.stringify([...selectedItems.value, jewelry])
    );

    selectedItems.value.push(jewelry);
};

const isSelected = (jewelry) => {
    return selectedItems.value.some((item) => item.id === jewelry.id);
};

const getSelected = () => {
    const selected = selectedItems.value.map((item) => {
        return {
            barcode: item.jewelry_code,
            name: item.name,
            weight: item.weight,
            carat: item.price.carat,
        };
    });

    // divide array into chunks of 2
    let result = [];
    for (let i = 0; i < selected.length; i += 2) {
        result.push(selected.slice(i, i + 2));
    }

    return result;
};

const resetSelected = () => {
    localStorage.removeItem("label-items");
    selectedItems.value = [];
};

const print = () => {
    let previewWindow = window.open("", "MsgWindow", "width=800,height=600");

    let content = `
    <style>
    * {
        padding: 0;
        margin: 0;
        box-sizing: border-box
    }

    .label-container {
        width: 77mm;
        height: 24mm;
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between
    }

    .label-container .label {
        width: 20mm;
        padding: 0.25mm;
        height: 100%;
        background: #fff
    }

    .label-container .label .label-barcode {
        width: 100%;
        height: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .label-container .label .label-detail {
        width: 100%;
        height: 50%;
        font-size: 8px !important;
        text-transform: uppercase;
        font-weight: 600;
        font-family: Arial, Helvetica, sans-serif;
        line-height: 10px !important;
        padding: 5px 10px;
    }

    .label-container .label .label-detail p {
        margin-bottom: 2px !important;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    </style>
    ${printArea.value.innerHTML}`;

    previewWindow.document.write(content);
    previewWindow.print();

    previewWindow.close();
};

watch(
    filters,
    debounce((value) => {
        const data = ["search", "order"].reduce((acc, key) => {
            return value[key] ? { ...acc, [key]: value[key] } : acc;
        }, {});

        router.get(route("label-generator"), data, {
            preserveState: true,
            replace: true,
        });
    }, 500)
);
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Cetak Label" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Cetak Label
            </h2>
        </template>

        <div class="p-4 sm:p-8 bg-white border sm:rounded-lg">
            <div class="flex flex-col md:flex-row mb-6 gap-4">
                <div class="w-full md:w-2/3">
                    <SearchInput class="mb-3" v-model="filters.search" />

                    <div class="bg-white overflow-hidden sm:rounded-lg border">
                        <Table>
                            <template #head>
                                <TableHeader
                                    v-model="filters.order"
                                    :items="[
                                        {
                                            name: 'Aksi',
                                            sort: false,
                                        },
                                        {
                                            name: 'Nama',
                                            label: 'name',
                                            sort: true,
                                        },
                                        {
                                            name: 'Status',
                                            label: 'status',
                                            sort: true,
                                        },
                                        {
                                            name: 'Kategori',
                                            label: 'category.id',
                                            sort: true,
                                        },
                                        {
                                            name: 'Berat',
                                            label: 'weight',
                                            sort: true,
                                        },
                                        {
                                            name: 'Kadar',
                                            label: 'price.id',
                                            sort: true,
                                        },
                                    ]"
                                />
                            </template>
                            <tr v-if="jewelries.data.length == 0">
                                <td colspan="8" class="px-4 py-14 text-center">
                                    <p>Tidak ada data!</p>
                                </td>
                            </tr>
                            <tr
                                class="bg-white border-b"
                                v-for="jewelry in jewelries.data"
                                :key="jewelry.id"
                            >
                                <td class="px-4 py-2">
                                    <button
                                        @click="selectItem(jewelry)"
                                        :class="{
                                            'm-0 p-1 border rounded': true,
                                            'bg-red-500 border-0 text-white':
                                                isSelected(jewelry),
                                        }"
                                    >
                                        <i
                                            :class="{
                                                'fas fa-fw': true,
                                                'fa-trash': isSelected(jewelry),
                                                'fa-plus': !isSelected(jewelry),
                                            }"
                                        />
                                    </button>
                                </td>
                                <td class="px-4 py-2">
                                    <div class="flex items-center">
                                        <ImageCover
                                            class="w-10 h-10 rounded-full bg-zinc-300"
                                            :src="
                                                jewelry.photo
                                                    ? 'storage/' + jewelry.photo
                                                    : '/images/image-placeholder.png'
                                            "
                                        />
                                        <div class="pl-3">
                                            <div
                                                class="font-medium text-gray-900 whitespace-nowrap"
                                            >
                                                {{ jewelry.name }}
                                            </div>
                                            <div
                                                class="font-normal text-gray-500"
                                            >
                                                {{ jewelry.jewelry_code }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-2">
                                    <div class="flex items-center">
                                        <div
                                            :class="{
                                                'bg-green-500':
                                                    jewelry.status == 'READY',
                                                'bg-yellow-500':
                                                    jewelry.status == 'SOLD',
                                            }"
                                            class="h-2.5 w-2.5 rounded-full mr-2"
                                        ></div>
                                        {{
                                            jewelry.status == "READY"
                                                ? "TERSEDIA"
                                                : "TERJUAL"
                                        }}
                                    </div>
                                </td>
                                <td class="px-4 py-2">
                                    <div
                                        class="font-medium text-gray-900 whitespace-nowrap"
                                    >
                                        {{ jewelry.category?.name }}
                                    </div>
                                </td>
                                <td class="px-4 py-2">
                                    <p class="w-fit max-w-56 truncate">
                                        {{ jewelry.weight }} Gram
                                    </p>
                                </td>
                                <td class="px-4 py-2">
                                    <p class="w-fit max-w-56 truncate">
                                        {{
                                            `${jewelry.price.carat} (${jewelry.price.rate}%)`
                                        }}
                                    </p>
                                </td>
                            </tr>
                        </Table>
                    </div>

                    <Pagination :links="jewelries.links" class="mt-5" />
                </div>
                <div class="w-full md:w-1/3">
                    <div class="border p-3 rounded sm:rounded-lg">
                        <h2 class="text-md font-medium text-gray-900">
                            Preview
                        </h2>
                        <hr class="my-3" />
                        <div
                            ref="printArea"
                            class="flex flex-col justify-start items-center gap-3 h-[600px] bg-zinc-100 p-3 overflow-y-auto no-scrollbar"
                            v-if="getSelected().length > 0"
                        >
                            <div
                                v-for="(jewelries, index) in getSelected()"
                                :key="index"
                                class="label-container"
                            >
                                <div v-for="jewelry in jewelries" class="label">
                                    <div class="label-barcode">
                                        <Barcode :value="jewelry.barcode" />
                                    </div>
                                    <div class="label-detail">
                                        <p v-text="jewelry.name" />
                                        <p v-text="`${jewelry.weight} Gr`" />
                                        <p v-text="jewelry.carat" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            v-else
                            class="flex justify-center items-center h-[600px] bg-zinc-100"
                        >
                            <div class="text-gray-500 text-center">
                                <p class="mb-1">Tidak ada data!</p>
                                <small>
                                    Pilih barang terlebih dahulu untuk
                                    <span class="underline">
                                        mencetak label
                                    </span>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-end items-center gap-3">
                <SecondaryButton @click="resetSelected">Reset</SecondaryButton>
                <PrimaryButton
                    @click="print"
                    :disabled="getSelected().length === 0"
                    >Cetak</PrimaryButton
                >
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
.label-container,
.label,
.label-barcode,
.label-detail {
    box-sizing: border-box;
}

.label-container {
    width: 77mm;
    height: 24mm;
    padding: 1mm;
    margin-bottom: 10px;
    display: flex;
    justify-content: space-between;
}

.label-container .label {
    width: 20mm;
    padding: 0.25mm;
    height: 100%;
    background: #fff;
}

.label-container .label .label-barcode {
    width: 100%;
    height: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    border-bottom: 1px dotted gray;
}

.label-container .label .label-detail {
    width: 100%;
    height: 50%;
    font-size: 8px !important;
    text-transform: uppercase;
    font-weight: 600;
    font-family: Arial, Helvetica, sans-serif;
    line-height: 10px !important;
    padding: 5px 10px;
}

.label-container .label .label-detail p {
    margin-bottom: 2px !important;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

@media print {
    body {
        visibility: hidden;
    }

    .print-area {
        visibility: visible;
        display: block;
        overflow: auto;
        height: fit-content;
        padding: 0;
        margin: 0;
        background: transparent;
    }
}
</style>
