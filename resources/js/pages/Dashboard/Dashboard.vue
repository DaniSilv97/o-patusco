<template>
    <AuthLayout title="Dashboard">
        <BaseContainer>
            <div class="flex flex-col gap-6 p-8">
                <WelcomeSection :userName="user.name" />

                <v-divider></v-divider>

                <ConsultationsSection
                    :user-requests="userRequests"
                    :user-consultations="userConsultations"
                    @new-consultation="handleNewConsultation"
                    @list-consultations="handleListConsultations"
                    :requestCount="requestCount"
                    :consultationCount="consultationCount"
                />

                <AnimalsSection
                    :animals="userAnimals"
                    @view-details="handleViewAnimalDetails"
                    @list-animals="handleListAnimal"
                    :animalCount="animalCount"
                />
            </div>
        </BaseContainer>
    </AuthLayout>
</template>

<script setup lang="ts">
import BaseContainer from '@/components/BaseContainer.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { computed, PropType } from 'vue';
import AnimalsSection from './AnimalsSection.vue';
import ConsultationsSection from './ConsultationsSection.vue';
import WelcomeSection from './WelcomeSection.vue';

export interface Animal {
    id: string;
    name: string;
    animal_type: string;
    birthday: string;
}

export interface ConsultationRequest {
    id: string;
    animal_name: string;
    client_note: string;
    date: string;
    timeframe: string;
}

export interface Consultation {
    id: string;
    animal_name: string;
    client_note: string;
    date: string;
    state: string;
    veterinarian_note: string;
}

export interface DashboardCounts {
    animals: number;
    consultationRequests: number;
    consultations: number;
}

export interface ApiResponse<T> {
    data: T;
}

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
    animals: {
        type: Array as () => ApiResponse<Animal>[],
        default: () => [],
    },
    consultationRequests: {
        type: Array as () => ApiResponse<ConsultationRequest>[],
        default: () => [],
    },
    consultations: {
        type: Array as () => ApiResponse<Consultation>[],
        default: () => [],
    },
    counts: {
        type: Object as PropType<DashboardCounts>,
        default: () => ({
            animals: 0,
            consultationRequests: 0,
            consultations: 0,
        }),
    },
});

const user = computed(() => props.user);
const userAnimals = computed(() => props.animals.map((animal) => animal.data));
const userRequests = computed(() => props.consultationRequests.map((request) => request.data));
const userConsultations = computed(() => props.consultations.map((consultation) => consultation.data));
const animalCount = computed(() => props.counts.animals);
const requestCount = computed(() => props.counts.consultationRequests);
const consultationCount = computed(() => props.counts.consultations);

const handleNewConsultation = () => {
    console.log('Navigate to new consultation form');
    // TODO: Implement navigation
};

const handleListConsultations = () => {
    console.log('Navigate to new consultation form');
    // TODO: Implement navigation
};

const handleViewAnimalDetails = (animalId: string) => {
    console.log('View animal details:', animalId);
    // TODO: Implement navigation
};

const handleListAnimal = () => {
    console.log('Navigate to animal list');
    // TODO: Implement navigation
};
</script>
