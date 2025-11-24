<template>
    <AuthLayout title="Pedido de Consulta">
        <BaseContainer>
            <div class="flex flex-col gap-6">
                <div class="flex items-center justify-between">
                    <PageHeader title="Pedido de Consulta" :description="`Alterando pedido para ${requestData.data.animal_name}`" />
                    <Link :href="route('dashboard')">
                        <v-btn variant="outlined" prepend-icon="mdi-arrow-left"> Voltar </v-btn>
                    </Link>
                </div>

                <v-divider></v-divider>

                <div class="grid gap-8 lg:grid-cols-3">
                    <div class="lg:col-span-2">
                        <form @submit.prevent="submitForm" class="space-y-6">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Período Preferido *</label>
                                <v-select
                                    v-model="form.consultation_timeframe_id"
                                    :items="timeframes"
                                    item-title="name"
                                    item-value="id"
                                    placeholder="Selecione um período..."
                                    variant="outlined"
                                    density="compact"
                                    prepend-inner-icon="mdi-clock"
                                    :error="!!getErrorMessage('consultation_timeframe_id')"
                                    :error-messages="
                                        getErrorMessage('consultation_timeframe_id') ? [getErrorMessage('consultation_timeframe_id')] : []
                                    "
                                    @update:model-value="validateTimeframe"
                                    @blur="validateTimeframe"
                                ></v-select>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Observações do Cliente</label>
                                <v-textarea
                                    v-model="form.client_note"
                                    placeholder="Descreva qualquer detalhe ou preocupação relevante sobre seu animal..."
                                    variant="outlined"
                                    density="compact"
                                    prepend-inner-icon="mdi-note-text"
                                    rows="5"
                                    :error="!!getErrorMessage('client_note')"
                                    :error-messages="getErrorMessage('client_note') ? [getErrorMessage('client_note')] : []"
                                    counter
                                    maxlength="500"
                                ></v-textarea>
                            </div>

                            <v-divider class="my-6"></v-divider>

                            <div class="flex gap-4">
                                <v-btn type="submit" color="primary" class="flex-1" :loading="loading" :disabled="!hasChanges || loading">
                                    <v-icon icon="mdi-check" class="mr-2"></v-icon>
                                    Guardar Alterações
                                </v-btn>
                                <v-btn v-if="hasChanges" @click="resetForm" variant="outlined" color="warning" class="flex-1" :disabled="loading">
                                    <v-icon icon="mdi-refresh" class="mr-2"></v-icon>
                                    Descartar
                                </v-btn>
                                <Link :href="route('dashboard')">
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
                            <v-card-title class="text-base">Informações do Pedido</v-card-title>
                            <v-card-text class="space-y-3 text-sm text-gray-700">
                                <div class="flex justify-between">
                                    <span class="font-medium">Animal:</span>
                                    <span>{{ requestData.data.animal_name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Data Pedida:</span>
                                    <span>{{ formatDate(requestData.data.date) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Período Atual:</span>
                                    <span class="text-primary font-medium">{{ requestData.data.timeframe }}</span>
                                </div>
                            </v-card-text>
                        </v-card>

                        <v-card class="rounded-lg bg-amber-50 p-4">
                            <template v-slot:prepend>
                                <v-icon color="amber" icon="mdi-alert-circle"></v-icon>
                            </template>
                            <v-card-title class="text-base">Dicas para Edição</v-card-title>
                            <v-card-text class="space-y-3 text-sm text-gray-700">
                                <div class="flex gap-2">
                                    <v-icon icon="mdi-check-circle" color="green" size="small"></v-icon>
                                    <span>Altere os campos que deseja atualizar</span>
                                </div>
                                <div class="flex gap-2">
                                    <v-icon icon="mdi-check-circle" color="green" size="small"></v-icon>
                                    <span>Data e período são obrigatórios</span>
                                </div>
                                <div class="flex gap-2">
                                    <v-icon icon="mdi-check-circle" color="green" size="small"></v-icon>
                                    <span>Adicione observações para ajudar o veterinário</span>
                                </div>
                                <div class="flex gap-2">
                                    <v-icon icon="mdi-check-circle" color="green" size="small"></v-icon>
                                    <span>Clique em "Descartar" para reverter alterações</span>
                                </div>
                            </v-card-text>
                        </v-card>

                        <v-card class="rounded-lg border-l-4 border-red-500 bg-red-50 p-4">
                            <template v-slot:prepend>
                                <v-icon color="red" icon="mdi-alert"></v-icon>
                            </template>
                            <v-card-title class="text-base">Eliminar Pedido</v-card-title>
                            <v-card-text class="space-y-3 text-sm text-gray-700">
                                <p>Esta ação é permanente e não pode ser desfeita.</p>
                                <v-btn
                                    color="error"
                                    variant="outlined"
                                    size="small"
                                    :loading="deleteLoading"
                                    :disabled="deleteLoading || loading"
                                    @click="confirmDelete"
                                >
                                    <v-icon icon="mdi-delete" class="mr-2"></v-icon>
                                    Eliminar Pedido
                                </v-btn>
                            </v-card-text>
                        </v-card>

                        <v-dialog v-model="showDeleteDialog" max-width="400">
                            <v-card>
                                <v-card-title class="text-lg font-bold">Confirmar Eliminação</v-card-title>
                                <v-card-text class="text-base">
                                    Tem a certeza que deseja eliminar este pedido de consulta para <strong>{{ requestData.data.animal_name }}</strong
                                    >? Esta ação não pode ser desfeita.
                                </v-card-text>
                                <v-card-actions class="flex justify-end gap-2">
                                    <v-btn variant="outlined" @click="showDeleteDialog = false" :disabled="deleteLoading"> Cancelar </v-btn>
                                    <v-btn color="error" @click="deleteRequest" :loading="deleteLoading"> Eliminar </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
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
import { Link, router, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

interface TimeFrame {
    id: string;
    name: string;
}

interface ConsultationRequestData {
    data: {
        id: string;
        date: string;
        client_note: string | null;
        animal_name: string;
        timeframe: string;
        created_at?: string;
    };
}

interface Props {
    consultationRequest: ConsultationRequestData;
    timeframes: TimeFrame[];
}

const props = defineProps<Props>();

const loading = computed(() => form.processing);
const deleteLoading = ref(false);
const showDeleteDialog = ref(false);

const findTimeframeId = (timeframeName: string): string => {
    const timeframe = props.timeframes.find((t) => t.name === timeframeName);
    return timeframe ? timeframe.id : '';
};

const form = useForm({
    date: props.consultationRequest.data.date,
    consultation_timeframe_id: findTimeframeId(props.consultationRequest.data.timeframe),
    client_note: props.consultationRequest.data.client_note || '',
});

const originalForm = ref({
    date: props.consultationRequest.data.date,
    consultation_timeframe_id: findTimeframeId(props.consultationRequest.data.timeframe),
    client_note: props.consultationRequest.data.client_note || '',
});

const requestData = computed(() => props.consultationRequest);

const hasChanges = computed(() => {
    return (
        form.date !== originalForm.value.date ||
        form.consultation_timeframe_id !== originalForm.value.consultation_timeframe_id ||
        form.client_note !== originalForm.value.client_note
    );
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

const validateDate = () => {
    clearError('date');
    const date = form.date;

    if (!date) {
        setError('date', 'Data é obrigatória');
    } else {
        try {
            const selectedDate = new Date(date);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (selectedDate < today) {
                setError('date', 'Data não pode ser no passado');
            }
        } catch {
            setError('date', 'Data inválida');
        }
    }
};

const validateTimeframe = () => {
    clearError('consultation_timeframe_id');

    if (!form.consultation_timeframe_id) {
        setError('consultation_timeframe_id', 'Período é obrigatório');
    }
};

const validateForm = (): boolean => {
    validateDate();
    validateTimeframe();

    return !form.hasErrors;
};

const resetForm = () => {
    form.date = originalForm.value.date;
    form.consultation_timeframe_id = originalForm.value.consultation_timeframe_id;
    form.client_note = originalForm.value.client_note;
    form.clearErrors();
};

const submitForm = () => {
    if (!validateForm()) {
        return;
    }

    form.put(route('consultation.update', requestData.value.data.id), {
        onSuccess: () => {
            Object.assign(originalForm.value, {
                date: form.date,
                consultation_timeframe_id: form.consultation_timeframe_id,
                client_note: form.client_note,
            });
        },
    });
};

const confirmDelete = () => {
    showDeleteDialog.value = true;
};

const deleteRequest = () => {
    deleteLoading.value = true;

    router.delete(route('consultation.delete', requestData.value.data.id), {
        onSuccess: () => {
            deleteLoading.value = false;
            showDeleteDialog.value = false;
        },
        onError: () => {
            deleteLoading.value = false;
            showDeleteDialog.value = false;
        },
    });
};

const formatDate = (date: string): string => {
    try {
        const dateObj = new Date(date);
        const day = String(dateObj.getDate()).padStart(2, '0');
        const month = String(dateObj.getMonth() + 1).padStart(2, '0');
        const year = dateObj.getFullYear();
        return `${day} - ${month} - ${year}`;
    } catch {
        return 'Data inválida';
    }
};
</script>
