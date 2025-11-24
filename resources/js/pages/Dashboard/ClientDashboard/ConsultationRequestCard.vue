<template>
    <div class="rounded border-l-4 border-blue-500 bg-blue-50 p-4">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <p class="text-sm text-gray-600"><strong>Animal:</strong> {{ request.animal_name }}</p>
                <p class="text-sm text-gray-600"><strong>Data:</strong> {{ formatDate(request.date) }}</p>
                <p class="mt-2 text-gray-700">{{ request.client_note }}</p>
            </div>
            <v-chip size="small" color="blue" text-color="white">
                {{ request.timeframe }}
            </v-chip>
        </div>
    </div>
</template>

<script setup lang="ts">
interface ConsultationRequest {
    id: string;
    animal_name: string;
    client_note: string;
    date: string;
    timeframe: string;
}

defineProps({
    request: {
        type: Object as () => ConsultationRequest,
        required: true,
    },
});

const formatDate = (dateString: string): string => {
    try {
        const date = new Date(dateString);
        return date.toLocaleDateString('pt-PT', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        });
    } catch {
        return 'Data inválida';
    }
};
</script>
