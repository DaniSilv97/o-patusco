<template>
    <div class="rounded-lg border border-gray-200 p-4 transition-shadow hover:shadow-lg">
        <div class="mb-3 flex items-start justify-between">
            <h4 class="text-lg font-bold text-gray-800">{{ animal.name }}</h4>
            <v-chip size="small" color="purple">
                {{ animal.animal_type || 'Tipo desconhecido' }}
            </v-chip>
        </div>

        <div class="mb-4 flex flex-col gap-2 text-sm text-gray-600">
            <p><strong>Data de Nascimento:</strong> {{ formatDate(animal.birthday) }}</p>
            <p><strong>Idade aproximada:</strong> {{ calculateAge(animal.birthday) }} anos</p>
        </div>

        <v-btn size="small" variant="outlined" color="primary" class="w-full hover:cursor-pointer" @click="handleViewDetails"> Ver Detalhes </v-btn>
    </div>
</template>

<script setup lang="ts">
interface Animal {
    id: string;
    name: string;
    animal_type: string;
    birthday: string;
}

const props = defineProps({
    animal: {
        type: Object as () => Animal,
        required: true,
    },
});

const emit = defineEmits(['view-details']);

const formatDate = (dateString: string): string => {
    try {
        const date = new Date(dateString);
        return date.toLocaleDateString('pt-PT', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        });
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

const handleViewDetails = () => {
    emit('view-details', props.animal.id);
};
</script>
