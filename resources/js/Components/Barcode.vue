<script setup>
import VueBarcode from "@chenfengyuan/vue-barcode";

defineProps({
    value: String,
});
</script>

<template>
    <vue-barcode
        :value="value"
        :options="{
            width: 0.8,
            height: 20,
            format: 'CODE128',
            displayValue: true,
            fontSize: 8,
            margin: 2,
            lineColor: '#000',
        }"
        tag="svg"
    />
</template>
