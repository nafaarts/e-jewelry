<template>
    <div class="relative overflow-x-auto sm:rounded-md no-scrollbar">
        <table class="w-full text-sm text-left text-gray-500">
            <thead
                v-if="$slots.head"
                class="text-xs text-gray-700 uppercase bg-orange-200"
            >
                <slot name="head" />
            </thead>
            <tbody>
                <slot />
            </tbody>
        </table>
    </div>
</template>
