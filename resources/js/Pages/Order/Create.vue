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
import { ref, watch, reactive } from "vue";
import status from "@/Constant/OrderStatus";
import { currencyFormatter } from "@/utils/currencyFormatter";

const props = defineProps({
    sales: String,
    prices: Array,
    order_code: String,
})

const sales = ref(props.sales)
const costumer = ref({})
const form = useForm({
    costumer_id: "",
    price_id: "",
    weight: "",
    cost: "",
    price: "",
    total_price: "0",
    paid_amount: "",
    status: "PESANAN DIBUAT",
    remarks: ""
});

const onSubmit = () => {
    form.post(route("orders.store"), {
        onSuccess: () => {
            Swal.fire({
                title: "Berhasil",
                icon: "success",
                text: "Tempahan berhasil ditambah!",
                ...SwalConfig,
            });
        },
    });
};

const priceReactive = reactive({
    price: "",
    weight: "",
    cost: "",
});

watch(priceReactive, ({ price, weight, cost }) => {
    form.price_id = price;
    form.weight = weight;
    form.cost = cost;

    if (price) {
        const selectedPrice = props.prices.filter((item) => item.id == price)[0];
        let getPrice =
            (weight / selectedPrice.weight) *
            (selectedPrice.sell_price + selectedPrice.cost);
        if (cost) getPrice += parseInt(cost.replace(",", ""));

        const roundedPrice = Math.floor(getPrice / 1000) * 1000;
        form.total_price = currencyFormatter.format(roundedPrice);
    }
});

watch(costumer, (value) => form.costumer_id = value.id)
</script>

<template>
    <AuthenticatedLayout>

        <Head title="Tambah Tempahan" />

        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Tambah Tempahan
                </h2>
            </div>
        </template>

        <form @submit.prevent="onSubmit" class="space-y-6">
            <div class="flex flex-col-reverse md:flex-row gap-6">
                <div class="w-full md:w-3/4">
                    <div class="bg-white overflow-hidden sm:rounded-lg border p-4 sm:p-8 space-y-6">
                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="w-full md:w-1/2 space-y-6">
                                <div>
                                    <InputLabel for="status" value="Status" />
                                    <Select v-model="form.status">
                                        <option value="">
                                            - Pilih status -
                                        </option>
                                        <option v-for="(item, index) in status" :key="index" :value="item">
                                            {{ item }}
                                        </option>
                                    </Select>
                                    <InputError class="mt-2" :message="form.errors.status" />
                                </div>
                            </div>
                            <div class="w-full md:w-1/2 space-y-6">
                                <div>
                                    <InputLabel for="price_id" value="Kadar dan Harga" />
                                    <Select v-model="priceReactive.price">
                                        <option value="">
                                            - Pilih Kadar dan Harga -
                                        </option>
                                        <option v-for="price in prices" :key="price.id" :value="price.id">
                                            {{
                                                `${price.category} - ${price.carat} (${price.rate}%) | ${price.name}`
                                            }}
                                        </option>
                                    </Select>
                                    <InputError class="mt-2" :message="form.errors.price_id" />
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="w-full md:w-1/2 space-y-6">
                                <div>
                                    <InputLabel for="weight" value="Berat (gram)" />
                                    <TextInput id="weight" type="number" step="0.01" class="mt-1 block w-full"
                                        v-model="priceReactive.weight" autocomplete="weight"
                                        placeholder="Masukan berat perhiasan" />
                                    <InputError class="mt-2" :message="form.errors.weight" />
                                </div>
                            </div>
                            <div class="w-full md:w-1/2 space-y-6">
                                <div>
                                    <InputLabel for="cost" value="Ongkos" />
                                    <CurrencyInput id="cost" class="mt-1 block w-full" v-model="priceReactive.cost"
                                        autocomplete="cost" placeholder="Masukan ongkos (jika ada)" />
                                    <InputError class="mt-2" :message="form.errors.cost" />
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col md:flex-row gap-6">
                            <div class="w-full md:w-1/2 space-y-6">
                                <div>
                                    <InputLabel for="total_price" value="Total Harga" />
                                    <TextInput id="total_price" type="text" class="mt-1 block w-full bg-zinc-100"
                                        v-model="form.total_price" readonly />
                                    <InputError class="mt-2" :message="form.errors.total_price" />
                                </div>
                            </div>
                            <div class="w-full md:w-1/2 space-y-6">
                                <div>
                                    <InputLabel for="paid_amount" value="Jumlah Dibayarkan" />
                                    <CurrencyInput id="paid_amount" type="paid_amount" class="mt-1 block w-full"
                                        v-model="form.paid_amount" autocomplete="paid_amount"
                                        placeholder="Masukan jumlah dibayarkan" />
                                    <InputError class="mt-2" :message="form.errors.paid_amount" />
                                </div>
                            </div>
                        </div>

                        <div>
                            <InputLabel for="remarks" value="Catatan" />
                            <TextareaInput id="remarks" name="remarks" rows="6" v-model="form.remarks"
                                placeholder="Tinggalkan catatan..." />
                            <InputError class="mt-2" :message="form.errors.remarks" />
                        </div>

                        <div class="flex items-center gap-2">
                            <PrimaryButton :disabled="form.processing">
                                Simpan
                            </PrimaryButton>
                            <Link :href="route('orders.index')">
                            <SecondaryButton type="reset" :disabled="form.processing">
                                Kembali
                            </SecondaryButton>
                            </Link>
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-1/4">
                    <div class="bg-white overflow-hidden sm:rounded-lg border p-4 sm:p-8 space-y-6">
                        <div>
                            <InputLabel for="pramuniaga" value="Pramuniaga" />
                            <TextInput id="pramuniaga" class="mt-1 block w-full bg-gray-200" v-model="sales" disabled />
                        </div>
                        <SelectCostumer v-model="costumer" />
                        <InputError class="mt-2" :message="form.errors.costumer_id" />
                    </div>
                </div>
            </div>
        </form>

    </AuthenticatedLayout>
</template>
