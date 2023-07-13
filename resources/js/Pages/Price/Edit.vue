<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import CurrencyInput from "@/Components/CurrencyInput.vue";
import TextInput from "@/Components/TextInput.vue";
import Select from "@/Components/Select.vue";
import TextareaInput from "@/Components/TextareaInput.vue";

import { useForm } from "@inertiajs/vue3";

import Swal from "sweetalert2";
import SwalConfig from "@/utils/sweetalert.conf";

const props = defineProps({
    price: Object,
});

const form = useForm({
    name: props.price.name || "",
    carat: props.price.carat || "",
    rate: props.price.rate || "",
    weight: (props.price.weight || "") + "",
    sell_price: (props.price.sell_price || "") + "",
    buy_price: (props.price.buy_price || "") + "",
    cost: (props.price.cost || "") + "",
    category: props.price.category || "",
    remarks: props.price.remarks || "",
});

const onSubmit = () => {
    form.put(route("prices.update", props.price.id), {
        onSuccess: () => {
            Swal.fire({
                title: "Berhasil",
                icon: "success",
                text: "Harga berhasil diubah!",
                ...SwalConfig,
            });
        },
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Ubah Harga" />

        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Ubah Harga
                </h2>
            </div>
        </template>

        <div class="bg-white overflow-hidden sm:rounded-lg border p-4 sm:p-8">
            <form @submit.prevent="onSubmit" class="space-y-6">
                <div class="flex flex-col md:flex-row gap-6">
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
                    <div class="w-full md:w-1/2 space-y-6">
                        <div>
                            <InputLabel for="weight" value="Berat" />
                            <TextInput
                                id="weight"
                                type="number"
                                step="0.000001"
                                class="mt-1 block w-full"
                                v-model="form.weight"
                                autocomplete="weight"
                                placeholder="Masukan berat"
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
                            <InputLabel for="carat" value="Karat" />
                            <TextInput
                                id="carat"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.carat"
                                autocomplete="carat"
                                placeholder="Masukan karat"
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.carat"
                            />
                        </div>
                    </div>
                    <div class="w-full md:w-1/2 space-y-6">
                        <div>
                            <InputLabel for="rate" value="Kadar" />
                            <TextInput
                                id="rate"
                                type="number"
                                step="0.01"
                                class="mt-1 block w-full"
                                v-model="form.rate"
                                autocomplete="rate"
                                placeholder="Masukan kadar"
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.rate"
                            />
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-6">
                    <div class="w-full md:w-1/2 space-y-6">
                        <div>
                            <InputLabel for="sell_price" value="Harga Jual" />
                            <CurrencyInput
                                id="sell_price"
                                class="mt-1 block w-full"
                                v-model="form.sell_price"
                                autocomplete="sell_price"
                                placeholder="Masukan harga jual"
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.sell_price"
                            />
                        </div>
                    </div>

                    <div class="w-full md:w-1/2 space-y-6">
                        <div>
                            <InputLabel for="buy_price" value="Harga Beli" />
                            <CurrencyInput
                                id="buy_price"
                                class="mt-1 block w-full"
                                v-model="form.buy_price"
                                autocomplete="buy_price"
                                placeholder="Masukan harga beli"
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.buy_price"
                            />
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-6">
                    <div class="w-full md:w-1/2 space-y-6">
                        <div>
                            <InputLabel for="cost" value="Harga Ongkos" />
                            <CurrencyInput
                                id="cost"
                                class="mt-1 block w-full"
                                v-model="form.cost"
                                autocomplete="cost"
                                placeholder="Masukan harga ongkos"
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.cost"
                            />
                        </div>
                    </div>
                    <div class="w-full md:w-1/2 space-y-6">
                        <div>
                            <InputLabel for="category" value="Category" />
                            <Select v-model="form.category">
                                <option value="">- Pilih category -</option>
                                <option value="MAYAM">MAYAM</option>
                                <option value="GRAM">GRAM</option>
                            </Select>
                            <InputError
                                class="mt-2"
                                :message="form.errors.category"
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
                    <InputError class="mt-2" :message="form.errors.remarks" />
                </div>

                <div class="flex items-center gap-2">
                    <PrimaryButton :disabled="form.processing">
                        Simpan
                    </PrimaryButton>
                    <Link :href="route('prices.index')">
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
