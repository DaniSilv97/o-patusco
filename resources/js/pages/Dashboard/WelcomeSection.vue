<template>
    <div class="flex flex-col gap-2">
        <h1 class="text-3xl font-bold">Bem-vindo, {{ userName }}!</h1>
        <p class="text-gray-600">Aqui está um resumo da sua conta e atividades</p>
    </div>
</template>

<script setup lang="ts">
defineProps({
    userName: {
        type: String,
        required: true,
    },
});
</script>
