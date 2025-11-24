<template>
    <AuthLayout title="Dashboard">
        <BaseContainer>
            <div class="space-y-6 p-6">
                <!-- Header -->
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Painel de Recepção</h1>
                        <p class="mt-2 text-gray-600">Gerencie pedidos de consulta e consultas agendadas</p>
                    </div>
                    <v-icon size="48" class="text-primary">mdi-hospital-box</v-icon>
                </div>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <ConsultationRequestsSection
                        :consultation-requests="consultationRequests.data"
                        :pagination="consultationRequests.meta"
                        @page-change="handleConsultationRequestPageChange"
                        @edit="handleEditConsultationRequest"
                    />

                    <ConsultationsSection
                        :consultations="consultations.data"
                        :pagination="consultations.meta"
                        @page-change="handleConsultationPageChange"
                        @edit="handleEditConsultation"
                    />
                </div>
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
    timeframe: string;
}

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
    data: any[];
    meta: Pagination;
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
});

const currentConsultationRequestPage = ref(1);
const currentConsultationPage = ref(1);

const handleConsultationRequestPageChange = (page: number) => {
    currentConsultationRequestPage.value = page;
    updatePage();
};

const handleConsultationPageChange = (page: number) => {
    currentConsultationPage.value = page;
    updatePage();
};

const updatePage = () => {
    router.get(
        route('receptionist.dashboard'),
        {
            consultation_request_page: currentConsultationRequestPage.value,
            consultation_page: currentConsultationPage.value,
        },
        {
            preserveState: true,
            replace: true,
        },
    );
};

const handleEditConsultationRequest = (id: string) => {
    router.visit(route('consultations.show', id));
};

const handleEditConsultation = (id: string) => {
    router.visit(route('consultations.show', id));
};
</script>
