<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import Checkbox from "@/Components/Checkbox.vue";
import TextareaInput from "@/Components/TextareaInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import SelectCostumer from "@/Components/SelectCostumer.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { useForm } from "@inertiajs/vue3";
import Swal from "sweetalert2";
import SwalConfig from "@/utils/sweetalert.conf";
import { ref, watch } from "vue";

const props = defineProps({
    sales: String,
    account_number: String,
});

const sales = ref(props.sales);
const account_number = ref(props.account_number);

const costumer = ref({});
const form = useForm({
    costumer_id: "",
    is_active: true,
    remarks: "",
});

const onSubmit = () => {
    form.post(route("deposits.store"), {
        onSuccess: () => {
            Swal.fire({
                title: "Berhasil",
                icon: "success",
                text: "Akun Titipan berhasil ditambah!",
                ...SwalConfig,
            });
        },
    });
};

watch(costumer, (value) => (form.costumer_id = value.id));
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Tambah Akun Titipan" />

        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Tambah Akun Titipan
                </h2>
            </div>
        </template>

        <form @submit.prevent="onSubmit" class="space-y-6">
            <div class="flex flex-col-reverse md:flex-row gap-6">
                <div class="w-full md:w-3/4">
                    <div
                        class="bg-white overflow-hidden sm:rounded-lg border p-4 sm:p-8 space-y-6"
                    >
                        <div>
                            <InputLabel
                                for="account_number"
                                value="Kode Akun"
                            />
                            <TextInput
                                id="account_number"
                                class="mt-1 block w-full bg-gray-200"
                                v-model="account_number"
                                disabled
                            />
                        </div>

                        <div>
                            <InputLabel for="remarks" value="Catatan" />
                            <TextareaInput
                                id="remarks"
                                name="remarks"
                                rows="12"
                                v-model="form.remarks"
                                placeholder="Tinggalkan catatan..."
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.remarks"
                            />
                        </div>

                        <div>
                            <InputLabel for="is_active" value="Status" />

                            <label
                                for="is_active"
                                class="flex items-center w-fit"
                            >
                                <Checkbox
                                    id="is_active"
                                    name="is_active"
                                    v-model:checked="form.is_active"
                                />
                                <span class="ml-2 text-sm text-gray-600">
                                    Aktif
                                </span>
                            </label>

                            <InputError
                                class="mt-2"
                                :message="form.errors.is_active"
                            />
                        </div>

                        <div class="flex items-center gap-2">
                            <PrimaryButton :disabled="form.processing">
                                Simpan
                            </PrimaryButton>
                            <Link :href="route('deposits.index')">
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
