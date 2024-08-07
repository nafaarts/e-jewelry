<template>
    <div class="container">
        <div id="interactive" class="viewport"></div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, defineEmits } from "vue";
import Quagga from "@ericblade/quagga2";

import SoundEffect from "../../assets/store-scanner.mp3";
const audio = new Audio(SoundEffect);

const scannerActive = ref(false);
const emit = defineEmits(["scan-success"]);

const startScanner = () => {
    Quagga.init(
        {
            inputStream: {
                type: "LiveStream",
                target: "#interactive",
                constraints: {
                    width: { min: 640 },
                    height: { min: 480 },
                    facingMode: "environment",
                    aspectRatio: { min: 1, max: 2 },
                },
            },
            locate: true,
            locator: {
                patchSize: "medium",
                halfSample: true,
                willReadFrequently: true,
            },
            numOfWorkers: 10,
            decoder: {
                readers: ["code_128_reader"],
            },
        },
        (err) => {
            if (err) {
                console.error(err);
                return;
            }
            console.log("Initialization finished. Ready to start");
            Quagga.start();
        }
    );

    Quagga.onDetected((result) => {
        audio.play();

        emit("scan-success", result.codeResult.code);
        stopScanner();
    });
};

const stopScanner = () => {
    Quagga.stop();
    scannerActive.value = false;
};

onMounted(() => {
    startScanner();
});

onUnmounted(() => {
    stopScanner();
});
</script>

<style>
.container {
    position: relative;
    width: 100%;
    height: auto;
}

#interactive.viewport {
    width: 100%;
    height: auto;
}

#interactive.viewport canvas,
video {
    width: 100%;
    height: auto;
}

canvas {
    position: absolute !important;
    top: 0;
}
</style>
