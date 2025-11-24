<template>
    <div class="flex flex-col gap-3">
        <h3 class="text-lg font-semibold text-gray-800">
            Pedidos Pendentes
            <v-chip size="small" class="ml-2" :color="requests?.length > 0 ? 'warning' : 'success'">
                {{ requestCount }}
            </v-chip>
        </h3>

        <div v-if="requests && requests.length > 0" class="flex flex-col gap-3">
            <ConsultationRequestCard v-for="request in requests" :key="request.id" :request="request" />
        </div>
        <div v-else class="py-6 text-center text-gray-500">
            <p>Nenhum pedido de consulta pendente</p>
        </div>
    </div>
</template>

<script setup lang="ts">
import ConsultationRequestCard from './ConsultationRequestCard.vue';

interface ConsultationRequest {
    id: string;
    animal_name: string;
    client_note: string;
    date: string;
    timeframe: string;
}

defineProps({
    requests: {
        type: Array as () => ConsultationRequest[],
        default: () => [],
    },
    requestCount: {
        type: Number,
        default: 0,
    },
});
</script>
