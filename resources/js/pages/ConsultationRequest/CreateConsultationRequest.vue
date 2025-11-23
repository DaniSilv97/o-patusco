<template>
    <AuthLayout v-if="authStore.isAuthenticated" title="Pedir Consulta">
        <BaseContainer>
            <PageHeader title="Criar pedido de consulta" description="Assim que o pedido for realizado, uma assistente confirmará o horário." />
            <ConsultationRequestForm
                :animals="animals"
                :animal-types="animalTypes"
                :timeframes="timeframes"
                :is-loading="form.processing"
                :errors="form.errors"
                @submit="handleFormSubmit"
                @cancel="handleCancel"
            />
        </BaseContainer>
    </AuthLayout>

    <GuestLayout v-else title="Pedir Consulta">
        <BaseContainer>
            <PageHeader title="Criar pedido de consulta" description="Assim que o pedido for realizado, uma assistente confirmará o horário." />
            <ConsultationRequestForm
                :animals="animals"
                :animal-types="animalTypes"
                :timeframes="timeframes"
                :is-loading="form.processing"
                :errors="form.errors"
                @submit="handleFormSubmit"
                @cancel="handleCancel"
            />
        </BaseContainer>
    </GuestLayout>
</template>

<script setup lang="ts">
import BaseContainer from '@/components/BaseContainer.vue';
import PageHeader from '@/components/PageHeader.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { useAuthStore } from '@/stores/useAuthStore';
import { useForm } from '@inertiajs/vue3';
import { computed, PropType } from 'vue';
import ConsultationRequestForm from './ConsultationRequestForm.vue';

export interface AnimalType {
    id: string;
    name: string;
}

export interface Animal {
    id: string;
    name: string;
    birthday: string;
    animalTypeId: string;
}

export interface Timeframe {
    id: string;
    name: string;
}

export interface ConsultationFormData {
    user: {
        id: string;
        name: string;
        email: string;
        birthday?: string;
    };
    animal: {
        id: string;
        name: string;
        birthday: string;
        animalTypeId: string;
    };
    date: string;
    timeframe: string;
    observations: string;
}

export interface FormErrors {
    [key: string]: string | undefined;
}

const props = defineProps({
    animals: {
        type: Array as PropType<Animal[]>,
        default: () => [],
    },
    animalTypes: {
        type: Array as PropType<AnimalType[]>,
        default: () => [],
    },
    timeframes: {
        type: Array as PropType<Timeframe[]>,
        required: true,
    },
});

const authStore = useAuthStore();

const animals = computed(() => props.animals);
const animalTypes = computed(() => props.animalTypes);
const timeframes = computed(() => props.timeframes);

const form = useForm({
    user: {
        id: '',
        name: '',
        email: '',
        birthday: '',
    },
    animal: {
        id: '',
        name: '',
        birthday: '',
        animalTypeId: '',
    },
    date: '',
    timeframe: '',
    observations: '',
});

const handleFormSubmit = (formData: ConsultationFormData) => {
    form.user = {
        id: formData.user.id,
        name: formData.user.name,
        email: formData.user.email,
        birthday: formData.user.birthday || '',
    };

    form.animal = {
        id: formData.animal.id,
        name: formData.animal.name,
        birthday: formData.animal.birthday,
        animalTypeId: formData.animal.animalTypeId,
    };

    form.date = formData.date;
    form.timeframe = formData.timeframe;
    form.observations = formData.observations;

    form.post(route('consultation.store'), {
        onSuccess: () => {
            console.log('Consultation request submitted successfully');
        },
        onError: () => {
            console.log('Error submitting consultation request', form.errors);
        },
    });
};

const handleCancel = () => {
    history.back();
};
</script>
