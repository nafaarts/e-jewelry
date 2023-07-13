<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    link: String,
    label: String,
    icon: String,
    active: Boolean,
});
</script>

<template>
    <Link
        as="li"
        :class="`relative flex items-center gap-2.5 rounded-md py-2 px-4 font-medium duration-300 ease-in-out hover:bg-orange-100 cursor-pointer ${
            props.active && 'bg-orange-200'
        }`"
        :href="props.link"
    >
        <i v-if="icon" :class="`fas fa-fw fa-${icon} text-zinc-700`"></i>
        <span v-text="props.label" class="text-sm text-zinc-900" />
    </Link>
</template>
