<template>
    <AuthLayout title="Novo Animal">
        <BaseContainer>
            <div class="flex flex-col gap-6">
                <div class="flex items-center justify-between">
                    <PageHeader title="Registar Novo Animal" description="Adicione os detalhes do seu animal de estimação" />
                    <Link :href="route('animals')">
                        <v-btn variant="outlined" prepend-icon="mdi-arrow-left"> Voltar </v-btn>
                    </Link>
                </div>

                <v-divider></v-divider>

                <div class="grid gap-8 lg:grid-cols-3">
                    <div class="lg:col-span-2">
                        <form @submit.prevent="submitForm" class="space-y-6">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Nome do Animal *</label>
                                <v-text-field
                                    v-model="form.animal.name"
                                    placeholder="Ex: Fluffy, Max, Bella..."
                                    variant="outlined"
                                    density="compact"
                                    prepend-inner-icon="mdi-paw"
                                    :error="!!getErrorMessage('animal.name')"
                                    :error-messages="getErrorMessage('animal.name') ? [getErrorMessage('animal.name')] : []"
                                    clearable
                                    @input="validateAnimalName"
                                    @blur="validateAnimalName"
                                ></v-text-field>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Tipo de Animal *</label>
                                <v-select
                                    v-model="form.animal.animalTypeId"
                                    :items="animalTypes"
                                    item-title="name"
                                    item-value="id"
                                    placeholder="Selecione um tipo de animal..."
                                    variant="outlined"
                                    density="compact"
                                    prepend-inner-icon="mdi-dog-side"
                                    :error="!!getErrorMessage('animal.animalTypeId')"
                                    :error-messages="getErrorMessage('animal.animalTypeId') ? [getErrorMessage('animal.animalTypeId')] : []"
                                    clearable
                                    @update:model-value="validateAnimalType"
                                    @blur="validateAnimalType"
                                ></v-select>
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Data de Nascimento *</label>
                                <v-text-field
                                    v-model="form.animal.birthday"
                                    type="date"
                                    variant="outlined"
                                    density="compact"
                                    prepend-inner-icon="mdi-calendar"
                                    :error="!!getErrorMessage('animal.birthday')"
                                    :error-messages="getErrorMessage('animal.birthday') ? [getErrorMessage('animal.birthday')] : []"
                                    @input="validateAnimalBirthday"
                                    @blur="validateAnimalBirthday"
                                ></v-text-field>
                                <p v-if="form.animal.birthday" class="mt-2 text-sm text-gray-600">
                                    Idade aproximada: <strong>{{ formatAge(calculateAge(form.animal.birthday)) }}</strong>
                                </p>
                            </div>

                            <v-divider class="my-6"></v-divider>

                            <div class="flex gap-4">
                                <v-btn type="submit" color="primary" class="flex-1" :loading="loading" :disabled="loading">
                                    <v-icon icon="mdi-check" class="mr-2"></v-icon>
                                    Registar Animal
                                </v-btn>
                                <Link :href="route('animals')">
                                    <v-btn variant="outlined" class="flex-1">
                                        <v-icon icon="mdi-close" class="mr-2"></v-icon>
                                        Cancelar
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
                            <v-card-title class="text-base">Dicas para Preenchimento</v-card-title>
                            <v-card-text class="space-y-3 text-sm text-gray-700">
                                <div class="flex gap-2">
                                    <v-icon icon="mdi-check-circle" color="green" size="small"></v-icon>
                                    <span>Use o nome pelo qual o animal é conhecido (2-50 caracteres)</span>
                                </div>
                                <div class="flex gap-2">
                                    <v-icon icon="mdi-check-circle" color="green" size="small"></v-icon>
                                    <span>A data de nascimento é importante para registos médicos</span>
                                </div>
                                <div class="flex gap-2">
                                    <v-icon icon="mdi-check-circle" color="green" size="small"></v-icon>
                                    <span>Você pode editar estas informações mais tarde</span>
                                </div>
                            </v-card-text>
                        </v-card>

                        <v-card v-if="form.animal.birthday" class="rounded-lg border border-purple-200 bg-purple-50 p-4">
                            <v-card-title class="text-base">Informações do Animal</v-card-title>
                            <v-card-text class="space-y-2 text-sm">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Nome:</span>
                                    <strong>{{ form.animal.name || 'Não preenchido' }}</strong>
                                </div>
                                <v-divider></v-divider>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Tipo:</span>
                                    <strong>{{ getAnimalTypeName() || 'Não selecionado' }}</strong>
                                </div>
                                <v-divider></v-divider>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600">Idade:</span>
                                    <strong>{{ formatAge(calculateAge(form.animal.birthday)) }}</strong>
                                </div>
                            </v-card-text>
                        </v-card>

                        <div v-else class="flex flex-col items-center justify-center gap-3 rounded-lg bg-gray-100 p-8">
                            <v-icon size="48" color="gray">mdi-paw</v-icon>
                            <p class="text-center text-sm text-gray-600">Preencha os detalhes para visualizar a pré-visualização</p>
                        </div>

                        <v-card class="rounded-lg bg-amber-50 p-4">
                            <template v-slot:prepend>
                                <v-icon color="amber" icon="mdi-alert"></v-icon>
                            </template>
                            <v-card-title class="text-base">Campos Obrigatórios</v-card-title>
                            <v-card-text class="text-sm text-gray-700">
                                Os campos marcados com <span class="font-bold text-red-500">*</span> devem ser preenchidos antes de registar o animal.
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
import { computed } from 'vue';

