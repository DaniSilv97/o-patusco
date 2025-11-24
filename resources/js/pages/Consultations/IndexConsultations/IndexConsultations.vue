<template>
    <AuthLayout title="Consultas">
        <BaseContainer>
            <div class="space-y-6 p-6">
                <!-- Header -->
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Minhas Consultas</h1>
                        <p class="mt-2 text-gray-600">Acompanhe seus pedidos de consulta e consultas agendadas</p>
                    </div>
                    <v-icon size="48" class="text-primary">mdi-stethoscope</v-icon>
                </div>

                <ConsultationRequestsSection
                    :consultation-requests="consultationRequests.data"
                    :pagination="consultationRequests.meta"
                    :animal-types="animalTypes"
                    :animals="animals"
                    @page-change="handleConsultationRequestPageChange"
                    @filter-change="handleConsultationRequestFilterChange"
                    @edit="handleViewConsultationRequest"
                />

                <ConsultationsSection
                    :consultations="consultations.data"
                    :pagination="consultations.meta"
                    :animal-types="animalTypes"
                    :animals="animals"
                    @page-change="handleConsultationPageChange"
                    @filter-change="handleConsultationFilterChange"
                    @edit="handleViewConsultation"
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
import ConsultationRequestsSection from './ConsultationRequestsSection.vue';
import ConsultationsSection from './ConsultationsSection.vue';

export interface ConsultationRequest {
    id: string;
    date: string;
    client_note: string;
    animal_name: string;
    animal_type: string;
    timeframe: string;
}

export interface Consultation {
    id: string;
    date: string;
    veterinarian_note: string;
    client_note: string;
    animal_name: string;
    animal_type: string;
    state: string;
}

export interface Pagination {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

export interface Animal {
    id: string;
    name: string;
}

export interface AnimalType {
    id: string;
    name: string;
}

interface PaginatedResponse {
    data: any[];
    meta: Pagination;
}

interface ConsultationRequestFilters {
    day?: string;
    animal_type_id?: string;
}

interface ConsultationFilters {
    consultation_day?: string;
    consultation_animal_id?: string;
    consultation_animal_type_id?: string;
}

defineProps({
    consultationRequests: {
        type: Object as () => PaginatedResponse,
        required: true,
    },
    consultations: {
        type: Object as () => PaginatedResponse,
        required: true,
    },
    animals: {
        type: Array as () => Animal[],
        default: () => [],
    },
    animalTypes: {
        type: Array as () => AnimalType[],
        default: () => [],
    },
});

const currentConsultationRequestPage = ref(1);
const currentConsultationPage = ref(1);
const consultationRequestFilters = ref<ConsultationRequestFilters>({});
const consultationFilters = ref<ConsultationFilters>({});

const handleConsultationRequestPageChange = (page: number) => {
    currentConsultationRequestPage.value = page;
    updatePage();
};

const handleConsultationRequestFilterChange = (filters: ConsultationRequestFilters) => {
    consultationRequestFilters.value = filters;
    currentConsultationRequestPage.value = 1;
    updatePage();
};

const handleConsultationPageChange = (page: number) => {
    currentConsultationPage.value = page;
    updatePage();
};

const handleConsultationFilterChange = (filters: ConsultationFilters) => {
    consultationFilters.value = filters;
    currentConsultationPage.value = 1;
    updatePage();
};

const updatePage = () => {
    router.get(
        route('consultations'),
        {
            consultation_request_page: currentConsultationRequestPage.value,
            consultation_page: currentConsultationPage.value,
            ...consultationRequestFilters.value,
            ...consultationFilters.value,
        },
        {
            preserveState: true,
            replace: true,
        },
    );
};

const handleViewConsultationRequest = (id: string) => {
    router.visit(route('consultations.show', id));
};

const handleViewConsultation = (id: string) => {
    router.visit(route('consultations.show', id));
};
</script>
