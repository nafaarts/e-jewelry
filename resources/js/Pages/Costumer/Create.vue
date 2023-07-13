<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import FileInput from "@/Components/FileInput.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { useForm } from "@inertiajs/vue3";

import Swal from "sweetalert2";
import SwalConfig from "@/utils/sweetalert.conf";

const props = defineProps({
    costumer_code: String,
});

const form = useForm({
    costumer_code: props.costumer_code,
    name: "",
    indentity_number: "",
    phone_number: "",
    address: "",
    photo: "",
    remarks: "",
});

const onSubmit = () => {
    form.post(route("costumers.store"), {
        onSuccess: () => {
            Swal.fire({
                title: "Berhasil",
                icon: "success",
                text: "Kostumer berhasil ditambah!",
                ...SwalConfig,
            });
        },
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Tambah Kostumer" />

        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Tambah Kostumer
                </h2>
            </div>
        </template>

        <div class="bg-white overflow-hidden sm:rounded-lg border p-4 sm:p-8">
            <form @submit.prevent="onSubmit" class="space-y-6">
                <div>
                    <InputLabel for="costumer_code" value="Kode Costumer" />
                    <TextInput
                        id="costumer_code"
                        type="text"
                        class="mt-1 block w-full bg-zinc-100"
                        v-model="form.costumer_code"
                        readonly
                    />
                    <InputError
                        class="mt-2"
                        :message="form.errors.costumer_code"
                    />
                </div>

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
                            <InputLabel
                                for="indentity_number"
                                value="Nomor Indentitas"
                            />
                            <TextInput
                                id="indentity_number"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.indentity_number"
                                autocomplete="indentity_number"
                                placeholder="Masukan nomor indentitas"
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.indentity_number"
                            />
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-6">
                    <div class="w-full md:w-1/2 space-y-6">
                        <div>
                            <InputLabel
                                for="phone_number"
                                value="Nomor Handphone"
                            />
                            <TextInput
                                id="phone_number"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.phone_number"
                                autocomplete="phone_number"
                                placeholder="Masukan nomor telepon"
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.phone_number"
                            />
                        </div>
                    </div>

                    <div class="w-full md:w-1/2 space-y-6">
                        <div>
                            <InputLabel for="address" value="Alamat" />

                            <TextInput
                                id="address"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.address"
                                autocomplete="address"
                                placeholder="Masukan alamat"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.address"
                            />
                        </div>
                    </div>
                </div>

                <div>
                    <InputLabel for="photo" value="Photo" />

                    <FileInput
                        id="photo"
                        accept="image/*"
                        class="mt-1 block w-full"
                        @input="form.photo = $event.target.files[0]"
                    />

                    <progress
                        v-if="form.progress"
                        :value="form.progress.percentage"
                        max="100"
                    >
                        {{ form.progress.percentage }}%
                    </progress>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">
                        SVG, PNG, JPG or GIF (MAX. 800x400px).
                    </p>

                    <InputError class="mt-2" :message="form.errors.photo" />
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
                    <Link :href="route('costumers.index')">
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
