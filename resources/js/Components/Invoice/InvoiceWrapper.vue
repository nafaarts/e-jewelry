<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    size: String,
    backRoute: String,
});

const print = () => {
    window.print();
}

// A5: 148mm x 210mm
// A4: 210mm x 297mm
const defaultVariable = {
    '--width': props.size === 'A5' ? '148mm' : '210mm',
    '--height': props.size === 'A5' ? '210mm' : '297mm',
    '--padding-header': props.size === 'A5' ? '90px' : '140px',
    '--padding': props.size === 'A5' ? '20px' : '40px'
}
</script>

<template>
    <div class="flex justify-center items-center bg-zinc-100 h-screen overflow-auto">
        <div class="page flex flex-col bg-white" :class="{
            'scale-75 md:scale-100': props.size === 'A5',
            'scale-50 md:scale-75': props.size === 'A4'
        }" id="print-area" :style="defaultVariable">
            <slot />
        </div>

        <div class="fixed flex gap-2 left-5 top-5 p-3 rounded shadow-sm bg-white">
            <Link :href="backRoute" class="w-fit">
            <SecondaryButton>
                <i class="fas fa-fw fa-arrow-left"></i>
            </SecondaryButton>
            </Link>
            <PrimaryButton @click="print" class="w-fit">
                <i class="fas fa-fw fa-print"></i>
            </PrimaryButton>
        </div>
    </div>
</template>

<style>
.page {
    padding: var(--padding-header) var(--padding) var(--padding) var(--padding);
    min-width: var(--width);
    max-width: var(--width);
    min-height: var(--height);
}

@media print {
    body {
        visibility: hidden;
    }

    #print-area {
        visibility: visible;
        position: absolute;
        left: 0;
        top: 0;
        min-width: var(--width);
        min-height: var(--height);
        transform: scale(1);
    }
}
</style>