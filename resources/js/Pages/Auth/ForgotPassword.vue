<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";

import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, useForm } from "@inertiajs/vue3";

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
});

const submit = () => {
    form.post(route("password.email"));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <div class="mb-4 text-sm text-gray-600">
            <strong>lupa kata sandi Anda? Tidak masalah.</strong>
            <br />
            <p>
                Beri tahu kami alamat email Anda dan kami akan mengirimi Anda
                email pengaturan ulang kata sandi link yang akan memungkinkan
                Anda untuk memilih yang baru.
            </p>
        </div>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <PrimaryButton
                    class="w-full flex justify-center py-2"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    <span>Email Password Reset Link</span>
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
