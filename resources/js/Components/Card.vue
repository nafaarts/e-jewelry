<template>
    <div class="bg-white overflow-hidden sm:rounded-lg p-5 border">
        <div class="flex justify-between items-center">
            <label
                v-if="label"
                class="inline-block text-sm text-zinc-400"
                :class="{ 'mb-3': !line }"
            >
                {{ label }}
            </label>
            <div v-if="$slots.action"><slot name="action" /></div>
        </div>
        <hr v-if="line" class="my-3" />
        <slot />
    </div>
</template>

<script>
export default {
    props: ["label", "line"],
};
</script>
