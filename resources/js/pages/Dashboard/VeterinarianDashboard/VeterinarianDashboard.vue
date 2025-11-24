<template>
    <AuthLayout title="Dashboard">
        <BaseContainer>
            <div class="space-y-6 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Painel do Veterinário</h1>
                        <p class="mt-2 text-gray-600">Gerencie suas consultas agendadas</p>
                    </div>
                    <v-icon size="48" class="text-primary">mdi-stethoscope</v-icon>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <v-card class="shadow-md">
                        <v-card-text class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Total de Consultas</p>
                                <p class="text-3xl font-bold text-gray-900">{{ allConsultations.length }}</p>
                            </div>
                            <v-icon size="40" class="text-primary">mdi-calendar-check</v-icon>
                        </v-card-text>
                    </v-card>

                    <v-card class="shadow-md">
                        <v-card-text class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Atribuídas</p>
                                <p class="text-3xl font-bold text-blue-600">{{ countByState('Atribuída') }}</p>
                            </div>
                            <v-icon size="40" class="text-blue-500">mdi-calendar-plus</v-icon>
                        </v-card-text>
                    </v-card>

                    <v-card class="shadow-md">
                        <v-card-text class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Concluídas</p>
                                <p class="text-3xl font-bold text-green-600">{{ countByState('Completa') }}</p>
                            </div>
                            <v-icon size="40" class="text-green-500">mdi-check-circle-outline</v-icon>
                        </v-card-text>
                    </v-card>
                </div>

                <ConsultationsSection
                    :consultations="consultations.data"
                    :pagination="consultations.meta"
                    :state-filter="stateFilter"
                    :all-consultations="allConsultations"
                    @page-change="handleConsultationPageChange"
                    @filter-change="handleFilterChange"
                    @edit="handleEditConsultation"
                />
            </div>
        </BaseContainer>
    </AuthLayout>
</template>

<script setup lang="ts">
import BaseContainer from '@/components/BaseContainer.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import ConsultationsSection from './ConsultationsSection.vue';

export interface Consultation {
    id: string;
    date: string;
    veterinarian_note: string;
    client_note: string;
    animal_name: string;
    state: string;
}

export interface Pagination {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

interface PaginatedResponse {
    data: Consultation[];
    meta: Pagination;
}

const props = defineProps<{
    consultations: PaginatedResponse;
    stateFilter: string | null;
    allConsultations: Consultation[];
}>();

const currentConsultationPage = ref(1);

const handleConsultationPageChange = (page: number) => {
    currentConsultationPage.value = page;
    updatePage();
};

const handleFilterChange = (state: string | null) => {
    currentConsultationPage.value = 1;
    updatePage(state);
};

const updatePage = (state: string | null = null) => {
    const params: any = {
        consultation_page: currentConsultationPage.value,
    };

    if (state && state !== 'all') {
        params.state_filter = state;
    }

    router.get(route('veterinarian.dashboard'), params, {
        preserveState: true,
        replace: true,
    });
};

const handleEditConsultation = (id: string) => {
    router.visit(route('consultations.show', id));
};

const countByState = (state: string): number => {
    return props.allConsultations.filter((consultation: Consultation) => consultation.state === state).length;
};
</script>
