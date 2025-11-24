<template>
    <v-card class="shadow-md">
        <v-card-title class="text-xl font-semibold">
            <div class="flex items-center gap-2">
                <v-icon>mdi-clipboard-check-outline</v-icon>
                Minhas Consultas
            </div>
        </v-card-title>
        <v-card-subtitle class="text-base">
            Total de consultas: <strong>{{ pagination.total }}</strong>
        </v-card-subtitle>

        <v-divider></v-divider>

        <v-card-text class="flex flex-wrap gap-2 border-b pb-4">
            <v-btn
                size="small"
                :variant="stateFilter === null || stateFilter === 'all' ? 'flat' : 'outlined'"
                :color="stateFilter === null || stateFilter === 'all' ? 'primary' : undefined"
                @click="$emit('filter-change', null)"
            >
                Todas ({{ allConsultations.length }})
            </v-btn>
            <v-btn
                size="small"
                :variant="stateFilter === 'Atribuída' ? 'flat' : 'outlined'"
                :color="stateFilter === 'Atribuída' ? 'primary' : undefined"
                @click="$emit('filter-change', 'Atribuída')"
            >
                Atribuídas ({{ countByState('Atribuída') }})
            </v-btn>
            <v-btn
                size="small"
                :variant="stateFilter === 'Completa' ? 'flat' : 'outlined'"
                :color="stateFilter === 'Completa' ? 'primary' : undefined"
                @click="$emit('filter-change', 'Completa')"
            >
                Concluídas ({{ countByState('Completa') }})
            </v-btn>
            <v-btn
                size="small"
                :variant="stateFilter === 'Cancelada' ? 'flat' : 'outlined'"
                :color="stateFilter === 'Cancelada' ? 'primary' : undefined"
                @click="$emit('filter-change', 'Cancelada')"
            >
                Canceladas ({{ countByState('Cancelada') }})
            </v-btn>
        </v-card-text>

        <v-card-text class="p-0">
            <div v-if="consultations.length === 0" class="flex flex-col items-center justify-center py-12 text-center">
                <v-icon size="64" class="mb-4 text-gray-300">mdi-calendar-blank-outline</v-icon>
                <p class="text-lg text-gray-500">Sem consultas agendadas</p>
            </div>

            <div v-else class="divide-y">
                <div
                    v-for="consultation in consultations"
                    :key="consultation.id"
                    class="cursor-pointer border-l-4 border-blue-500 p-4 transition-colors hover:bg-gray-50"
                    @click="emit('edit', consultation.id)"
                >
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="mb-2 flex items-center gap-2">
                                <p class="font-semibold text-gray-900">{{ consultation.animal_name }}</p>
                                <v-chip size="x-small" :color="getStateColor(consultation.state)" text-color="white">
                                    {{ consultation.state }}
                                </v-chip>
                            </div>
                            <p class="text-sm text-gray-600"><strong>Data da Consulta:</strong> {{ formatDate(consultation.date) }}</p>
                            <div v-if="consultation.client_note" class="mt-2">
                                <p class="text-sm text-gray-700">
                                    <strong>Nota do Cliente:</strong> {{ truncateText(consultation.client_note, 80) }}
                                </p>
                            </div>
                            <div v-if="consultation.veterinarian_note" class="mt-1">
                                <p class="text-sm text-gray-700">
                                    <strong>Minhas Notas:</strong> {{ truncateText(consultation.veterinarian_note, 80) }}
                                </p>
                            </div>
                        </div>
                        <v-icon class="ml-4 text-gray-400">mdi-chevron-right</v-icon>
                    </div>
                </div>
            </div>
        </v-card-text>

        <v-divider></v-divider>
        <v-card-text v-if="pagination.last_page > 1" class="flex flex-col items-center gap-4 py-4">
            <div class="flex items-center gap-2">
                <v-btn
                    variant="outlined"
                    size="small"
                    :disabled="pagination.current_page === 1"
                    @click="$emit('page-change', pagination.current_page - 1)"
                    icon="mdi-chevron-left"
                ></v-btn>

                <div class="flex gap-1">
                    <v-btn
                        v-for="page in displayedPages"
                        :key="page"
                        :variant="page === pagination.current_page ? 'flat' : 'outlined'"
                        :color="page === pagination.current_page ? 'primary' : undefined"
                        size="small"
                        @click="$emit('page-change', page)"
                    >
                        {{ page }}
                    </v-btn>
                </div>

                <v-btn
                    variant="outlined"
                    size="small"
                    :disabled="pagination.current_page === pagination.last_page"
                    @click="$emit('page-change', pagination.current_page + 1)"
                    icon="mdi-chevron-right"
                ></v-btn>
            </div>

            <div class="text-center text-sm text-gray-600">Ver {{ startIndex }} a {{ endIndex }} de {{ pagination.total }} consultas</div>
        </v-card-text>
    </v-card>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { Consultation, Pagination } from './VeterinarianDashboard.vue';

const props = defineProps({
    consultations: {
        type: Array as () => Consultation[],
        default: () => [],
    },
    pagination: {
        type: Object as () => Pagination,
        required: true,
    },
    stateFilter: {
        type: String as () => string | null,
        default: null,
    },
    allConsultations: {
        type: Array as () => Consultation[],
        default: () => [],
    },
});

const emit = defineEmits(['page-change', 'edit', 'filter-change']);

const displayedPages = computed(() => {
    const pages: number[] = [];
    const maxPagesToShow = 5;
    const halfWindow = Math.floor(maxPagesToShow / 2);

    let start = Math.max(1, props.pagination.current_page - halfWindow);
    let end = Math.min(props.pagination.last_page, props.pagination.current_page + halfWindow);

    if (props.pagination.current_page <= halfWindow) {
        end = Math.min(props.pagination.last_page, maxPagesToShow);
    } else if (props.pagination.current_page > props.pagination.last_page - halfWindow) {
        start = Math.max(1, props.pagination.last_page - maxPagesToShow + 1);
    }

    for (let i = start; i <= end; i++) {
        pages.push(i);
    }

    return pages;
});

const startIndex = computed(() => {
    return (props.pagination.current_page - 1) * props.pagination.per_page + 1;
});

const endIndex = computed(() => {
    return Math.min(props.pagination.current_page * props.pagination.per_page, props.pagination.total);
});

const countByState = (state: string): number => {
    return props.allConsultations.filter((consultation: Consultation) => consultation.state === state).length;
};

const formatDate = (dateString: string): string => {
    try {
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return 'Data inválida';
        return date.toLocaleString('pt-PT', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        });
    } catch {
        return 'Data inválida';
    }
};

const truncateText = (text: string, length: number): string => {
    return text.length > length ? text.substring(0, length) + '...' : text;
};

const getStateColor = (state: string): string => {
    const colors: Record<string, string> = {
        Completa: 'success',
        Atribuída: 'info',
        Cancelada: 'error',
    };
    return colors[state] || 'default';
};
</script>
