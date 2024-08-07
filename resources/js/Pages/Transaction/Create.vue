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
import { ref } from "vue";
import CurrencyInput from "@/Components/CurrencyInput.vue";
import { currencyFormatter } from "@/utils/currencyFormatter";

const props = defineProps({
    account: Object,
    sales: String,
});

const sales = ref(props.sales);
const account_number = ref(props.account?.account_number);
const customer = ref(props.account?.costumer?.name);

const form = useForm({
    type: "CREDIT",
    category: "MONEY",
    weight: "",
    amount: "",
    cost: "",
    remarks: "",
});

const onSubmit = () => {
    form.post(route("deposits.transactions.store", props.account), {
        onSuccess: () => {
            Swal.fire({
                title: "Berhasil",
                icon: "success",
                text: "Transaksi berhasil ditambahkan!",
                ...SwalConfig,
            });
        },
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Buat Transaksi" />

        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Buat Transaksi
                </h2>
            </div>
        </template>

        <form @submit.prevent="onSubmit">
            <div
                class="bg-white overflow-hidden sm:rounded-lg border p-4 sm:p-8 space-y-6"
            >
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="w-full md:w-1/2">
                        <InputLabel for="account_number" value="Kode Akun" />
                        <TextInput
                            id="account_number"
                            class="mt-1 block w-full bg-gray-200"
                            v-model="account_number"
                            disabled
                        />
                    </div>

                    <div class="w-full md:w-1/2">
                        <InputLabel for="kastamer" value="Kastamer" />
                        <TextInput
                            id="kastamer"
                            class="mt-1 block w-full bg-gray-200"
                            v-model="customer"
                            disabled
                        />
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-6">
                    <div class="w-full md:w-1/2">
                        <InputLabel for="category" value="Kategori" />

                        <div class="flex gap-3">
                            <label
                                class="w-full text-center border border-gray-300 py-2 px-4 cursor-pointer rounded text-xs"
                                :class="{
                                    'border-orange-500 bg-orange-50 text-orange-600':
                                        form.category === 'MONEY',
                                }"
                            >
                                <input
                                    type="radio"
                                    v-model="form.category"
                                    value="MONEY"
                                    class="hidden"
                                />
                                UANG
                            </label>

                            <label
                                class="w-full text-center border border-gray-300 py-2 px-4 cursor-pointer rounded text-xs"
                                :class="{
                                    'border-orange-500 bg-orange-50 text-orange-600':
                                        form.category === 'GOLD',
                                }"
                            >
                                <input
                                    type="radio"
                                    v-model="form.category"
                                    value="GOLD"
                                    class="hidden"
                                />
                                EMAS
                            </label>
                        </div>

                        <InputError
                            class="mt-2"
                            :message="form.errors.category"
                        />
                    </div>

                    <div class="w-full md:w-1/2">
                        <InputLabel for="type" value="Jenis" />

                        <div class="flex gap-3">
                            <label
                                class="w-full text-center border border-gray-300 py-2 px-4 cursor-pointer rounded text-xs"
                                :class="{
                                    'border-orange-500 bg-orange-50 text-orange-600':
                                        form.type === 'CREDIT',
                                }"
                            >
                                <input
                                    type="radio"
                                    v-model="form.type"
                                    value="CREDIT"
                                    class="hidden"
                                />
                                TITIP
                            </label>

                            <label
                                class="w-full text-center border border-gray-300 py-2 px-4 cursor-pointer rounded text-xs"
                                :class="{
                                    'border-orange-500 bg-orange-50 text-orange-600':
                                        form.type === 'DEBIT',
                                }"
                            >
                                <input
                                    type="radio"
                                    v-model="form.type"
                                    value="DEBIT"
                                    class="hidden"
                                />
                                AMBIL
                            </label>
                        </div>

                        <InputError class="mt-2" :message="form.errors.type" />
                    </div>
                </div>

                <div v-if="form.category === 'GOLD'">
                    <InputLabel for="weight" value="Berat (Gram)" />
                    <TextInput
                        id="weight"
                        type="number"
                        class="mt-1 block w-full"
                        v-model="form.weight"
                        step="0.000001"
                        :placeholder="
                            'Masukan Berat' +
                            (form.type === 'DEBIT'
                                ? ` (Max. ${account.gold_balance} Gr)`
                                : '')
                        "
                    />
                    <InputError class="mt-2" :message="form.errors.weight" />
                </div>

                <div v-else>
                    <InputLabel for="amount" value="Jumlah (Rp)" />
                    <CurrencyInput
                        id="amount"
                        class="mt-1 block w-full"
                        v-model="form.amount"
                        :placeholder="
                            'Masukan Jumlah' +
                            (form.type === 'DEBIT'
                                ? ` (Max. ${currencyFormatter.format(
                                      account.money_balance
                                  )})`
                                : '')
                        "
                    />
                    <InputError class="mt-2" :message="form.errors.amount" />
                </div>

                <div class="flex flex-col md:flex-row gap-6">
                    <div class="w-full md:w-1/2">
                        <InputLabel for="cost" value="Ongkos" />
                        <CurrencyInput
                            id="cost"
                            class="mt-1 block w-full"
                            v-model="form.cost"
                            placeholder="Masukan Ongkos (jika ada)"
                        />
                        <InputError class="mt-2" :message="form.errors.cost" />
                    </div>

                    <div class="w-full md:w-1/2">
                        <InputLabel for="sales" value="Pramuniaga" />
                        <TextInput
                            id="sales"
                            class="mt-1 block w-full bg-gray-200"
                            v-model="sales"
                            disabled
                        />
                    </div>
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
                    <InputError class="mt-2" :message="form.errors.remarks" />
                </div>

                <div class="flex items-center gap-2">
                    <PrimaryButton :disabled="form.processing">
                        Simpan
                    </PrimaryButton>
                    <Link :href="route('deposits.show', account)">
                        <SecondaryButton
                            type="reset"
                            :disabled="form.processing"
                        >
                            Kembali
                        </SecondaryButton>
                    </Link>
                </div>
            </div>
        </form>
    </AuthenticatedLayout>
</template>
