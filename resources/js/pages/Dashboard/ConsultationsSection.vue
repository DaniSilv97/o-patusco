<template>
    <v-card class="shadow-md">
        <v-card-title class="text-xl font-semibold">
            <div class="flex w-full">
                <div>
                    <v-icon class="mr-2">mdi-clipboard-check</v-icon>
                    Consultas
                </div>
                <div class="ml-auto">
                    <v-btn class="hover:cursor-pointer" @click="handleNewConsultation">
                        <v-icon class="mr-2">mdi-plus-circle</v-icon>
                        Nova marcação
                    </v-btn>
                </div>
            </div>
        </v-card-title>
        <v-card-subtitle>Gerencie os seus pedidos de consulta e histórico</v-card-subtitle>

        <v-card-text class="flex w-full flex-col gap-6 pt-4">
            <PendingRequestsSection :requests="userRequests" :request-count="requestCount" />

            <v-divider></v-divider>

            <ConsultationHistorySection :consultations="userConsultations" :consultation-count="consultationCount" />

            <div class="flex gap-3 pt-4">
                <v-btn size="large" color="primary" class="flex-1 hover:cursor-pointer" @click="handleListConsultations">
                    <v-icon class="mr-2">mdi-format-list-bulleted</v-icon>
                    Todas as consultas
                </v-btn>
            </div>
        </v-card-text>
    </v-card>
</template>

<script setup lang="ts">
import ConsultationHistorySection from './ConsultationHistorySection.vue';
import { Consultation, ConsultationRequest } from './Dashboard.vue';
import PendingRequestsSection from './PendingRequestsSection.vue';

defineProps({
    userRequests: {
        type: Array as () => ConsultationRequest[],
        default: () => [],
    },
    userConsultations: {
        type: Array as () => Consultation[],
        default: () => [],
    },
    requestCount: {
        type: Number,
        default: 0,
    },
    consultationCount: {
        type: Number,
        default: 0,
    },
});

const emit = defineEmits(['new-consultation', 'list-consultations']);

const handleNewConsultation = () => {
    emit('new-consultation');
};

function handleListConsultations() {
    emit('list-consultations');
}
</script>
