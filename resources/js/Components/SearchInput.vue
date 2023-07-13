<script setup>
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
</script>

<template>
    <div
        class="relative bg-white rounded-lg border border-gray-300 overflow-hidden"
    >
        <i class="absolute top-3 left-3 fas fa-search text-gray-500"></i>
        <input
            class="border-0 pl-10 text-gray-900 text-sm rounded-lg focus:ring-orange-500 focus:border-orange-500 block w-full p-2.5"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            ref="input"
            placeholder="Cari sesuatu..."
        />
    </div>
</template>
