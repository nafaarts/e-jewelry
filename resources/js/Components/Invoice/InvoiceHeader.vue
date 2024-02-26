<script setup>
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
</script>

<template>
    <div class="flex justify-between items-center border-b pb-2">
        <div>
            <div class="flex items-center">
                <ApplicationLogo class="h-16 mr-3" />
                <div>
                    <h1 class="text-base font-bold text-zinc-800">Toko Mas Bina Nusa</h1>
                    <small class="text-xs text-gray-500">Jln. Tgk. Chink Pante Kulu No. 36</small>
                    <br />
                    <small class="text-xs text-gray-500">Telp. (0651) 32245 | Email. tkm.binanusa@gmail.com</small>
                </div>
            </div>
        </div>
        <div class="flex-1 text-right">
            <slot />
        </div>
    </div>
</template>