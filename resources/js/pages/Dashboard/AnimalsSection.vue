<template>
    <v-card class="shadow-md">
        <v-card-title class="text-xl font-semibold">
            <v-icon class="mr-2">mdi-paw</v-icon>
            Meus Animais
            <v-chip size="small" class="ml-2" color="success">
                {{ animalCount }}
            </v-chip>
        </v-card-title>
        <v-card-subtitle>Gerencie os dados dos seus animais de estimação</v-card-subtitle>

        <v-card-text class="flex w-full flex-col gap-4 pt-4">
            <div v-if="animals && animals.length > 0" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <AnimalCard v-for="animal in animals" :key="animal.id" :animal="animal" @view-details="handleViewDetails" />
            </div>
            <div v-else class="py-12 text-center text-gray-500">
                <v-icon size="48" class="mb-4">mdi-paw-off</v-icon>
                <p>Nenhum animal registado</p>
            </div>

            <v-btn size="large" color="primary" variant="elevated" class="mt-4 hover:cursor-pointer" @click="handleListAnimals">
                <v-icon class="mr-2">mdi-format-list-bulleted-square</v-icon>
                Todos os animais
            </v-btn>
        </v-card-text>
    </v-card>
</template>

<script setup lang="ts">
import AnimalCard from './AnimalCard.vue';
import { Animal } from './Dashboard.vue';

defineProps({
    animals: {
        type: Array as () => Animal[],
        default: () => [],
    },
    animalCount: {
        type: Number,
        default: 0,
    },
});

const emit = defineEmits(['view-details', 'list-animals']);

const handleViewDetails = (animalId: string) => {
    emit('view-details', animalId);
};

const handleListAnimals = () => {
    emit('list-animals');
};
</script>
