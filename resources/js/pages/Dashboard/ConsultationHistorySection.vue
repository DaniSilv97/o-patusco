<template>
    <div class="flex flex-col gap-3">
        <h3 class="text-lg font-semibold text-gray-800">
            Histórico de Consultas
            <v-chip size="small" class="ml-2" color="success">
                {{ consultationCount }}
            </v-chip>
        </h3>

        <div v-if="consultations && consultations.length > 0" class="flex flex-col gap-3">
            <ConsultationHistoryCard v-for="consultation in consultations" :key="consultation.id" :consultation="consultation" />
        </div>
        <div v-else class="py-6 text-center text-gray-500">
            <p>Nenhuma consulta registada</p>
        </div>
    </div>
</template>

<script setup lang="ts">
import ConsultationHistoryCard from './ConsultationHistoryCard.vue';

interface Consultation {
    id: string;
    animal_name: string;
    client_note: string;
    date: string;
    state: string;
    veterinarian_note: string;
}

defineProps({
    consultations: {
        type: Array as () => Consultation[],
        default: () => [],
    },
    consultationCount: {
        type: Number,
        default: 0,
    },
});
</script>
