<script setup>
import Sale from './Sale.vue';
import Order from './Order.vue';
import Service from './Service.vue';

const props = defineProps({
    data: Object,
});

</script>

<template>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Invoice</h2>

        <p class="mt-1 text-sm text-gray-600">
            Pengaturan untuk mempebarui data informasi invoice pada aplikasi anda.
        </p>
    </header>

    <hr class="my-4" />

    <div class="space-y-6">
        <Sale :data="data?.sale" />
        <hr />
        <Order :data="data?.order" />
        <hr />
        <Service :data="data?.service" />
    </div>
</template>
