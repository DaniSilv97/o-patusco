<template>
    <v-card class="shadow-md">
        <v-card-text class="pt-6">
            <form @submit.prevent="submit">
                <div v-if="isAuthenticated" class="space-y-6">
                    <div class="bg-secondary rounded-lg p-4">
                        <p class="mb-2 text-sm font-semibold text-white">Informações do Utilizador</p>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-white">Nome:</span>
                                <span class="font-medium text-white">{{ authStore.user?.name }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-white">Email:</span>
                                <span class="font-medium text-white">{{ authStore.user?.email }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold">
                            <v-icon size="small" class="mr-1">mdi-paw</v-icon>
                            Animal
                        </label>

                        <div v-if="hasAnimals" class="space-y-3">
                            <v-select
                                v-model="form.animal.id"
                                :items="animalSelectOptions"
                                item-title="name"
                                item-value="id"
                                label="Selecione um animal"
                                variant="outlined"
                                class="w-full"
                                required
                                :error="!!getErrorMessage('animal.id')"
                                :error-messages="getErrorMessages('animal.id')"
                                @update:model-value="handleAnimalSelect"
                                @blur="validateAnimalSelection"
                            ></v-select>

                            <div v-if="selectedAnimal" class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                                <p class="text-sm"><strong>Tipo:</strong> {{ selectedAnimal.animalType }}</p>
                                <p class="text-sm"><strong>Data de Nascimento:</strong> {{ formatDate(selectedAnimal.birthday) }}</p>
                                <p class="text-sm"><strong>Idade:</strong> {{ formatAge(calculateAge(selectedAnimal.birthday)) }}</p>
                            </div>

                            <v-divider class="my-3"></v-divider>

                            <p class="mb-2 text-xs">
                                Não encontra o seu animal?
                                <a :href="route('animals.create')" class="text-secondary hover:underline">Registar novo animal</a>
                            </p>
                        </div>

                        <div v-else class="space-y-3">
                            <p class="mb-3 text-sm">Não tem animais registados. Por favor, preencha os dados abaixo:</p>

                            <v-text-field
                                v-model="form.animal.name"
                                label="Nome do animal"
                                variant="outlined"
                                placeholder="Ex: Fluffy"
                                required
                                :error="!!getErrorMessage('animal.name')"
                                :error-messages="getErrorMessages('animal.name')"
                                class="w-full"
                                @input="validateAnimalName"
                                @blur="validateAnimalName"
                            ></v-text-field>

                            <v-select
                                v-model="form.animal.animalTypeId"
                                :items="animalTypes"
                                item-title="name"
                                item-value="id"
                                label="Tipo de animal"
                                variant="outlined"
                                class="w-full"
                                required
                                :error="!!getErrorMessage('animal.animalTypeId')"
                                :error-messages="getErrorMessages('animal.animalTypeId')"
                                @update:model-value="validateAnimalType"
                                @blur="validateAnimalType"
                            ></v-select>

                            <v-text-field
                                v-model="form.animal.birthday"
                                label="Data de Nascimento"
                                type="date"
                                variant="outlined"
                                required
                                :error="!!getErrorMessage('animal.birthday')"
                                :error-messages="getErrorMessages('animal.birthday')"
                                class="w-full"
                                @input="validateAnimalBirthday"
                                @blur="validateAnimalBirthday"
                            ></v-text-field>
                        </div>
                    </div>
                </div>

                <div v-else class="space-y-6">
                    <div>
                        <p class="mb-3 text-sm font-semibold">Informações Pessoais</p>

                        <v-text-field
                            v-model="form.user.name"
                            label="Nome Completo"
                            variant="outlined"
                            placeholder="Ex: João Silva"
                            required
                            :error="!!getErrorMessage('user.name')"
                            :error-messages="getErrorMessages('user.name')"
                            class="mb-3 w-full"
                            @input="validateUserName"
                            @blur="validateUserName"
                        ></v-text-field>

                        <v-text-field
                            v-model="form.user.email"
                            label="Email"
                            type="email"
                            variant="outlined"
                            placeholder="Ex: joao@example.com"
                            required
                            :error="!!getErrorMessage('user.email')"
                            :error-messages="getErrorMessages('user.email')"
                            class="mb-3 w-full"
                            @input="validateUserEmail"
                            @blur="validateUserEmail"
                        ></v-text-field>

                        <v-text-field
                            v-model="form.user.birthday"
                            label="Data de Nascimento (Opcional)"
                            type="date"
                            variant="outlined"
                            :error="!!getErrorMessage('user.birthday')"
                            :error-messages="getErrorMessages('user.birthday')"
                            class="w-full"
                            @input="validateUserBirthday"
                            @blur="validateUserBirthday"
                        ></v-text-field>
                    </div>

                    <v-divider></v-divider>

                    <div>
                        <p class="mb-3 text-sm font-semibold">Informações do Animal</p>

                        <v-text-field
                            v-model="form.animal.name"
                            label="Nome do animal"
                            variant="outlined"
                            placeholder="Ex: Fluffy"
                            required
                            :error="!!getErrorMessage('animal.name')"
                            :error-messages="getErrorMessages('animal.name')"
                            class="mb-3 w-full"
                            @input="validateAnimalName"
                            @blur="validateAnimalName"
                        ></v-text-field>

                        <v-select
                            v-model="form.animal.animalTypeId"
                            :items="animalTypes"
                            item-title="name"
                            item-value="id"
                            label="Tipo de animal"
                            variant="outlined"
                            class="mb-3 w-full"
                            required
                            :error="!!getErrorMessage('animal.animalTypeId')"
                            :error-messages="getErrorMessages('animal.animalTypeId')"
                            @update:model-value="validateAnimalType"
                            @blur="validateAnimalType"
                        ></v-select>

                        <v-text-field
                            v-model="form.animal.birthday"
                            label="Data de Nascimento"
                            type="date"
                            variant="outlined"
                            required
                            :error="!!getErrorMessage('animal.birthday')"
                            :error-messages="getErrorMessages('animal.birthday')"
                            class="w-full"
                            @input="validateAnimalBirthday"
                            @blur="validateAnimalBirthday"
                        ></v-text-field>
                    </div>
                </div>

                <v-divider class="my-6"></v-divider>

                <div class="space-y-6">
                    <div>
                        <p class="mb-3 text-sm font-semibold">Detalhes da Consulta</p>

                        <v-text-field
                            v-model="form.date"
                            label="Data da Consulta"
                            type="date"
                            variant="outlined"
                            required
                            :error="!!getErrorMessage('date')"
                            :error-messages="getErrorMessages('date')"
                            class="mb-3 w-full"
                            @input="validateConsultationDate"
                            @blur="validateConsultationDate"
                        ></v-text-field>

                        <v-select
                            v-model="form.timeframe"
                            :items="timeframes"
                            item-title="name"
                            item-value="id"
                            label="Horário"
                            variant="outlined"
                            required
                            :error="!!getErrorMessage('timeframe')"
                            :error-messages="getErrorMessages('timeframe')"
                            @update:model-value="validateTimeframe"
                            @blur="validateTimeframe"
                        ></v-select>

                        <div class="space-y-6">
                            <div>
                                <p class="mb-3 text-sm font-semibold">Observações</p>

                                <v-textarea
                                    v-model="form.observations"
                                    label="Observações (Opcional)"
                                    placeholder="Adicione qualquer observação relevante sobre o animal ou consulta..."
                                    variant="outlined"
                                    rows="4"
                                    counter="255"
                                    maxlength="255"
                                    :error="!!getErrorMessage('observations')"
                                    :error-messages="getErrorMessages('observations')"
                                    class="w-full"
                                    @input="validateObservations"
                                    @blur="validateObservations"
                                ></v-textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <v-btn
                        size="large"
                        variant="outlined"
                        color="gray"
                        class="flex-1 hover:cursor-pointer"
                        @click="handleCancel"
                        :disabled="form.processing"
                    >
                        <v-icon class="mr-2">mdi-close</v-icon>
                        Cancelar
                    </v-btn>
                    <v-btn
                        size="large"
                        color="primary"
                        variant="elevated"
                        class="flex-1 hover:cursor-pointer"
                        @click="submit"
                        :loading="form.processing"
                    >
                        <v-icon class="mr-2">mdi-check-circle</v-icon>
                        Enviar Pedido
                    </v-btn>
                </div>
            </form>
        </v-card-text>
    </v-card>
</template>

<script setup lang="ts">
import { useAuthStore } from '@/stores/useAuthStore';
import { useForm } from '@inertiajs/vue3';
import { computed, watch } from 'vue';
import { Animal, AnimalType, Timeframe } from './CreateConsultationRequest.vue';

const props = defineProps({
    animals: {
        type: Array as () => Animal[],
        default: () => [],
    },
    animalTypes: {
        type: Array as () => AnimalType[],
        default: () => [],
    },
    timeframes: {
        type: Array as () => Timeframe[],
        required: true,
    },
});

const emit = defineEmits(['submit', 'cancel']);

const authStore = useAuthStore();

interface AgeObject {
    years: number;
    months: number;
}

const form = useForm({
    user: {
        id: authStore.user?.id || '',
        name: authStore.user?.name || '',
        email: authStore.user?.email || '',
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

const isAuthenticated = computed(() => authStore.isAuthenticated);
const hasAnimals = computed(() => isAuthenticated.value && props.animals.length > 0);

const animalSelectOptions = computed(() => {
    return props.animals.map((animal) => ({
        id: animal.id,
        name: animal.name,
        fullName: `${animal.name} (${getAnimalTypeName(animal.animalTypeId)})`,
    }));
});

const selectedAnimal = computed(() => {
    if (!form.animal.id) return null;
    const animal = props.animals.find((a) => a.id === form.animal.id);
    if (!animal) return null;

    return {
        name: animal.name,
        birthday: animal.birthday,
        animalType: getAnimalTypeName(animal.animalTypeId),
    };
});

const getAnimalTypeName = (animalTypeId: string): string => {
    const type = props.animalTypes.find((t) => t.id === animalTypeId);
    return type?.name || 'Tipo desconhecido';
};

const formatDate = (dateString: string): string => {
    try {
        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day} - ${month} - ${year}`;
    } catch {
        return 'Data inválida';
    }
};

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

const getErrorMessages = (field: string): string[] => {
    const message = getErrorMessage(field);
    return message ? [message] : [];
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

const validateUserName = () => {
    clearError('user.name');
    const name = form.user.name?.trim();

    if (!name) {
        setError('user.name', 'Nome completo é obrigatório');
    } else if (name.length < 3) {
        setError('user.name', 'Nome deve ter pelo menos 3 caracteres');
    } else if (!/^[a-záéíóúâêôãõñ\s'-]{3,}$/i.test(name)) {
        setError('user.name', 'Nome contém caracteres inválidos');
    }
};

const validateUserEmail = () => {
    clearError('user.email');
    const email = form.user.email?.trim();

    if (!email) {
        setError('user.email', 'Email é obrigatório');
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        setError('user.email', 'Formato de email inválido');
    }
};

const validateUserBirthday = () => {
    clearError('user.birthday');
    const birthday = form.user.birthday;

    if (!birthday) {
        return;
    }

    try {
        const birthDate = new Date(birthday);
        const today = new Date();

        if (birthDate > today) {
            setError('user.birthday', 'Data de nascimento não pode ser no futuro');
        } else if (today.getFullYear() - birthDate.getFullYear() < 13) {
            setError('user.birthday', 'Deve ter pelo menos 13 anos');
        }
    } catch {
        setError('user.birthday', 'Data inválida');
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

const validateAnimalSelection = () => {
    clearError('animal.id');

    if (!form.animal.id) {
        setError('animal.id', 'Selecione um animal');
    }
};

const validateConsultationDate = () => {
    clearError('date');
    const date = form.date;

    if (!date) {
        setError('date', 'Data da consulta é obrigatória');
    } else {
        try {
            const consultationDate = new Date(date);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (consultationDate < today) {
                setError('date', 'Data da consulta deve ser no futuro');
            }
        } catch {
            setError('date', 'Data inválida');
        }
    }
};

const validateTimeframe = () => {
    clearError('timeframe');

    if (!form.timeframe) {
        setError('timeframe', 'Horário é obrigatório');
    }
};

const validateObservations = () => {
    clearError('observations');
    const observations = form.observations;

    if (observations && observations.length > 255) {
        setError('observations', 'Observações não podem exceder 255 caracteres');
    }
};

const validateForm = (): boolean => {
    validateUserName();
    validateUserEmail();
    validateUserBirthday();
    validateAnimalName();
    validateAnimalType();
    validateAnimalBirthday();
    validateConsultationDate();
    validateTimeframe();
    validateObservations();

    if (hasAnimals.value) {
        validateAnimalSelection();
    }

    return !form.hasErrors;
};

const handleAnimalSelect = () => {
    const selected = props.animals.find((a) => a.id === form.animal.id);
    if (selected) {
        form.animal.name = selected.name;
        form.animal.birthday = selected.birthday;
        form.animal.animalTypeId = selected.animalTypeId;
    }
};

const submit = () => {
    if (!validateForm()) {
        return;
    }

    emit('submit', {
        user: {
            id: form.user.id,
            name: form.user.name,
            email: form.user.email,
            birthday: form.user.birthday || undefined,
        },
        animal: {
            id: form.animal.id,
            name: form.animal.name,
            birthday: form.animal.birthday,
            animalTypeId: form.animal.animalTypeId,
        },
        date: form.date,
        timeframe: form.timeframe,
        observations: form.observations,
    });
};

const handleCancel = () => {
    emit('cancel');
};

watch(
    () => authStore.user,
    (newUser) => {
        if (newUser) {
            form.user.id = newUser.id;
            form.user.name = newUser.name;
            form.user.email = newUser.email;
        }
    },
);
</script>
