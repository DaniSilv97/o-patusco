<template>
    <div class="space-y-4">
        <v-card class="shadow-md">
            <v-card-title class="text-xl font-semibold">
                <div class="flex items-center gap-2">
                    <v-icon>mdi-clipboard-check-outline</v-icon>
                    Consultas Agendadas
                </div>
            </v-card-title>
            <v-card-subtitle class="text-base">
                Total de consultas: <strong>{{ pagination.total }}</strong>
            </v-card-subtitle>

            <v-divider></v-divider>

            <v-card-text class="bg-gray-50 py-4">
                <div class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700"> Filtrar por Data </label>
                            <v-text-field
                                v-model="localFilters.consultation_day"
                                type="date"
                                variant="outlined"
                                density="compact"
                                @update:model-value="applyFilters"
                            ></v-text-field>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700"> Filtrar por Tipo de Animal </label>
                            <v-select
                                v-model="localFilters.consultation_animal_type_id"
                                :items="animalTypes"
                                item-title="name"
                                item-value="id"
                                placeholder="Selecionar tipo de animal"
                                variant="outlined"
                                density="compact"
                                clearable
                                @update:model-value="applyFilters"
                            ></v-select>
                        </div>
                    </div>

                    <div v-if="hasActiveFilters" class="flex gap-2">
                        <v-btn size="small" variant="outlined" color="error" @click="clearFilters">
                            <v-icon start>mdi-close</v-icon>
                            Limpar Filtros
                        </v-btn>
                    </div>
                </div>
            </v-card-text>

            <v-divider></v-divider>

            <v-card-text class="p-0">
                <div v-if="consultations.length === 0" class="flex flex-col items-center justify-center py-12 text-center">
                    <v-icon size="64" class="mb-4 text-gray-300">mdi-calendar-blank-outline</v-icon>
                    <p class="text-lg text-gray-500">Sem consultas agendadas</p>
                </div>

                <div v-else class="divide-y">
                    <div
                        v-for="consultation in consultations"
                        :key="consultation.id"
                        class="cursor-pointer border-l-4 border-green-500 p-4 transition-colors hover:bg-gray-50"
                        @click="emit('edit', consultation.id)"
                    >
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="mb-2 flex items-center gap-2">
                                    <p class="font-semibold text-gray-900">{{ consultation.animal_name }}</p>
                                    <v-chip size="x-small" :color="getStateColor(consultation.state)" text-color="white">
                                        {{ consultation.state }}
                                    </v-chip>
                                    <v-chip size="x-small" color="secondary" text-color="white">
                                        {{ consultation.animal_type }}
                                    </v-chip>
                                </div>
                                <div class="mb-2 flex items-center gap-4 text-sm text-gray-600">
                                    <span><strong>Data:</strong> {{ formatDate(consultation.date) }}</span>
                                    <span><strong>Veterinário:</strong> {{ consultation.veterinarian_name }}</span>
                                </div>
                                <p v-if="consultation.veterinarian_note" class="text-sm text-gray-700">
                                    <strong>Notas:</strong> {{ truncateText(consultation.veterinarian_note, 80) }}
                                </p>
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
    </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue';

export interface Consultation {
    id: string;
    date: string;
    veterinarian_note: string;
    client_note: string;
    animal_name: string;
    animal_type: string;
    state: string;
    veterinarian_name: string;
    veterinarian_id: string;
}

export interface Pagination {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

export interface AnimalType {
    id: string;
    name: string;
}

interface Filters {
    consultation_day?: string;
    consultation_animal_id?: string;
    consultation_animal_type_id?: string;
}

const props = defineProps({
    consultations: {
        type: Array as () => Consultation[],
        default: () => [],
    },
    pagination: {
        type: Object as () => Pagination,
        required: true,
    },
    animalTypes: {
        type: Array as () => AnimalType[],
        default: () => [],
    },
});

const emit = defineEmits(['page-change', 'filter-change', 'edit']);

const localFilters = ref<Filters>({
    consultation_day: undefined,
    consultation_animal_id: undefined,
    consultation_animal_type_id: undefined,
});

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

const hasActiveFilters = computed(() => {
    return Object.values(localFilters.value).some((val) => val !== undefined && val !== '');
});

const applyFilters = () => {
    const filters: Filters = {};
    if (localFilters.value.consultation_day) filters.consultation_day = localFilters.value.consultation_day;
    if (localFilters.value.consultation_animal_id) filters.consultation_animal_id = localFilters.value.consultation_animal_id;
    if (localFilters.value.consultation_animal_type_id) filters.consultation_animal_type_id = localFilters.value.consultation_animal_type_id;

    emit('filter-change', filters);
};

const clearFilters = () => {
    localFilters.value = {
        consultation_day: undefined,
        consultation_animal_id: undefined,
        consultation_animal_type_id: undefined,
    };
    emit('filter-change', {});
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
        Completed: 'success',
        Pending: 'warning',
        Cancelled: 'error',
        Concluída: 'success',
        Pendente: 'warning',
        Cancelada: 'error',
        Atribuída: 'info',
    };
    return colors[state] || 'default';
};
</script>