interface AnimalType {
    id: string;
    name: string;
}

interface AgeObject {
    years: number;
    months: number;
}

const props = defineProps({
    animalTypes: {
        type: Array as () => AnimalType[],
        default: () => [],
    },
});

const form = useForm({
    animal: {
        name: '',
        animalTypeId: null as string | null,
        birthday: '',
    },
});

const loading = computed(() => form.processing);

const calculateAge = (birthday: string): AgeObject => {
    try {
        if (!birthday) return { years: 0, months: 0 };
        const birthDate = new Date(birthday);
        const today = new Date();
        let years = today.getFullYear() - birthDate.getFullYear();
        let months = today.getMonth() - birthDate.getMonth();

        if (months < 0) {
            years--;
            months += 12;
        }

        if (today.getDate() < birthDate.getDate()) {
            months--;
            if (months < 0) {
                years--;
                months += 12;
            }
        }

        return { years: Math.max(0, years), months: Math.max(0, months) };
    } catch {
        return { years: 0, months: 0 };
    }
};

const formatAge = (ageObj: AgeObject): string => {
    if (ageObj.years === 0 && ageObj.months === 0) return '0 meses';
    if (ageObj.years === 0) return `${ageObj.months} mês${ageObj.months > 1 ? 'es' : ''}`;
    if (ageObj.months === 0) return `${ageObj.years} ano${ageObj.years > 1 ? 's' : ''}`;
    return `${ageObj.years} ano${ageObj.years > 1 ? 's' : ''} e ${ageObj.months} mês${ageObj.months > 1 ? 'es' : ''}`;
};

const getAnimalTypeName = (): string => {
    if (!form.animal.animalTypeId) return '';
    const type = props.animalTypes.find((t) => t.id === form.animal.animalTypeId);
    return type?.name || '';
};

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

const validateAnimalName = () => {
    clearError('animal.name');
    const name = form.animal.name?.trim();

    if (!name) {
        setError('animal.name', 'Nome do animal é obrigatório');
    } else if (name.length < 2) {
        setError('animal.name', 'Nome deve ter pelo menos 2 caracteres');
    } else if (name.length > 50) {
        setError('animal.name', 'Nome deve ter no máximo 50 caracteres');
    }
};

const validateAnimalType = () => {
    clearError('animal.animalTypeId');

    if (!form.animal.animalTypeId) {
        setError('animal.animalTypeId', 'Tipo de animal é obrigatório');
    }
};

const validateAnimalBirthday = () => {
    clearError('animal.birthday');
    const birthday = form.animal.birthday;

    if (!birthday) {
        setError('animal.birthday', 'Data de nascimento é obrigatória');
    } else {
        try {
            const birthDate = new Date(birthday);
            const today = new Date();

            if (birthDate > today) {
                setError('animal.birthday', 'Data de nascimento não pode ser no futuro');
            }
        } catch {
            setError('animal.birthday', 'Data inválida');
        }
    }
};

const validateForm = (): boolean => {
    validateAnimalName();
    validateAnimalType();
    validateAnimalBirthday();

    return !form.hasErrors;
};

const submitForm = () => {
    if (!validateForm()) {
        return;
    }

    form.post(route('animals.store'), {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>
