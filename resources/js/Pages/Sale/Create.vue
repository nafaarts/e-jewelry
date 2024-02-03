<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SelectCostumer from "@/Components/SelectCostumer.vue";
import { useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import SwalConfig from "@/utils/sweetalert.conf";
import { ref, watch } from "vue";
import { currencyFormatter } from "@/utils/currencyFormatter";
import Table from "@/Components/Table.vue";
import ImageCover from "@/Components/ImageCover.vue";
import axiosInstance from "@/utils/AxiosInstance";

const props = defineProps({
    sales: String,
    order: Object
})

const backRoute = ref(props.order ? route('orders.show', props.order?.id) : route('sales.index'))

const sales = ref(props.sales)
const costumer = ref({})
const jewelry_code = ref("")

const form = useForm({
    costumer_id: "",
    total_amount: "",
    remarks: "",
    items: []
});

const addItem = (data) => {
    let check = form.items.find(o => o.jewelry_code === data.jewelry_code);
    if (!check) {
        form.items.push(data);
        form.total_amount = form.items.reduce((total, item) => total + item.sell_price, 0);

        jewelry_code.value = "";
    } else {
        Swal.fire({
            title: "Opps",
            icon: "error",
            text: "Barang sudah di input!",
            ...SwalConfig,
        });
    }
}

const onAddItem = () => {
    axiosInstance.get(`/api/jewelry?code=${jewelry_code.value}`)
        .then(response => {
            console.log(response);
            addItem(response.data.data)
        })
        .catch(error => {
            console.log(error);

            if (error?.response.status === 404) {
                Swal.fire({
                    title: "Opps",
                    icon: "error",
                    text: "Barang tidak ditemukan!",
                    ...SwalConfig,
                });
            }
        })
}

const onDeleteItem = (jewelry_code) => {
    form.items = form.items.filter(item => item.jewelry_code !== jewelry_code)
    form.total_amount = form.items.reduce((total, item) => total + item.sell_price, 0);
}

const onSubmit = () => {
    if (form.items.length !== 0) {
        Swal.fire({
            title: "Konfirmasi",
            html: `<span>Simpan data penjualan?</span>`,
            showCancelButton: true,
            cancelButtonText: "Tidak",
            confirmButtonText: "Ya",
            ...SwalConfig,
        }).then((result) => {
            if (result.isConfirmed) {
                form.post(route("sales.store"), {
                    onSuccess: () => {
                        Swal.fire({
                            title: "Berhasil",
                            icon: "success",
                            text: "Penjualan berhasil ditambah!",
                            ...SwalConfig,
                        });
                    },
                });
            }
        });
    } else {
        Swal.fire({
            title: "Opps",
            icon: "error",
            text: "Item tidak boleh kosong!",
            ...SwalConfig,
        });
    }
};

// add item if sale coming from order.
if (props.order) {
    addItem({
        id: props.order.jewelry.id,
        jewelry_code: props.order.jewelry.jewelry_code,
        name: props.order.jewelry.name,
        weight: props.order.jewelry.weight,
        price: {
            category: props.order.jewelry.price.category,
            carat: props.order.jewelry.price.carat,
            rate: props.order.jewelry.price.rate,
        },
        sell_price: props.order.total_price,
        photo: props.order.jewelry.photo,
    })

    costumer.value = props.order.costumer
    form.costumer_id = props.order.costumer.id
}

watch(costumer, (value) => {
    form.costumer_id = value.id
})
</script>

<template>
    <AuthenticatedLayout>

        <Head title="Tambah Penjualan" />

        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Tambah Penjualan
                </h2>
            </div>
        </template>

        <div class="flex flex-col-reverse md:flex-row gap-6 mb-6">
            <div class="w-full md:w-3/4">
                <div class="bg-white sm:rounded-lg border p-4 sm:p-8">
                    <div class="space-y-6 mb-8">
                        <form @submit.prevent="onAddItem">
                            <InputLabel for="jewelry_code" value="Kode Barang" />
                            <TextInput id="jewelry_code" type="text" class="mt-1 block w-full" v-model="jewelry_code"
                                autocomplete="jewelry_code" placeholder="Masukan kode barang" />
                        </form>

                        <div
                            class="flex flex-col min-h-[300px] md:min-h-[500px] border border-gray-300 rounded-lg p-2 space-y-1">
                            <div v-if="form.items.length != 0"
                                class="overflow-hidden sm:rounded-lg max-h-[580px] overflow-y-auto">
                                <Table>
                                    <template #head>
                                        <tr>
                                            <th scope="col" class="px-4 py-3 whitespace-nowrap">
                                                Nama
                                            </th>
                                            <th scope="col" class="px-4 py-3 whitespace-nowrap">
                                                Berat
                                            </th>
                                            <th scope="col" class="px-4 py-3 whitespace-nowrap">
                                                Kadar
                                            </th>
                                            <th scope="col" class="px-4 py-3 whitespace-nowrap">
                                                Harga
                                            </th>
                                            <th scope="col" class="px-4 py-3 whitespace-nowrap">
                                                Aksi
                                            </th>
                                        </tr>
                                    </template>

                                    <tr v-for="jewelry in form.items" :key="jewelry.id" class="border-b">
                                        <td class="px-4 py-2">
                                            <div class="flex items-center">
                                                <ImageCover class="w-10 h-10 rounded-full bg-zinc-300" :src="jewelry.photo
                                                    ? '/storage/' + jewelry.photo
                                                    : '/images/image-placeholder.png'
                                                    " />
                                                <div class="pl-3">
                                                    <div class="font-medium text-gray-900 whitespace-nowrap">
                                                        {{ jewelry.name }}
                                                    </div>
                                                    <div class="font-normal text-gray-500">
                                                        {{ jewelry.jewelry_code }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-2">
                                            <p class="w-fit max-w-56 truncate">
                                                {{ jewelry.weight }} Gram
                                            </p>
                                        </td>
                                        <td class="px-4 py-2">
                                            <p class="w-fit max-w-56 truncate">
                                                {{ `${jewelry.price.category} - ${jewelry.price.carat}
                                                                                                (${jewelry.price.rate}%)` }}
                                            </p>
                                        </td>
                                        <td class="px-4 py-2">
                                            <div class="font-medium text-gray-900 whitespace-nowrap">
                                                {{ currencyFormatter.format(jewelry.sell_price) }}
                                            </div>
                                        </td>

                                        <td class="px-4 py-2">
                                            <button @click="onDeleteItem(jewelry.jewelry_code)"
                                                class="p-1 transition bg-red-600 hover:bg-red-700 text-white rounded">
                                                <i class="fas fa-fw fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </Table>
                            </div>

                            <div v-else class="flex-1 flex justify-center items-center">
                                <p class="text-gray-600">Tidak ada data!</p>
                            </div>
                        </div>

                        <div>
                            <p class="text-2xl text-gray-600">
                                Total: <strong>{{ currencyFormatter.format(form.total_amount) }}</strong>
                            </p>
                        </div>
                    </div>

                    <form @submit.prevent="onSubmit">
                        <div class="flex items-center gap-2">
                            <PrimaryButton :disabled="form.processing">
                                Simpan
                            </PrimaryButton>
                            <Link :href="backRoute">
                            <SecondaryButton type="reset" :disabled="form.processing">
                                Kembali
                            </SecondaryButton>
                            </Link>
                        </div>
                    </form>
                </div>
            </div>

            <div class="w-full md:w-1/4">
                <div class="bg-white sm:rounded-lg border p-4 sm:p-8 space-y-6">
                    <div>
                        <InputLabel for="pramuniaga" value="Pramuniaga" />
                        <TextInput id="pramuniaga" class="mt-1 block w-full bg-gray-200" v-model="sales" disabled />
                    </div>
                    <div>
                        <SelectCostumer v-model="costumer" />
                        <InputError class="mt-2" :message="form.errors.costumer_id" />
                    </div>
                    <div>
                        <InputLabel for="remarks" value="Catatan" />
                        <TextareaInput id="remarks" name="remarks" rows="6" v-model="form.remarks"
                            placeholder="Tinggalkan catatan..." />
                        <InputError class="mt-2" :message="form.errors.remarks" />
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
