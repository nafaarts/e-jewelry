<script setup>
import axiosInstance from "../utils/AxiosInstance";

import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { reactive, ref } from "vue";

const emit = defineEmits(["onSuccess", "onCancel"]);
const newUser = reactive({
    name: "",
    phone_number: "",
    address: ""
});
const errors = ref({})
const loading = ref(false)

const addNewUser = () => {
    loading.value = true;
    axiosInstance.post('/api/costumer/store', newUser)
        .then(response => {
            loading.value = false;
            emit('onSuccess', response.data);
        })
        .catch(error => {
            loading.value = false;
            if (error?.response) {
                errors.value = {
                    name: error.response.data.errors?.name[0],
                    phone_number: error.response.data.errors?.phone_number[0],
                    address: error.response.data.errors?.address[0]
                }
            }
        });
}

const resetForm = () => {
    newUser.name = "";
    newUser.phone_number = "";
    newUser.address = "";
}
</script>

<template>
    <div class="border border-gray-300 p-4 rounded space-y-3">
        <div class="w-full">
            <span class="block mb-2 text-sm text-gray-500">Nama :</span>
            <TextInput type="text" placeholder="Masukan nama" v-model="newUser.name" />
            <InputError class="mt-2" :message="errors?.name" />
        </div>

        <div class="w-full">
            <span class="block mb-2 text-sm text-gray-500">No Telp :</span>
            <TextInput type="text" placeholder="Masukan nomor telepon" v-model="newUser.phone_number" />
            <InputError class="mt-2" :message="errors?.phone_number" />
        </div>

        <div class="w-full">
            <span class="block mb-2 text-sm text-gray-500">Address :</span>
            <TextInput type="text" placeholder="Masukan alamat" v-model="newUser.address" />
            <InputError class="mt-2" :message="errors?.address" />
        </div>
        <hr>
        <div class="flex gap-2">
            <PrimaryButton type="button" @click="addNewUser" :disabled="loading">
                <i class="fas fa-fw fa-check"></i>
            </PrimaryButton>
            <SecondaryButton type="button" @click="() => {
                resetForm()
                $emit('onCancel')
            }">
                <i class="fas fw-fw fa-times"></i>
            </SecondaryButton>
        </div>
    </div>
</template>