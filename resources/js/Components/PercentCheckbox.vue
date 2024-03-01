<script setup>
import { computed } from "vue";

const emit = defineEmits(["update:checked"]);

const props = defineProps({
    id: {
        type: String,
        required: true,
    },
    checked: {
        type: [Array, Boolean],
        required: true,
    },
    value: {
        default: null,
    },
});

const proxyChecked = computed({
    get() {
        return props.checked;
    },

    set(val) {
        emit("update:checked", val);
    },
});
</script>

<template>
    <input type="checkbox" :value="value" v-model="proxyChecked" :id="id" value="" class="hidden peer">
    <label :for="id"
        class="inline-flex items-center justify-between w-full py-1.5 px-3 text-gray-500 bg-white border border-gray-300 rounded-lg cursor-pointer peer-checked:border-orange-300 peer-checked:bg-orange-300 hover:text-gray-600 peer-checked:text-gray-600 hover:bg-gray-100">
        <div class="block">
            <div class="w-full text-lg font-semibold">%</div>
        </div>
    </label>
</template>