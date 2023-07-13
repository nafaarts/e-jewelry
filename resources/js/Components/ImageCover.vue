<script setup>
defineProps({
    src: { type: String, required: true },
});
</script>

<template>
    <div
        :style="`background-image: url(${src}); background-size:cover; background-position: center center`"
    ></div>
</template>
