<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import CurrencyInput from "@/Components/CurrencyInput.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Select from "@/Components/Select.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import FileInput from "@/Components/FileInput.vue";
import { useForm } from "@inertiajs/vue3";
import { reactive, ref, watch } from "vue";

import { currencyFormatter } from "@/utils/currencyFormatter";

import Swal from "sweetalert2";
import SwalConfig from "@/utils/sweetalert.conf";

const props = defineProps({
    prices: Array,
    categories: Array,
    suppliers: Array,
    safeboxes: Array,
    jewelry: Object,
});

const totalPrice = ref(currencyFormatter.format(props.jewelry.sell_price));

const form = useForm({
    _method: "PUT",
    price_id: props.jewelry.price_id + "" || "",
    category_id: props.jewelry.category_id + "" || "",
    supplier_id: props.jewelry.supplier_id + "" || "",
    safe_box_id: props.jewelry.safe_box_id + "" || "",
    name: props.jewelry.name || "",
    jewelry_code: props.jewelry.jewelry_code || "",
    weight: props.jewelry.weight || "",
    cost: props.jewelry.cost || "",
    photo: null,
    status: props.jewelry.status || "",
    remarks: props.jewelry.remarks || "",
});

const priceReactive = reactive({
    price: props.jewelry.price_id + "" || "",
    weight: props.jewelry.weight + "" || "",
    cost: props.jewelry.cost + "" || "",
});

const onSubmit = () => {
    form.post(route("jewelries.update", props.jewelry.id), {
        onSuccess: () => {
            Swal.fire({
                title: "Berhasil",
                icon: "success",
                text: "Barang berhasil diedit!",
                ...SwalConfig,
            });
        },
    });
};

const imagePreview = ref(
    props.jewelry.photo
        ? `/storage/${props.jewelry.photo}`
        : "/images/image-placeholder.png"
);
const onUpdateImage = (e) => {
    const file = e.target.files[0];
    form.photo = file;
    imagePreview.value = URL.createObjectURL(file);
};

