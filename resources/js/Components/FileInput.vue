<script setup>
import { onMounted, ref } from "vue";

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        type="file"
        ref="input"
        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer focus:ring-orange-500 focus:border-orange-500"
    />
</template>
