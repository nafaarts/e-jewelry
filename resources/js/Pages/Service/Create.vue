<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import CurrencyInput from "@/Components/CurrencyInput.vue";
import SelectCostumer from "@/Components/SelectCostumer.vue";
import Select from "@/Components/Select.vue";
import { useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import SwalConfig from "@/utils/sweetalert.conf";
import { ref, watch } from "vue";
import status from "@/Constant/ServiceStatus";

const props = defineProps({
    sales: String,
    categories: Array,
});

const sales = ref(props.sales);
const costumer = ref({});
const form = useForm({
    category_id: "",
    costumer_id: "",
    weight: "",
    cost: "",
    paid_amount: "",
    status: "PESANAN DIBUAT",
    estimated_date: "",
    remarks: "",
});

const onSubmit = () => {
    form.post(route("services.store"), {
        onSuccess: () => {
            Swal.fire({
                title: "Berhasil",
                icon: "success",
                text: "Perbaikan berhasil ditambah!",
                ...SwalConfig,
            });
        },
    });
};

watch(costumer, (value) => (form.costumer_id = value.id));
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Tambah Perbaikan" />

        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Tambah Perbaikan
                </h2>
            </div>
        </template>

        <form @submit.prevent="onSubmit" class="space-y-6">
            <div class="flex flex-col-reverse md:flex-row gap-6">
                <div class="w-full md:w-3/4">
                    <div
                        class="bg-white overflow-hidden sm:rounded-lg border p-4 sm:p-8 space-y-6"
                    >
                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="w-full md:w-1/2 space-y-6">
                                <div>
                                    <InputLabel for="status" value="Status" />
                                    <Select v-model="form.status">
                                        <option value="">
                                            - Pilih status -
                                        </option>
                                        <option
                                            v-for="(item, index) in status"
                                            :key="index"
                                            :value="item"
                                        >
                                            {{ item }}
                                        </option>
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

                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="w-full md:w-1/2 space-y-6">
                                <div>
                                    <InputLabel
                                        for="weight"
                                        value="Berat (gram)"
                                    />
                                    <TextInput
                                        id="weight"
                                        type="number"
                                        step="0.01"
                                        class="mt-1 block w-full"
                                        v-model="form.weight"
                                        autocomplete="weight"
                                        placeholder="Masukan berat perhiasan"
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.weight"
                                    />
                                </div>
                            </div>
                            <div class="w-full md:w-1/2 space-y-6">
                                <div>
                                    <InputLabel for="cost" value="Biaya" />
                                    <CurrencyInput
                                        id="cost"
                                        class="mt-1 block w-full"
                                        v-model="form.cost"
                                        autocomplete="cost"
                                        placeholder="Masukan total biaya"
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.cost"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="w-full md:w-1/2 space-y-6">
                                <div>
                                    <InputLabel
                                        for="paid_amount"
                                        value="Jumlah Dibayarkan"
                                    />
                                    <CurrencyInput
                                        id="paid_amount"
                                        type="paid_amount"
                                        class="mt-1 block w-full"
                                        v-model="form.paid_amount"
                                        autocomplete="paid_amount"
                                        placeholder="Masukan jumlah dibayarkan"
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.paid_amount"
                                    />
                                </div>
                            </div>

                            <div class="w-full md:w-1/2 space-y-6">
                                <div>
                                    <InputLabel
                                        for="estimated_date"
                                        value="Estimasi Tanggal Selesai"
                                    />
                                    <TextInput
                                        id="estimated_date"
                                        type="date"
                                        class="mt-1 block w-full"
                                        v-model="form.estimated_date"
                                        autocomplete="estimated_date"
                                        placeholder="Masukan Estimasi Tanggal Selesai"
                                    />
                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.estimated_date"
                                    />
                                </div>
                            </div>
                        </div>

                        <div>
                            <InputLabel for="remarks" value="Catatan" />
                            <TextareaInput
                                id="remarks"
                                name="remarks"
                                rows="6"
                                v-model="form.remarks"
                                placeholder="Tinggalkan catatan..."
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.remarks"
                            />
                        </div>

                        <div class="flex items-center gap-2">
                            <PrimaryButton :disabled="form.processing">
                                Simpan
                            </PrimaryButton>
                            <Link :href="route('services.index')">
                                <SecondaryButton
                                    type="reset"
                                    :disabled="form.processing"
                                >
                                    Kembali
                                </SecondaryButton>
                            </Link>
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-1/4">
                    <div
                        class="bg-white overflow-hidden sm:rounded-lg border p-4 sm:p-8 space-y-6"
                    >
                        <div>
                            <InputLabel for="pramuniaga" value="Pramuniaga" />
                            <TextInput
                                id="pramuniaga"
                                class="mt-1 block w-full bg-gray-200"
                                v-model="sales"
                                disabled
                            />
                        </div>
                        <div>
                            <SelectCostumer v-model="costumer" />
                            <InputError
                                class="mt-2"
                                :message="form.errors.costumer_id"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </AuthenticatedLayout>
</template>
