<template>
    <AuthLayout title="Criar Consulta">
        <BaseContainer>
            <div class="flex flex-col gap-6">
                <div class="flex items-center justify-between">
                    <PageHeader title="Atribuir Consulta" :description="`Convertendo pedido para ${consultationRequest.data.animal_name}`" />
                    <Link :href="route('receptionist.dashboard')">
                        <v-btn variant="outlined" prepend-icon="mdi-arrow-left"> Voltar </v-btn>
                    </Link>
                </div>

                <v-divider></v-divider>

                <div class="grid gap-8 lg:grid-cols-3">
                    <div class="lg:col-span-2">
                        <form @submit.prevent="submitForm" class="space-y-6">
                            <!-- Requested Date (Read-only) -->
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Data Solicitada pelo Cliente</label>
                                <div class="rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900">
                                    {{ formatDate(consultationRequest.data.date) }}
                                </div>
                            </div>

                            <!-- Time Selection -->
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">Data da Consulta *</label>
                                    <v-text-field
                                        v-model="form.consultation_date"
                                        type="date"
                                        placeholder="Selecione uma data..."
                                        variant="outlined"
                                        density="compact"
                                        prepend-inner-icon="mdi-calendar"
                                        :error="!!getErrorMessage('consultation_date')"
                                        :error-messages="getErrorMessage('consultation_date') ? [getErrorMessage('consultation_date')] : []"
                                        @update:model-value="onDateChange"
                                        @blur="validateConsultationDate"
                                    ></v-text-field>
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">Hora da Consulta *</label>
                                    <v-select
                                        v-model="form.consultation_time"
                                        :items="availableTimes"
                                        placeholder="Selecione uma hora..."
                                        variant="outlined"
                                        density="compact"
                                        prepend-inner-icon="mdi-clock"
                                        :error="!!getErrorMessage('consultation_time')"
                                        :error-messages="getErrorMessage('consultation_time') ? [getErrorMessage('consultation_time')] : []"
                                        @update:model-value="validateConsultationTime"
                                        @blur="validateConsultationTime"
                                    ></v-select>
                                </div>
                            </div>

                            <!-- Veterinarian Selection -->
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Veterinário *</label>
                                <v-select
                                    v-model="form.veterinarian_id"
                                    :items="veterinarians"
                                    item-title="name"
                                    item-value="id"
                                    placeholder="Selecione um veterinário..."
                                    variant="outlined"
                                    density="compact"
                                    prepend-inner-icon="mdi-hospital-box"
                                    :error="!!getErrorMessage('veterinarian_id')"
                                    :error-messages="getErrorMessage('veterinarian_id') ? [getErrorMessage('veterinarian_id')] : []"
                                    @update:model-value="onVeterinarianChange"
                                    @blur="validateVeterinarian"
                                ></v-select>
                            </div>

                            <!-- Client Notes (Read-only) -->
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Observações do Cliente</label>
                                <div class="rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900">
                                    <p v-if="consultationRequest.data.client_note" class="text-sm whitespace-pre-wrap">
                                        {{ consultationRequest.data.client_note }}
                                    </p>
                                    <p v-else class="text-sm text-gray-500 italic">Sem observações do cliente</p>
                                </div>
                            </div>

                            <v-divider class="my-6"></v-divider>

                            <div class="flex gap-4">
                                <v-btn type="submit" color="primary" class="flex-1" :loading="loading" :disabled="!isFormValid || loading">
                                    <v-icon icon="mdi-check" class="mr-2"></v-icon>
                                    Criar Consulta
                                </v-btn>
                                <Link :href="route('receptionist.dashboard')">
                                    <v-btn variant="outlined" class="flex-1">
                                        <v-icon icon="mdi-close" class="mr-2"></v-icon>
                                        Cancelar
                                    </v-btn>
                                </Link>
                            </div>
                        </form>
                    </div>

                    <!-- Right Sidebar -->
                    <div class="flex flex-col gap-4">
                        <!-- Request Information Card -->
                        <v-card class="rounded-lg bg-blue-50 p-4">
                            <template v-slot:prepend>
                                <v-icon color="blue" icon="mdi-information"></v-icon>
                            </template>
                            <v-card-title class="text-base">Informações do Pedido</v-card-title>
                            <v-card-text class="space-y-3 text-sm text-gray-700">
                                <div class="flex justify-between">
                                    <span class="font-medium">Animal:</span>
                                    <span>{{ consultationRequest.data.animal_name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Tipo:</span>
                                    <span>{{ consultationRequest.data.animal_type }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Data Pedida:</span>
                                    <span>{{ formatDate(consultationRequest.data.date) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Período:</span>
                                    <span class="text-primary font-medium">{{ consultationRequest.data.timeframe }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">Data Criação:</span>
                                    <span class="text-xs text-gray-500">{{ formatDateTime(consultationRequest.data.created_at) }}</span>
                                </div>
                            </v-card-text>
                        </v-card>

                        <!-- Schedule Rules Card -->
                        <v-card class="rounded-lg bg-purple-50 p-4">
                            <template v-slot:prepend>
                                <v-icon color="purple" icon="mdi-clock-check"></v-icon>
                            </template>
                            <v-card-title class="text-base">Horários Disponíveis</v-card-title>
                            <v-card-text class="space-y-2 text-sm text-gray-700">
                                <div class="flex items-center gap-2">
                                    <v-icon icon="mdi-check-circle" color="green" size="small"></v-icon>
                                    <span>Horários: 08:00h - 17:30h</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <v-icon icon="mdi-check-circle" color="green" size="small"></v-icon>
                                    <span>Intervalos de 30 minutos</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <v-icon icon="mdi-check-circle" color="green" size="small"></v-icon>
                                    <span>Sem conflitos de horário</span>
                                </div>
                                <v-divider class="my-3"></v-divider>
                                <div v-if="selectedVeterinarianConflicts.length > 0" class="mt-3">
                                    <p class="mb-2 font-medium text-red-700">Horários Ocupados:</p>
                                    <div class="space-y-1">
                                        <div
                                            v-for="(conflict, idx) in selectedVeterinarianConflicts"
                                            :key="idx"
                                            class="flex items-center gap-1 text-xs text-red-600"
                                        >
                                            <v-icon icon="mdi-close-circle" size="x-small"></v-icon>
                                            {{ conflict }}
                                        </div>
                                    </div>
                                </div>
                                <div v-else-if="form.consultation_date && form.veterinarian_id" class="mt-3">
                                    <p class="flex items-center gap-1 text-sm font-medium text-green-700">
                                        <v-icon icon="mdi-check" size="small"></v-icon>
                                        Veterinário disponível nesta data
                                    </p>
                                </div>
                            </v-card-text>
                        </v-card>

                        <!-- Tips Card -->
                        <v-card class="rounded-lg bg-amber-50 p-4">
                            <template v-slot:prepend>
                                <v-icon color="amber" icon="mdi-lightbulb"></v-icon>
                            </template>
                            <v-card-title class="text-base">Dicas para Agendamento</v-card-title>
                            <v-card-text class="space-y-3 text-sm text-gray-700">
                                <div class="flex gap-2">
                                    <v-icon icon="mdi-check-circle" color="green" size="small"></v-icon>
                                    <span>Selecione uma data e hora disponível</span>
                                </div>
                                <div class="flex gap-2">
                                    <v-icon icon="mdi-check-circle" color="green" size="small"></v-icon>
                                    <span>Escolha um veterinário sem conflitos</span>
                                </div>
                                <div class="flex gap-2">
                                    <v-icon icon="mdi-check-circle" color="green" size="small"></v-icon>
                                    <span>Horários válidos: 08:00h até 17:30h</span>
                                </div>
                                <div class="flex gap-2">
                                    <v-icon icon="mdi-check-circle" color="green" size="small"></v-icon>
                                    <span>Todos os campos são obrigatórios</span>
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
import { computed, onMounted, ref } from 'vue';

interface Veterinarian {
    id: string;
    name: string;
}

interface ConsultationRequestData {
    data: {
        id: string;
        date: string;
        client_note: string | null;
        animal_name: string;
        animal_type: string;
        timeframe: string;
        created_at?: string;
    };
}

interface Conflict {
    veterinarian_id: string;
    date: string;
    time: string;
}

interface Props {
    consultationRequest: ConsultationRequestData;
    veterinarians: Veterinarian[];
    conflicts: Conflict[];
}

const props = defineProps<Props>();

const loading = computed(() => form.processing);

const form = useForm({
    consultation_date: '',
    consultation_time: '',
    veterinarian_id: '',
});

const errors = ref<Record<string, string>>({});

const availableTimes = computed(() => {
    const times: string[] = [];
    const startHour = 8;
    const endHour = 17;
    const endMinute = 30;

    for (let hour = startHour; hour <= endHour; hour++) {
        for (let minute = 0; minute < 60; minute += 30) {
            if (hour === endHour && minute > endMinute) break;
            const timeStr = `${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')}`;
            times.push(timeStr);
        }
    }

    return times;
});

const selectedVeterinarianConflicts = computed(() => {
    if (!form.consultation_date || !form.veterinarian_id) {
        return [];
    }

    return props.conflicts.filter((c) => c.veterinarian_id === form.veterinarian_id && c.date === form.consultation_date).map((c) => c.time);
});

const isFormValid = computed(() => {
    return (
        form.consultation_date &&
        form.consultation_time &&
        form.veterinarian_id &&
        !selectedVeterinarianConflicts.value.includes(form.consultation_time) &&
        Object.keys(errors.value).length === 0
    );
});

const getErrorMessage = (field: string): string => {
    const keys = field.split('.');
    let current: any = errors.value;

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
    const topLevelKey = keys[0];
    if (errors.value[topLevelKey]) {
        delete errors.value[topLevelKey];
    }
};

const setError = (field: string, message: string) => {
    errors.value[field] = message;
};

const validateConsultationDate = () => {
    clearError('consultation_date');

    if (!form.consultation_date) {
        setError('consultation_date', 'Data é obrigatória');
    } else {
        try {
            const selectedDate = new Date(form.consultation_date);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (selectedDate < today) {
                setError('consultation_date', 'Data não pode ser no passado');
            }
        } catch {
            setError('consultation_date', 'Data inválida');
        }
    }
};

const validateConsultationTime = () => {
    clearError('consultation_time');

    if (!form.consultation_time) {
        setError('consultation_time', 'Hora é obrigatória');
    } else if (selectedVeterinarianConflicts.value.includes(form.consultation_time)) {
        setError('consultation_time', 'Este horário já está ocupado para o veterinário selecionado');
    }
};

const validateVeterinarian = () => {
    clearError('veterinarian_id');

    if (!form.veterinarian_id) {
        setError('veterinarian_id', 'Veterinário é obrigatório');
    }
};

const onDateChange = () => {
    validateConsultationDate();
    // Reset time if it conflicts
    if (form.consultation_time && selectedVeterinarianConflicts.value.includes(form.consultation_time)) {
        form.consultation_time = '';
    }
};

const onVeterinarianChange = () => {
    validateVeterinarian();
    // Reset time if it conflicts with new veterinarian
    if (form.consultation_time && selectedVeterinarianConflicts.value.includes(form.consultation_time)) {
        form.consultation_time = '';
        setError('consultation_time', 'Este horário não está disponível para o veterinário selecionado');
    }
};

const validateForm = (): boolean => {
    validateConsultationDate();
    validateConsultationTime();
    validateVeterinarian();

    return Object.keys(errors.value).length === 0;
};

const submitForm = () => {
    if (!validateForm()) {
        return;
    }

    form.post(route('receptionist.consultation.create', props.consultationRequest.data.id));
};

const formatDate = (date: string): string => {
    try {
        const dateObj = new Date(date);
        const day = String(dateObj.getDate()).padStart(2, '0');
        const month = String(dateObj.getMonth() + 1).padStart(2, '0');
        const year = dateObj.getFullYear();
        return `${day}/${month}/${year}`;
    } catch {
        return 'Data inválida';
    }
};

const formatDateTime = (date: string | undefined): string => {
    if (!date) return 'N/A';
    try {
        const dateObj = new Date(date);
        const day = String(dateObj.getDate()).padStart(2, '0');
        const month = String(dateObj.getMonth() + 1).padStart(2, '0');
        const year = dateObj.getFullYear();
        const hours = String(dateObj.getHours()).padStart(2, '0');
        const minutes = String(dateObj.getMinutes()).padStart(2, '0');
        return `${day}/${month}/${year} ${hours}:${minutes}`;
    } catch {
        return 'Data inválida';
    }
};

onMounted(() => {
    console.log(props);
});
</script>
