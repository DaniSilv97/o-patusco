<template>
    <div class="border-secondary rounded border-l-4 bg-green-50 p-4 transition hover:bg-green-100">
        <Link :href="route('consultation', request.id)">
            <div class="flex items-start justify-between hover:cursor-pointer">
                <div class="flex-1">
                    <p class="text-sm text-gray-600"><strong>Animal:</strong> {{ request.animal_name }}</p>
                    <p class="text-sm text-gray-600"><strong>Data:</strong> {{ formatDate(request.date) }}</p>
                    <p class="mt-2 text-gray-700">{{ request.client_note }}</p>
                </div>
                <v-chip size="small" color="blue" text-color="white">
                    {{ request.timeframe }}
                </v-chip>
            </div>
        </Link>
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

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
