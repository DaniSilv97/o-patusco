<template>
    <div class="flex flex-col gap-2">
        <h1 class="text-3xl font-bold">{{ title }}</h1>
        <p class="text-gray-600">{{ description }}</p>
    </div>
</template>

<script setup lang="ts">
defineProps({
    title: {
        type: String,
        required: true,
    },
    description: {
        type: String,
        required: true,
    },
});
</script>
