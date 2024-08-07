<template>
    <div
        class="overflow-hidden sm:rounded-lg p-5 border"
        :class="{
            'bg-orange-200': variant === 'primary',
            'bg-white': variant === 'white',
        }"
    >
        <div class="flex justify-between items-center">
            <span
                v-if="label"
                class="inline-block text-sm"
                :class="{ 'mb-3': !line, 'text-zinc-400': variant === 'white' }"
            >
                {{ label }}
            </span>
            <div v-if="$slots.action"><slot name="action" /></div>
        </div>
        <hr v-if="line" class="my-3" />
        <slot />
    </div>
</template>

<script>
export default {
    // props: [
    //     "label",
    //     "line"

    // ],\
    props: {
        label: String,
        line: String,
        variant: {
            Type: String,
            required: false,
            default: "white",
        },
    },
};
</script>
