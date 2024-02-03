<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

import { Head } from "@inertiajs/vue3";

</script>

<template>
    <AuthenticatedLayout>

        <Head title="Pengaturan" />

        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pengaturan
        </h2>
        <hr class="my-4" />

        <div class="space-y-6">
            <div class="p-4 sm:p-8 bg-white border sm:rounded-lg">
                <!-- <UpdateProfileInformationForm
                    :user="user"
                    :must-verify-email="mustVerifyEmail"
                    :status="status"
                /> -->
            </div>
        </div>
    </AuthenticatedLayout>
</template>
