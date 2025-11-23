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
                                :error="!!form.errors['animal.id']"
                                :error-messages="form.errors['animal.id'] ? [form.errors['animal.id']] : []"
                                @update:model-value="handleAnimalSelect"
                                @blur="validateAnimalSelection"
                            ></v-select>

                            <div v-if="selectedAnimal" class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                                <p class="text-sm"><strong>Tipo:</strong> {{ selectedAnimal.animalType }}</p>
                                <p class="text-sm"><strong>Data de Nascimento:</strong> {{ formatDate(selectedAnimal.birthday) }}</p>
                                <p class="text-sm"><strong>Idade:</strong> {{ calculateAge(selectedAnimal.birthday) }} anos</p>
                            </div>

                            <v-divider class="my-3"></v-divider>

                            <p class="mb-2 text-xs">
                                Não encontra o seu animal?
                                <a href="#" class="text-secondary hover:underline">Registar novo animal</a>
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
                                :error="!!form.errors['animal.name']"
                                :error-messages="form.errors['animal.name'] ? [form.errors['animal.name']] : []"
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
                                :error="!!form.errors['animal.animalTypeId']"
                                :error-messages="form.errors['animal.animalTypeId'] ? [form.errors['animal.animalTypeId']] : []"
                                @update:model-value="validateAnimalType"
                                @blur="validateAnimalType"
                            ></v-select>

                            <v-text-field
                                v-model="form.animal.birthday"
                                label="Data de Nascimento"
                                type="date"
                                variant="outlined"
                                required
                                :error="!!form.errors['animal.birthday']"
                                :error-messages="form.errors['animal.birthday'] ? [form.errors['animal.birthday']] : []"
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
                            :error="!!form.errors['user.name']"
                            :error-messages="form.errors['user.name'] ? [form.errors['user.name']] : []"
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
                            :error="!!form.errors['user.email']"
                            :error-messages="form.errors['user.email'] ? [form.errors['user.email']] : []"
                            class="mb-3 w-full"
                            @input="validateUserEmail"
                            @blur="validateUserEmail"
                        ></v-text-field>

                        <v-text-field
                            v-model="form.user.birthday"
                            label="Data de Nascimento (Opcional)"
                            type="date"
                            variant="outlined"
                            :error="!!form.errors['user.birthday']"
                            :error-messages="form.errors['user.birthday'] ? [form.errors['user.birthday']] : []"
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
                            :error="!!form.errors['animal.name']"
                            :error-messages="form.errors['animal.name'] ? [form.errors['animal.name']] : []"
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
                            :error="!!form.errors['animal.animalTypeId']"
                            :error-messages="form.errors['animal.animalTypeId'] ? [form.errors['animal.animalTypeId']] : []"
                            @update:model-value="validateAnimalType"
                            @blur="validateAnimalType"
                        ></v-select>

                        <v-text-field
                            v-model="form.animal.birthday"
                            label="Data de Nascimento"
                            type="date"
                            variant="outlined"
                            required
                            :error="!!form.errors['animal.birthday']"
                            :error-messages="form.errors['animal.birthday'] ? [form.errors['animal.birthday']] : []"
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
                            :error="!!form.errors['date']"
                            :error-messages="form.errors['date'] ? [form.errors['date']] : []"
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
                            :error="!!form.errors['timeframe']"
                            :error-messages="form.errors['timeframe'] ? [form.errors['timeframe']] : []"
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
                                    :error="!!form.errors['observations']"
                                    :error-messages="form.errors['observations'] ? [form.errors['observations']] : []"
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
                        :disabled="isLoading"
                    >
                        <v-icon class="mr-2">mdi-close</v-icon>
                        Cancelar
                    </v-btn>
                    <v-btn size="large" color="primary" variant="elevated" class="flex-1 hover:cursor-pointer" @click="submit" :loading="isLoading">
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
import { computed, ref, watch } from 'vue';
import { Animal, AnimalType, ConsultationFormData, FormErrors, Timeframe } from './CreateConsultationRequest.vue';

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
    isLoading: {
        type: Boolean,
        default: false,
    },
    errors: {
        type: Object as () => FormErrors,
        default: () => ({}),
    },
});

const emit = defineEmits(['submit', 'cancel']);

const authStore = useAuthStore();

const form = ref<ConsultationFormData & { errors: FormErrors }>({
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
    errors: {},
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
    if (!form.value.animal.id) return null;
    const animal = props.animals.find((a) => a.id === form.value.animal.id);
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

const calculateAge = (birthday: string): number => {
    try {
        const birthDate = new Date(birthday);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();

        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }

        return age;
    } catch {
        return 0;
    }
};

const clearError = (field: string) => {
    form.value.errors[field] = undefined;
};

const setError = (field: string, message: string) => {
    form.value.errors[field] = message;
};

const validateUserName = () => {
    clearError('user.name');
    const name = form.value.user.name?.trim();

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
    const email = form.value.user.email?.trim();

    if (!email) {
        setError('user.email', 'Email é obrigatório');
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        setError('user.email', 'Formato de email inválido');
    }
};

const validateUserBirthday = () => {
    clearError('user.birthday');
    const birthday = form.value.user.birthday;

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
    const name = form.value.animal.name?.trim();

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

    if (!form.value.animal.animalTypeId) {
        setError('animal.animalTypeId', 'Tipo de animal é obrigatório');
    }
};

const validateAnimalBirthday = () => {
    clearError('animal.birthday');
    const birthday = form.value.animal.birthday;

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

    if (!form.value.animal.id) {
        setError('animal.id', 'Selecione um animal');
    }
};

const validateConsultationDate = () => {
    clearError('date');
    const date = form.value.date;

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

    if (!form.value.timeframe) {
        setError('timeframe', 'Horário é obrigatório');
    }
};

const validateObservations = () => {
    clearError('observations');
    const observations = form.value.observations;

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

    return Object.values(form.value.errors).every((error) => !error);
};

const handleAnimalSelect = () => {
    const selected = props.animals.find((a) => a.id === form.value.animal.id);
    if (selected) {
        form.value.animal.name = selected.name;
        form.value.animal.birthday = selected.birthday;
        form.value.animal.animalTypeId = selected.animalTypeId;
    }
};

const submit = () => {
    if (!validateForm()) {
        return;
    }

    const formData = {
        ...form.value,
        animal: {
            ...form.value.animal,
            birthday: form.value.animal.birthday,
        },
        user: {
            ...form.value.user,
            birthday: form.value.user.birthday || undefined,
        },
    };

    emit('submit', formData);
};

const handleCancel = () => {
    emit('cancel');
};

watch(
    () => authStore.user,
    (newUser) => {
        if (newUser) {
            form.value.user.id = newUser.id;
            form.value.user.name = newUser.name;
            form.value.user.email = newUser.email;
        }
    },
);
</script>
