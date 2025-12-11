<template>
    <div class="min-h-screen flex flex-col">
        <BarraNavegacao />

        <main class="flex-1">
            <div class="max-w-6xl mx-auto px-4 py-6">
                <slot />
            </div>
        </main>
    </div>
</template>

<script setup>
import BarraNavegacao from "./BarraNavegacao.vue";
</script>
