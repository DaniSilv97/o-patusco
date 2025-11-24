<template>
    <AuthLayout title="Consulta">
        <BaseContainer>
            <div class="flex flex-col gap-6">
                <div class="flex items-center justify-between">
                    <PageHeader title="Consulta" :description="`Consulta para ${consultation.data.animal_name}`" />
                    <Link :href="route('veterinarian.dashboard')">
                        <v-btn variant="outlined" prepend-icon="mdi-arrow-left"> Voltar </v-btn>
                    </Link>
                </div>

                <v-divider></v-divider>

                <div class="grid gap-8 lg:grid-cols-3">
                    <div class="lg:col-span-2">
                        <form @submit.prevent="submitForm" class="space-y-6">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Data da Consulta</label>
                                <div class="rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900">
                                    {{ formatDate(consultation.data.date) }}
                                </div>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Estado da Consulta *</label>
                                <v-select
                                    v-model="form.state_id"
                                    :items="states"
                                    item-title="name"
                                    item-value="id"
                                    placeholder="Selecione um estado..."
                                    variant="outlined"
                                    density="compact"
                                    prepend-inner-icon="mdi-information"
                                    :error="!!getErrorMessage('state_id')"
                                    :error-messages="getErrorMessage('state_id') ? [getErrorMessage('state_id')] : []"
                                    @update:model-value="validateState"
                                    @blur="validateState"
                                ></v-select>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Observações do Cliente</label>
                                <div class="rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900">
                                    <p v-if="consultation.data.client_note" class="whitespace-pre-wrap">
                                        {{ consultation.data.client_note }}
                                    </p>
                                    <p v-else class="text-gray-500 italic">Sem observações do cliente</p>
                                </div>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Observações do Veterinário *</label>
                                <v-textarea
                                    v-model="form.veterinarian_note"
                                    placeholder="Adicione as observações ou diagnóstico da consulta..."
                                    variant="outlined"
                                    density="compact"
                                    prepend-inner-icon="mdi-note-text"
                                    rows="6"
                                    :error="!!getErrorMessage('veterinarian_note')"
                                    :error-messages="getErrorMessage('veterinarian_note') ? [getErrorMessage('veterinarian_note')] : []"
                                    counter
                                    maxlength="255"
                                    @input="validateVeterinarianNote"
                                    @blur="validateVeterinarianNote"
                                ></v-textarea>
                            </div>

                            <v-divider class="my-6"></v-divider>

                            <div class="flex gap-4">
                                <v-btn type="submit" color="primary" class="flex-1" :loading="loading" :disabled="!hasChanges || loading">
                                    <v-icon icon="mdi-check" class="mr-2"></v-icon>
                                    Guardar Observações
                                </v-btn>
                                <v-btn v-if="hasChanges" @click="resetForm" variant="outlined" color="warning" class="flex-1" :disabled="loading">
                                    <v-icon icon="mdi-refresh" class="mr-2"></v-icon>
                                    Descartar
                                </v-btn>
                                <Link :href="route('veterinarian.dashboard')">
                                    <v-btn variant="outlined" class="flex-1">
                                        <v-icon icon="mdi-close" class="mr-2"></v-icon>
                                        Voltar
                                    </v-btn>
                                </Link>
                            </div>
                        </form>
                    </div>

                    <div class="flex flex-col gap-4">
                        <v-card class="rounded-lg bg-blue-50 p-4">
                            <template v-slot:prepend>
                                <v-icon color="blue" icon="mdi-information"></v-icon>
                            </template>
                            <v-card-title class="text-base">Informações da Consulta</v-card-title>
                            <v-card-text class="space-y-3 text-sm text-gray-700">
                                <div class="flex justify-between">
                                    <span class="font-medium">Animal:</span>
                                    <span>{{ consultation.data.animal_name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Data:</span>
                                    <span>{{ formatDate(consultation.data.date) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Estado:</span>
                                    <v-chip :color="getStateColor(consultation.data.state)" text-color="white" size="small">
                                        {{ consultation.data.state }}
                                    </v-chip>
                                </div>
                            </v-card-text>
                        </v-card>

                        <v-card class="rounded-lg bg-green-50 p-4">
                            <template v-slot:prepend>
                                <v-icon color="green" icon="mdi-file-check"></v-icon>
                            </template>
                            <v-card-title class="text-base">Detalhes do Paciente</v-card-title>
                            <v-card-text class="space-y-3 text-sm text-gray-700">
                                <div class="flex items-center gap-2">
                                    <v-icon icon="mdi-paw" color="green" size="small"></v-icon>
                                    <span class="font-medium">{{ consultation.data.animal_name }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <v-icon icon="mdi-calendar" color="green" size="small"></v-icon>
                                    <span>{{ formatDate(consultation.data.date) }}</span>
                                </div>
                                <div v-if="consultation.data.client_note" class="flex items-center gap-2">
                                    <v-icon icon="mdi-note-outline" color="green" size="small"></v-icon>
                                    <span>Cliente forneceu observações</span>
                                </div>
                            </v-card-text>
                        </v-card>

                        <v-card class="rounded-lg bg-amber-50 p-4">
                            <template v-slot:prepend>
                                <v-icon color="amber" icon="mdi-lightbulb"></v-icon>
                            </template>
                            <v-card-title class="text-base">Dicas</v-card-title>
                            <v-card-text class="space-y-3 text-sm text-gray-700">
                                <div class="flex gap-2">
                                    <v-icon icon="mdi-check-circle" color="green" size="small"></v-icon>
                                    <span>Adicione observações detalhadas sobre o diagnóstico</span>
                                </div>
                                <div class="flex gap-2">
                                    <v-icon icon="mdi-check-circle" color="green" size="small"></v-icon>
                                    <span>Inclua recomendações de tratamento</span>
                                </div>
                                <div class="flex gap-2">
                                    <v-icon icon="mdi-check-circle" color="green" size="small"></v-icon>
                                    <span>Descreva qualquer achado relevante</span>
                                </div>
                                <div class="flex gap-2">
                                    <v-icon icon="mdi-check-circle" color="green" size="small"></v-icon>
                                    <span>Máximo de 255 caracteres para as observações</span>
                                </div>
                            </v-card-text>
                        </v-card>
                    </div>
                </div>
            </div>
        </BaseContainer>
    </AuthLayout>
</template>

<script setup lang="ts">
import BaseContainer from '@/components/BaseContainer.vue';
import PageHeader from '@/components/PageHeader.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

interface State {
    id: string;
    name: string;
}

interface ConsultationData {
    data: {
        id: string;
        date: string;
        veterinarian_note: string | null;
        client_note: string | null;
        animal_name: string;
        state: string;
        state_id: string;
    };
}

interface Props {
    consultation: ConsultationData;
    states: State[];
}

const props = defineProps<Props>();

const loading = computed(() => form.processing);

const form = useForm({
    state_id: props.consultation.data.state_id,
    veterinarian_note: props.consultation.data.veterinarian_note || '',
});

const originalForm = ref({
    state_id: props.consultation.data.state_id,
    veterinarian_note: props.consultation.data.veterinarian_note || '',
});

const consultation = computed(() => props.consultation);

const hasChanges = computed(() => {
    return form.state_id !== originalForm.value.state_id || form.veterinarian_note !== originalForm.value.veterinarian_note;
});

const getErrorMessage = (field: string): string => {
    const keys = field.split('.');
    let current: any = form.errors;

    for (const key of keys) {
        if (!current || typeof current !== 'object') {
            return '';
        }
        current = current[key];
    }

    return typeof current === 'string' ? current : '';
};

const clearError = (field: string) => {
    const keys = field.split('.');
    const topLevelKey = keys[0] as keyof typeof form.errors;
    form.clearErrors(topLevelKey);
};

const setError = (field: string, message: string) => {
    const keys = field.split('.');
    const topLevelKey = keys[0] as keyof typeof form.errors;

    if (keys.length === 1) {
        form.setError(topLevelKey, message);
    } else {
        const currentError = form.errors[topLevelKey];
        const errorObj = typeof currentError === 'object' && currentError !== null ? (currentError as Record<string, string>) : {};
        (form.errors[topLevelKey] as any) = { ...errorObj, [field]: message };
    }
};

const validateState = () => {
    clearError('state_id');

    if (!form.state_id) {
        setError('state_id', 'Estado da consulta é obrigatório');
    }
};

const validateVeterinarianNote = () => {
    clearError('veterinarian_note');

    if (!form.veterinarian_note || !form.veterinarian_note.trim()) {
        setError('veterinarian_note', 'Observações do veterinário são obrigatórias');
    } else if (form.veterinarian_note.length > 255) {
        setError('veterinarian_note', 'Observações não podem exceder 255 caracteres');
    }
};

const validateForm = (): boolean => {
    validateState();
    validateVeterinarianNote();
    return !form.hasErrors;
};

const resetForm = () => {
    form.state_id = originalForm.value.state_id;
    form.veterinarian_note = originalForm.value.veterinarian_note;
    form.clearErrors();
};

const submitForm = () => {
    if (!validateForm()) {
        return;
    }

    form.put(route('veterinarian.consultation.update', consultation.value.data.id), {
        onSuccess: () => {
            Object.assign(originalForm.value, {
                state_id: form.state_id,
                veterinarian_note: form.veterinarian_note,
            });
        },
    });
};

const formatDate = (date: string): string => {
    try {
        const dateObj = new Date(date);
        const day = String(dateObj.getDate()).padStart(2, '0');
        const month = String(dateObj.getMonth() + 1).padStart(2, '0');
        const year = dateObj.getFullYear();
        const hours = String(dateObj.getHours()).padStart(2, '0');
        const minutes = String(dateObj.getMinutes()).padStart(2, '0');
        return `${day}-${month}-${year} ${hours}:${minutes}`;
    } catch {
        return 'Data inválida';
    }
};

const getStateColor = (state: string): string => {
    const stateColors: Record<string, string> = {
        Atribuída: 'warning',
        Completa: 'success',
        Cancelada: 'error',
    };
    return stateColors[state] || 'gray';
};
</script>
