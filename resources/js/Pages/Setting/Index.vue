<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

import { Head } from "@inertiajs/vue3";
import InvoiceSetting from "./Partials/Invoice/Index.vue";
// import DepositSetting from "./Partials/DepositSetting.vue";
// import HardwareSetting from "./Partials/HardwareSetting.vue";

const props = defineProps({
    invoices: Object,
});
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Pengaturan" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Pengaturan
            </h2>
        </template>

        <div class="space-y-6">
            <div class="p-4 sm:p-8 bg-white border sm:rounded-lg">
                <InvoiceSetting :data="invoices" />
            </div>

            <!-- <div class="p-4 sm:p-8 bg-white border sm:rounded-lg">
                <DepositSetting />
            </div> -->

            <!-- <div class="p-4 sm:p-8 bg-white border sm:rounded-lg">
                <HardwareSetting />
            </div> -->
        </div>
    </AuthenticatedLayout>
</template>
