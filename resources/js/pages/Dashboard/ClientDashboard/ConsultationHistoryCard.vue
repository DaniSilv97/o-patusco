<template>
    <div class="border-primary rounded border-l-4 bg-green-100 p-4 transition hover:bg-green-200">
        <Link :href="route('consultation', consultation.id)">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-sm text-gray-600"><strong>Animal:</strong> {{ consultation.animal_name }}</p>
                    <p class="text-sm text-gray-600"><strong>Data da Consulta:</strong> {{ formatDate(consultation.date) }}</p>
                    <p class="mt-2 text-gray-700"><strong>Notas do Veterinário:</strong> {{ consultation.veterinarian_note }}</p>
                </div>
                <v-chip size="small" color="green" text-color="white">
                    {{ consultation.state }}
                </v-chip>
            </div>
        </Link>
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

interface Consultation {
    id: string;
    animal_name: string;
    client_note: string;
    date: string;
    state: string;
    veterinarian_note: string;
}

defineProps({
    consultation: {
        type: Object as () => Consultation,
        required: true,
    },
});

const formatDate = (dateString: string): string => {
    try {
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return 'Data inválida';
        return date.toLocaleString('pt-PT', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: false,
        });
    } catch {
        return 'Data inválida';
    }
};
</script>
