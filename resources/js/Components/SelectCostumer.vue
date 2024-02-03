<script setup>
import "vue-search-select/dist/VueSearchSelect.css"

import { ref, watch } from "vue";
import { ModelListSelect } from "vue-search-select"
import axiosInstance from "../utils/AxiosInstance"
import debounce from "lodash/debounce";
import InputLabel from "./InputLabel.vue";
import AddCostumer from "./AddCostumer.vue";

const props = defineProps({
    modelValue: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(["update:modelValue"]);

const costumers = ref([])
const costumer = ref(props.modelValue)
const toggleAddUser = ref(false)

const getCostumer = debounce((query) => {
    axiosInstance.get(`/api/costumer?search=${query}`)
        .then(response => {
            costumers.value = response.data
            console.log(response.data);
        })
        .catch(error => {
            console.error(error.response.status, error.response.data.message)
        })
}, 500)

const optionDisplayText = (costumer) => {
    return `${costumer.name} - ${costumer.phone_number}`
}

const isObjectEmpty = (objectName) => {
    return Object.keys(objectName).length === 0
}

watch(costumer, (value) => {
    if (value?.id != null) {
        toggleAddUser.value = false
    }
    emit('update:modelValue', value)
})
</script>

<template>
    <div class="flex flex-col space-y-6">
        <div class="w-full">
            <InputLabel for="costumer" value="Data Kostumer" />
            <model-list-select :list="costumers" option-value="id" :custom-text="optionDisplayText" option-text="name"
                v-model="costumer" :placeholder="isObjectEmpty(costumer) ? 'Cari kostumer' : optionDisplayText(costumer)"
                @searchchange="getCostumer">
            </model-list-select>
        </div>

        <div>
            <div class="mb-3">
                <div v-if="!toggleAddUser" class="border border-gray-300 p-4 rounded space-y-3">
                    <div class="w-full">
                        <span class="block mb-2 text-sm text-gray-500">Kode :</span>
                        <h6 class="font-medium text-gray-700 w-full truncate">{{ costumer?.costumer_code ?? '-' }}</h6>
                    </div>

                    <div class="w-full">
                        <span class="block mb-2 text-sm text-gray-500">Nama :</span>
                        <h6 class="font-medium text-gray-700 w-full truncate">{{ costumer?.name ?? '-' }}</h6>
                    </div>

                    <div class="w-full">
                        <span class="block mb-2 text-sm text-gray-500">No Telp :</span>
                        <h6 class="font-medium text-gray-700 w-full truncate">{{ costumer?.phone_number ?? '-' }}</h6>
                    </div>

                    <div class="w-full">
                        <span class="block mb-2 text-sm text-gray-500">Alamat :</span>
                        <h6 class="font-medium text-gray-700 w-full truncate">{{ costumer?.address ?? '-' }}</h6>
                    </div>
                </div>

                <div v-else>
                    <AddCostumer @on-cancel="() => { toggleAddUser = false }" @on-success="(newCostumer) => {
                        costumer = newCostumer;
                        toggleAddUser = false;
                    }" />
                </div>
            </div>

            <div v-if="!toggleAddUser" class="flex justify-start items-center gap-3">
                <button class="text-xs mb-2 text-orange-400 hover:text-orange-600 focus:text-orange-700"
                    @click="() => { toggleAddUser = true; costumer = {} }">
                    <i class="fas fa-fw fa-plus-circle"></i> tambah
                </button>
                <button v-if="!isObjectEmpty(costumer)"
                    class="text-xs mb-2 text-orange-400 hover:text-orange-600 focus:text-orange-700"
                    @click="() => costumer = {}">
                    <i class="fas fa-fw fa-refresh"></i> reset
                </button>
            </div>
        </div>
    </div>
</template>