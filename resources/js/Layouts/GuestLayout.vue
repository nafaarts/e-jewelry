<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
</script>

<template>
    <div
        class="min-h-screen flex flex-col justify-center items-center pt-6 sm:pt-0 bg-white sm:bg-zinc-50"
    >
        <div
            class="w-full sm:max-w-md px-8 sm:px-5 py-8 bg-white sm:shadow overflow-hidden sm:rounded-lg"
        >
            <div class="flex justify-center items-center gap-4">
                <ApplicationLogo class="h-10" />
                <span
                    class="self-center text-2xl font-semibold whitespace-nowrap text-zinc-600 dark:text-white"
                >
                    Toko Mas Bina Nusa
                </span>
            </div>
            <hr class="my-5" />
            <slot />
        </div>
    </div>
</template>
