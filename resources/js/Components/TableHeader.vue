<script setup>
import { watch, ref } from "vue";

const props = defineProps({
    items: {
        type: Array,
        required: true,
    },
    modelValue: {
        type: String,
        required: false,
    },
});

const emit = defineEmits(["update:modelValue"]);

const selected = ref(props.modelValue);

const select = (label) => {
    const current = selected.value ?? "";
    const sign = current.charAt(0);

    if (current.includes(label)) {
        selected.value = sign === "-" ? null : `-${label}`;
        return;
    }

    selected.value = label;
};

const getSelected = (label) => {
    if (selected.value !== null && selected.value?.includes(label)) {
        return selected.value.charAt(0) === "-" ? "DESC" : "ASC";
    }

    return null;
};

watch(selected, (value) => {
    emit("update:modelValue", value);
});
</script>

<template>
    <tr>
        <th
            v-for="(item, index) in items"
            :key="index"
            scope="col"
            class="px-4 py-3 whitespace-nowrap"
        >
            <button
                v-if="item.sort"
                class="flex gap-2 items-center uppercase hover:underline"
                v-show="item.sort"
                @click="select(item?.label ?? item.name)"
            >
                <span v-text="item.name" />
                <div v-show="getSelected(item.label)">
                    <i
                        v-if="getSelected(item.label) === 'ASC'"
                        class="fas fa-fw fa-caret-up"
                    >
                    </i>
                    <i v-else class="fas fa-fw fa-caret-down"></i>
                </div>
            </button>
            <span v-else v-text="item.name" />
        </th>
    </tr>
</template>
