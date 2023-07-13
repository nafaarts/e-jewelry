<template>
    <input
        type="text"
        ref="input"
        class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5"
        :value="updateAmount(modelValue)"
        @keyup="$emit('update:modelValue', updateAmount($event.target.value))"
    />
</template>

<script setup>
import { convertToCurrency } from "@/utils/currencyFormatter";
import { onMounted, ref } from "vue";

defineProps({
    modelValue: {
        type: String,
        required: true,
    },
});

defineEmits(["update:modelValue"]);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });

const updateAmount = (n) => {
    return convertToCurrency(n);
};
</script>