watch(priceReactive, ({ price, weight, cost }) => {
    form.price_id = price;
    form.weight = weight;
    form.cost = cost;

    const selectedPrice = props.prices.filter((item) => item.id == price)[0];
    let getPrice =
        (weight / selectedPrice.weight) *
        (selectedPrice.sell_price + selectedPrice.cost);
    if (cost) getPrice += parseInt(cost.replace(",", ""));
    const roundedPrice = Math.floor(getPrice / 1000) * 1000;
    totalPrice.value = currencyFormatter.format(roundedPrice);
});
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Edit Barang" />

        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit Barang
                </h2>
            </div>
        </template>

        <div class="bg-white overflow-hidden sm:rounded-lg border p-4 sm:p-8">
            <form @submit.prevent="onSubmit" class="space-y-6">
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="w-full md:w-3/4 space-y-6">
                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="w-full md:w-1/2 space-y-6">
                                <div>
                                    <InputLabel
                                        for="jewelry_code"
                                        value="Kode Barang"
                                    />
                                    <TextInput
                                        id="jewelry_code"
                                        type="text"
                                        class="mt-1 block w-full bg-zinc-100"
                                        v-model="form.jewelry_code"
                                        readonly
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.jewelry_code"
                                    />
                                </div>
                            </div>
                            <div class="w-full md:w-1/2 space-y-6">
                                <div>
                                    <InputLabel for="name" value="Nama" />
                                    <TextInput
                                        id="name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.name"
                                        autocomplete="name"
                                        placeholder="Masukan nama"
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.name"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="w-full md:w-1/2 space-y-6">
                                <div>
                                    <InputLabel
                                        for="price_id"
                                        value="Kadar dan Harga"
                                    />
                                    <Select v-model="priceReactive.price">
                                        <option value="">
                                            - Pilih Kadar dan Harga -
                                        </option>
                                        <option
                                            v-for="price in prices"
                                            :key="price.id"
                                            :value="price.id"
                                        >
                                            {{
                                                `${price.category} - ${price.carat} (${price.rate}%) | ${price.name}`
                                            }}
                                        </option>
                                    </Select>
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.price_id"
                                    />
                                </div>
                            </div>
                            <div class="w-full md:w-1/2 space-y-6">
                                <div>
                                    <InputLabel
                                        for="weight"
                                        value="Berat (gram)"
                                    />
                                    <TextInput
                                        id="weight"
                                        type="number"
                                        class="mt-1 block w-full"
                                        v-model="priceReactive.weight"
                                        autocomplete="weight"
                                        placeholder="Masukan berat"
                                        step="0.000001"
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.weight"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="w-full md:w-1/2 space-y-6">
                                <div>
                                    <InputLabel for="cost" value="Ongkos" />
                                    <CurrencyInput
                                        id="cost"
                                        class="mt-1 block w-full"
                                        v-model="priceReactive.cost"
                                        autocomplete="cost"
                                        placeholder="Masukan ongkos (jika ada)"
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.cost"
                                    />
                                </div>
                            </div>
                            <div class="w-full md:w-1/2 space-y-6">
                                <div>
                                    <InputLabel
                                        for="totalPrice"
                                        value="Harga"
                                    />
                                    <TextInput
                                        id="totalPrice"
                                        class="mt-1 block w-full bg-zinc-100"
                                        v-model="totalPrice"
                                        readonly
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="w-full md:w-1/2 space-y-6">
                                <div>
                                    <InputLabel
                                        for="supplier_id"
                                        value="Supplier"
                                    />
                                    <Select v-model="form.supplier_id">
                                        <option value="">
                                            - Pilih supplier -
                                        </option>
                                        <option
                                            v-for="supplier in suppliers"
                                            :key="supplier.id"
                                            :value="supplier.id"
                                        >
                                            {{ supplier.name }}
                                        </option>
                                    </Select>
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.supplier_id"
                                    />
                                </div>
                            </div>
                            <div class="w-full md:w-1/2 space-y-6">
                                <div>
                                    <InputLabel
                                        for="safe_box_id"
                                        value="Brankas"
                                    />
                                    <Select v-model="form.safe_box_id">
                                        <option value="">
                                            - Pilih brankas -
                                        </option>
                                        <option
                                            v-for="safebox in safeboxes"
                                            :key="safebox.id"
                                            :value="safebox.id"
                                        >
                                            {{ safebox.name }}
                                        </option>
                                    </Select>
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.safe_box_id"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="w-full md:w-1/2 space-y-6">
                                <div>
                                    <InputLabel for="status" value="Status" />
                                    <Select v-model="form.status">
                                        <option value="">
                                            - Pilih status -
                                        </option>
                                        <option value="READY">TERSEDIA</option>
                                        <option value="SOLD">TERJUAL</option>
                                    </Select>
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.status"
                                    />
                                </div>
                            </div>
                            <div class="w-full md:w-1/2 space-y-6">
                                <div>
                                    <InputLabel
                                        for="category_id"
                                        value="Kategori"
                                    />
                                    <Select v-model="form.category_id">
                                        <option value="">
                                            - Pilih kategori -
                                        </option>
                                        <option
                                            v-for="category in categories"
                                            :key="category.id"
                                            :value="category.id"
                                        >
                                            {{ category.name }}
                                        </option>
                                    </Select>
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.category_id"
                                    />
                                </div>
                            </div>
                        </div>

                        <div>
                            <InputLabel for="remarks" value="Catatan" />

                            <TextareaInput
                                id="remarks"
                                name="remarks"
                                v-model="form.remarks"
                                placeholder="Tinggalkan catatan..."
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.remarks"
                            />
                        </div>
                    </div>

                    <div class="w-full md:w-1/4">
                        <InputLabel for="photo" value="Photo" />

                        <label for="photo" class="block mb-3">
                            <div class="w-full p-1 border rounded-md">
                                <img
                                    :src="imagePreview"
                                    alt="preview"
                                    class="w-full"
                                />
                            </div>
                        </label>

                        <FileInput
                            id="photo"
                            accept="image/*"
                            class="mt-1 block w-full"
                            @input="onUpdateImage"
                        />

                        <progress
                            v-if="form.progress"
                            :value="form.progress.percentage"
                            max="100"
                        >
                            {{ form.progress.percentage }}%
                        </progress>

                        <p
                            class="mt-1 text-sm text-gray-500 dark:text-gray-300"
                        >
                            SVG, PNG, JPG or GIF (MAX. 5MB).
                        </p>

                        <InputError class="mt-2" :message="form.errors.photo" />
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <PrimaryButton :disabled="form.processing">
                        Simpan
                    </PrimaryButton>
                    <Link :href="route('jewelries.index')">
                        <SecondaryButton
                            type="reset"
                            :disabled="form.processing"
                        >
                            Kembali
                        </SecondaryButton>
                    </Link>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
