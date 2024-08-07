<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import { useForm } from '@inertiajs/vue3';
import TextareaInput from '@/Components/TextareaInput.vue';
import FileInput from '@/Components/FileInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Select from '@/Components/Select.vue';
import InputError from '@/Components/InputError.vue';
import { ref } from 'vue';

const props = defineProps({
    data: Object,
});

const form = useForm({
    type: 'deposit_invoice',
    header_image: '',
    paper_size: props.data.paper_size ?? '',
    note: props.data.note ?? '',
});

const imagePreview = ref(`storage/${props.data.header_image}`);
const onUpdateImage = (e) => {
    const file = e.target.files[0];
    form.header_image = file;
    imagePreview.value = URL.createObjectURL(file);
};

const updateSetting = () => {
    form.post(route("setting.invoice.update"), {
        preserveScroll: true,
    });
};
</script>

<template>
    <div>
        <form class="space-y-3" @submit.prevent="updateSetting">
            <h3 class="text-base font-medium text-gray-900">Titipan</h3>

            <div class="flex flex-col-reverse md:flex-row gap-3">
                <div class="w-full md:w-2/3">
                    <InputLabel value="Gambar Header" />
                    <FileInput id="deposit_header_image" v-model="form.header_image" accept="image/*"
                        @input="onUpdateImage" />
                    <InputError class="mt-2" :message="form.errors.header_image" />
                </div>

                <div class="w-full md:w-1/3">
                    <InputLabel value="Ukuran Kertas" />
                    <Select v-model="form.paper_size">
                        <option value="" disabled>Pilih ukuran kertas</option>
                        <option value="A4">A4</option>
                        <option value="A5">A5</option>
                    </Select>
                    <InputError class="mt-2" :message="form.errors.paper_size" />
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-3">
                <div class="w-full md:w-2/3">
                    <label for="deposit_header_image">
                        <div class="bdeposit rounded p-2 bg-gray-100 h-32">
                            <img class="mx-auto rounded max-h-28" :src="imagePreview" alt="Header Service">
                        </div>
                    </label>
                </div>
                <div class="w-full md:w-1/3">
                    <InputLabel value="Catatan" />
                    <TextareaInput v-model="form.note" />
                    <InputError class="mt-2" :message="form.errors.note" />
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Simpan</PrimaryButton>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">
                        Tersimpan.
                    </p>
                </Transition>
            </div>
        </form>
    </div>
</template>