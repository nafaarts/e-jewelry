<template>
    <div class="flex justify-between items-center">
        <label class="inline-block text-sm text-zinc-400">
            Grafik Harga Emas
        </label>
        <div>
            <select
                id="countries"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-xs rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full px-2 py-1"
                v-model="filter"
            >
                <option value="1 week ago">Minggu ini</option>
                <option value="1 month ago">Bulan ini</option>
                <option value="3 month ago">3 bulan terakhir</option>
                <option value="6 month ago">6 bulan terakhir</option>
                <option value="1 year ago">1 tahun terakhir</option>
            </select>
        </div>
    </div>
    <hr class="my-3" />

    <Bar id="my-chart-id" :options="chartOptions" :data="chartData" />
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import { Bar } from "vue-chartjs";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
} from "chart.js";

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale
);

const filter = ref("1 week ago");

const labels = ref([]);
const sells = ref([]);
const buys = ref([]);
const neutrals = ref([]);

const getData = async () => {
    try {
        const url = new URL("http://localhost:8000/api/prices");
        const params = new URLSearchParams();
        params.append("filter", filter.value);

        url.search = params.toString();

        const response = await fetch(url);
        const data = await response.json();

        labels.value = data.map((i) => i.label);
        sells.value = data.map((i) => i.sell);
        buys.value = data.map((i) => i.buy);
        neutrals.value = data.map((i) => i.neutral);
    } catch (error) {
        console.error(error);
    }
};

onMounted(getData);

watch(filter, getData);

const chartData = computed(() => {
    return {
        labels: labels.value,
        datasets: [
            {
                label: "Jual",
                backgroundColor: "#31C48D",
                borderColor: "#31C48D",
                data: sells.value,
            },
            {
                label: "Netral",
                backgroundColor: "#9CA3AF",
                borderColor: "#9CA3AF",
                data: neutrals.value,
            },
            {
                label: "Beli",
                backgroundColor: "#76A9FA",
                borderColor: "#76A9FA",
                data: buys.value,
            },
        ],
    };
});
const chartOptions = {
    responsive: true,
};
</script>
