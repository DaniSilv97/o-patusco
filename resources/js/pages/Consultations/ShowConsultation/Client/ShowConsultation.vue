<template>
    <AuthLayout title="Consulta">
        <BaseContainer>
            <div class="flex flex-col gap-6">
                <div class="flex items-center justify-between">
                    <PageHeader title="Consulta" :description="`Consulta para, ${consultation.data.animal_name}`" />
                    <Link :href="route('dashboard')">
                        <v-btn variant="outlined" prepend-icon="mdi-arrow-left"> Voltar </v-btn>
                    </Link>
                </div>

                <v-divider></v-divider>

                <div class="grid gap-8 lg:grid-cols-3">
                    <div class="lg:col-span-2">
                        <div class="space-y-6">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Data da Consulta</label>
                                <div class="rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900">
                                    {{ formatDate(consultation.data.date) }}
                                </div>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Veterinário</label>
                                <div class="rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900">
                                    {{ veterinarian }}
                                </div>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Observações do Veterinário</label>
                                <div class="rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900">
                                    <p v-if="consultation.data.veterinarian_note" class="whitespace-pre-wrap">
                                        {{ consultation.data.veterinarian_note }}
                                    </p>
                                    <p v-else class="text-gray-500 italic">Sem observações</p>
                                </div>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Observações do Cliente</label>
                                <div class="rounded-lg border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900">
                                    <p v-if="consultation.data.client_note" class="whitespace-pre-wrap">
                                        {{ consultation.data.client_note }}
                                    </p>
                                    <p v-else class="text-gray-500 italic">Sem observações</p>
                                </div>
                            </div>
                        </div>
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
                                    <span class="font-medium">Veterinário:</span>
                                    <span>{{ veterinarian }}</span>
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
                                <v-icon color="green" icon="mdi-check-circle"></v-icon>
                            </template>
                            <v-card-title class="text-base">Status</v-card-title>
                            <v-card-text class="space-y-2 text-sm text-gray-700">
                                <div class="flex items-center gap-2">
                                    <v-icon icon="mdi-calendar" color="green" size="small"></v-icon>
                                    <span>Consulta marcada para {{ formatDate(consultation.data.date) }}</span>
                                </div>
                                <div v-if="consultation.data.veterinarian_note" class="flex items-center gap-2">
                                    <v-icon icon="mdi-note-check" color="green" size="small"></v-icon>
                                    <span>Veterinário adicionou observações</span>
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
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

interface ConsultationData {
    data: {
        id: string;
        date: string;
        veterinarian_note: string | null;
        client_note: string | null;
        animal_name: string;
        state: string;
    };
}

interface Props {
    consultation: ConsultationData;
    veterinarian: string;
}

const props = defineProps<Props>();

const consultation = computed(() => props.consultation);

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
