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
            <p><strong>Idade aproximada:</strong> {{ formatAge(calculateAge(animal.birthday)) }}</p>
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

interface AgeObject {
    years: number;
    months: number;
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

const handleViewDetails = () => {
    emit('view-details', props.animal.id);
};
</script>
