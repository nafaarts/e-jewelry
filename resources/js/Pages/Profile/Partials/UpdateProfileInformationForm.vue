<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import FileInput from "@/Components/FileInput.vue";
import { Link, useForm, router } from "@inertiajs/vue3";

const props = defineProps({
    user: Object,
    mustVerifyEmail: Boolean,
    status: String,
});

const user = props.user;

const form = useForm({
    _method: "patch",
    user_code: user.user_code,
    name: user.name,
    email: user.email,
    indentity_number: user.indentity_number,
    phone_number: user.phone_number,
    address: user.address,
    photo: null,
});
</script>

<template>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Informasi Akun</h2>

        <p class="mt-1 text-sm text-gray-600">
            Perbarui informasi profil akun dan alamat email Anda.
        </p>
    </header>

    <form
        @submit.prevent="form.post(route('profile.update'))"
        class="mt-6 space-y-6"
    >
        <div class="flex flex-col md:flex-row gap-6">
            <div class="w-full md:w-1/2 space-y-6">
                <div>
                    <InputLabel for="user_code" value="Kode User" />

                    <TextInput
                        id="user_code"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.user_code"
                        readonly
                    />

                    <InputError class="mt-2" :message="form.errors.user_code" />
                </div>

                <div>
                    <InputLabel for="name" value="Nama" />

                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.name"
                        autocomplete="name"
                    />

                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div>
                    <InputLabel for="email" value="Email" />

                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        autocomplete="username"
                    />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>
            </div>

            <div class="w-full md:w-1/2 space-y-6">
                <div>
                    <InputLabel for="phone_number" value="Nomor Handphone" />

                    <TextInput
                        id="phone_number"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.phone_number"
                        autocomplete="phone_number"
                    />

                    <InputError
                        class="mt-2"
                        :message="form.errors.phone_number"
                    />
                </div>

                <div>
                    <InputLabel
                        for="indentity_number"
                        value="Nomor Indentitas"
                    />

                    <TextInput
                        id="indentity_number"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.indentity_number"
                        autocomplete="indentity_number"
                    />

                    <InputError
                        class="mt-2"
                        :message="form.errors.indentity_number"
                    />
                </div>

                <div>
                    <InputLabel for="address" value="Alamat" />

                    <TextInput
                        id="address"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.address"
                        autocomplete="address"
                    />

                    <InputError class="mt-2" :message="form.errors.address" />
                </div>
            </div>
        </div>

        <div>
            <InputLabel for="photo" value="Photo" />

            <FileInput
                id="photo"
                accept="image/*"
                class="mt-1 block w-full"
                @input="form.photo = $event.target.files[0]"
            />

            <progress
                v-if="form.progress"
                :value="form.progress.percentage"
                max="100"
            >
                {{ form.progress.percentage }}%
            </progress>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">
                SVG, PNG, JPG or GIF (MAX. 800x400px).
            </p>

            <InputError class="mt-2" :message="form.errors.photo" />
        </div>

        <div v-if="mustVerifyEmail && user.email_verified_at === null">
            <p class="text-sm mt-2 text-gray-800">
                Alamat email Anda belum diverifikasi.
                <Link
                    :href="route('verification.send')"
                    method="post"
                    as="button"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Klik di sini untuk mengirim ulang email verifikasi.
                </Link>
            </p>

            <div
                v-show="status === 'verification-link-sent'"
                class="mt-2 font-medium text-sm text-green-600"
            >
                Tautan verifikasi baru telah dikirim ke alamat email Anda.
            </div>
        </div>

        <div class="flex items-center gap-4">
            <PrimaryButton :disabled="form.processing">Simpan</PrimaryButton>

            <Transition
                enter-from-class="opacity-0"
                leave-to-class="opacity-0"
                class="transition ease-in-out"
            >
                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">
                    Tersimpan.
                </p>
            </Transition>
        </div>
    </form>
</template>
