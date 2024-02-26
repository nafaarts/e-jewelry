<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import Select from "@/Components/Select.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    prices: Array,
});

const form = useForm({
    price: "all",
    from: new Date().toISOString().split("T")[0],
    to: new Date().toISOString().split("T")[0],
});

const submit = (e) => {
    e.preventDefault();
    form.get(route('report.result'));
};

</script>

<template>
    <AuthenticatedLayout>

        <Head title="Laporan" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Laporan
            </h2>
        </template>

        <div class="p-4 sm:p-8 bg-white border sm:rounded-lg">
            <header>
                <h2 class="text-lg font-medium text-gray-900">Laporan Barang Keluar</h2>

                <p class="mt-1 text-sm text-gray-600">
                    Cetak laporan barang keluar berdasarkan kategori dan tanggal.
                </p>
            </header>

            <hr class="my-6" />

            <form class="space-y-6" @submit="submit">
                <div class="w-full">
                    <InputLabel value="Kategori" />
                    <Select v-model="form.price">
                        <option value="" disabled>-- Pilih Kategori --</option>
                        <option v-for="price in prices" :value="price.id"
                            v-text="`${price.name} ${price.rate ? '(' + price.rate + '%)' : ''}`" />
                    </Select>
                    <InputError :message="form.errors.price" />
                </div>
                <div>
                    <div class="flex flex-col md:flex-row gap-6 mb-2">
                        <div class="w-full md:w-1/2">
                            <InputLabel value="Dari" />
                            <TextInput type="date" v-model="form.from" />
                            <InputError :message="form.errors.from" />
                        </div>
                        <div class="w-full md:w-1/2">
                            <InputLabel value="Sampai" />
                            <TextInput type="date" v-model="form.to" />
                            <InputError :message="form.errors.to" />
                        </div>
                    </div>
                </div>

                <PrimaryButton type="submit">Cetak</PrimaryButton>
            </form>
        </div>

    </AuthenticatedLayout>
</template>