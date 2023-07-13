<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";

import { Head } from "@inertiajs/vue3";

defineProps({
    user: Object,
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Profile" />

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Profil
        </h2>
        <hr class="my-4" />

        <div class="space-y-6">
            <div class="p-4 sm:p-8 bg-white border sm:rounded-lg">
                <UpdateProfileInformationForm
                    :user="user"
                    :must-verify-email="mustVerifyEmail"
                    :status="status"
                />
            </div>

            <div class="p-4 sm:p-8 bg-white border sm:rounded-lg">
                <UpdatePasswordForm />
            </div>

            <!-- <div class="p-4 sm:p-8 bg-white border  sm:rounded-lg">
                <DeleteUserForm class="max-w-xl" />
            </div> -->
        </div>
    </AuthenticatedLayout>
</template>
