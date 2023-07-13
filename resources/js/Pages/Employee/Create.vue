<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import Checkbox from "@/Components/Checkbox.vue";
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
    employee_code: String,
});

const form = useForm({
    user_code: props.employee_code,
    name: "",
    indentity_number: "",
    email: "",
    phone_number: "",
    password: "",
    password_confirmation: "",
    address: "",
    photo: "",
    role: "SALES",
    is_active: false,
    remarks: "",
});

const onSubmit = () => {
    form.post(route("employees.store"), {
        onSuccess: () => {
            Swal.fire({
                title: "Berhasil",
                icon: "success",
                text: "Karyawan berhasil ditambah!",
                ...SwalConfig,
            });
        },
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Tambah Karyawan" />

        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Tambah Karyawan
                </h2>
            </div>
        </template>

        <div class="bg-white overflow-hidden sm:rounded-lg border p-4 sm:p-8">
            <form @submit.prevent="onSubmit" class="space-y-6">
                <div>
                    <InputLabel for="user_code" value="Kode User" />
                    <TextInput
                        id="user_code"
                        type="text"
                        class="mt-1 block w-full bg-zinc-100"
                        v-model="form.user_code"
                        readonly
                    />
                    <InputError class="mt-2" :message="form.errors.user_code" />
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
                            <InputLabel for="email" value="Email" />
                            <TextInput
                                id="email"
                                type="email"
                                class="mt-1 block w-full"
                                v-model="form.email"
                                autocomplete="email"
                                placeholder="Masukan email"
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.email"
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
                            <InputLabel for="password" value="Password" />

                            <TextInput
                                id="password"
                                type="password"
                                class="mt-1 block w-full"
                                v-model="form.password"
                                placeholder="Masukan password"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.password"
                            />
                        </div>
                    </div>
                    <div class="w-full md:w-1/2 space-y-6">
                        <div>
                            <InputLabel
                                for="password_confirmation"
                                value="Ulangi Password"
                            />

                            <TextInput
                                id="password_confirmation"
                                type="password"
                                class="mt-1 block w-full"
                                v-model="form.password_confirmation"
                                placeholder="Ulangi password"
                            />
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-6">
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

                    <div class="w-full md:w-1/2 space-y-6">
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

                            <p
                                class="mt-1 text-sm text-gray-500 dark:text-gray-300"
                            >
                                SVG, PNG, JPG or GIF (MAX. 800x400px).
                            </p>

                            <InputError
                                class="mt-2"
                                :message="form.errors.photo"
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

                <div>
                    <InputLabel value="Status" />

                    <label for="is_active" class="flex items-center">
                        <Checkbox
                            id="is_active"
                            name="is_active"
                            v-model:checked="form.is_active"
                        />
                        <span class="ml-2 text-sm text-gray-600"> Aktif </span>
                    </label>

                    <InputError class="mt-2" :message="form.errors.is_active" />
                </div>

                <div class="flex items-center gap-2">
                    <PrimaryButton :disabled="form.processing">
                        Simpan
                    </PrimaryButton>
                    <Link :href="route('employees.index')">
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
