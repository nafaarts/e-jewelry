<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { useForm } from "@inertiajs/vue3";

import Swal from "sweetalert2";
import SwalConfig from "@/utils/sweetalert.conf";

const props = defineProps({
    category: Object,
});

const form = useForm({
    name: props.category.name || "",
    remarks: props.category.remarks || "",
});

const onSubmit = () => {
    form.put(route("categories.update", props.category.id), {
        onSuccess: () => {
            Swal.fire({
                title: "Berhasil",
                icon: "success",
                text: "Kategori berhasil di edit!",
                ...SwalConfig,
            });
        },
    });
};
</script>

<template>
    <AuthenticatedLayout>

        <Head title="Edit Kategori" />

        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit Kategori
                </h2>
            </div>
        </template>

        <div class="bg-white overflow-hidden sm:rounded-lg border p-4 sm:p-8">
            <form @submit.prevent="onSubmit" class="space-y-6">
                <div>
                    <InputLabel for="name" value="Nama" />
                    <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" autocomplete="name"
                        placeholder="Masukan nama" autofocus />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div>
                    <InputLabel for="remarks" value="Catatan" />

                    <TextareaInput id="remarks" name="remarks" v-model="form.remarks"
                        placeholder="Tinggalkan catatan..." />
                    <InputError class="mt-2" :message="form.errors.remarks" />
                </div>

                <div class="flex items-center gap-2">
                    <PrimaryButton :disabled="form.processing">
                        Simpan
                    </PrimaryButton>
                    <Link :href="route('categories.index')">
                    <SecondaryButton type="reset" :disabled="form.processing">
                        Kembali
                    </SecondaryButton>
                    </Link>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
