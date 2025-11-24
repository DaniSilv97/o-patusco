<template>
    <AuthLayout title="Os meus animais">
        <BaseContainer>
            <div class="flex flex-col gap-6">
                <div class="flex items-center justify-between">
                    <PageHeader title="Os meus animais" description="Gerencie e visualize todos os seus animais" />
                    <Link :href="route('animals.create')">
                        <v-btn color="primary" prepend-icon="mdi-plus"> Novo Animal </v-btn>
                    </Link>
                </div>

                <v-divider></v-divider>

                <div class="flex flex-col gap-4 md:flex-row md:items-end">
                    <div class="flex-1">
                        <label class="mb-2 block text-sm font-medium text-gray-700">Nome do Animal</label>
                        <v-text-field
                            v-model="filters.name"
                            placeholder="Procurar por nome..."
                            prepend-inner-icon="mdi-magnify"
                            variant="outlined"
                            density="compact"
                            clearable
                            @update:model-value="applyFilters"
                        ></v-text-field>
                    </div>

                    <div class="flex-1">
                        <label class="mb-2 block text-sm font-medium text-gray-700">Tipo de Animal</label>
                        <v-select
                            v-model="filters.animal_type"
                            :items="props.animalTypes"
                            item-title="name"
                            item-value="id"
                            placeholder="Selecione um tipo..."
                            variant="outlined"
                            density="compact"
                            clearable
                            @update:model-value="applyFilters"
                        ></v-select>
                    </div>

                    <v-btn v-if="hasActiveFilters" variant="outlined" color="secondary" @click="resetFilters" class="my-auto"> Limpar Filtros </v-btn>
                </div>

                <v-divider class="my-4"></v-divider>

                <div v-if="animals.length === 0" class="flex flex-col items-center justify-center gap-4 py-12">
                    <v-icon size="64" color="gray">mdi-paw-off</v-icon>
                    <h3 class="text-lg font-semibold text-gray-700">Nenhum animal registado</h3>
                    <p class="text-center text-gray-600">Comece adicionando seu primeiro animal para que possa agendar consultas</p>
                    <Link :href="route('animals.create')">
                        <v-btn color="primary"> Adicionar Primeiro Animal </v-btn>
                    </Link>
                </div>

                <div v-else class="flex flex-col gap-6">
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        <AnimalCard v-for="animal in paginatedAnimals" :key="animal.id" :animal="animal" @view-details="handleViewDetails" />
                    </div>

                    <div v-if="totalPages > 1" class="flex items-center justify-center gap-2">
                        <v-btn variant="outlined" size="small" :disabled="currentPage === 1" @click="previousPage" icon="mdi-chevron-left"></v-btn>

                        <div class="flex gap-1">
                            <v-btn
                                v-for="page in displayedPages"
                                :key="page"
                                :variant="page === currentPage ? 'flat' : 'outlined'"
                                :color="page === currentPage ? 'primary' : undefined"
                                size="small"
                                @click="goToPage(page)"
                            >
                                {{ page }}
                            </v-btn>
                        </div>

                        <v-btn
                            variant="outlined"
                            size="small"
                            :disabled="currentPage === totalPages"
                            @click="nextPage"
                            icon="mdi-chevron-right"
                        ></v-btn>
                    </div>

                    <div class="text-center text-sm text-gray-600">Ver {{ startIndex + 1 }} a {{ endIndex }} de {{ animals.length }} animais</div>
                </div>
            </div>
        </BaseContainer>
    </AuthLayout>
</template>

<script setup lang="ts">
import BaseContainer from '@/components/BaseContainer.vue';
import PageHeader from '@/components/PageHeader.vue';
import AuthLayout from '@/layouts/AuthLayout.vue';
import AnimalCard from '@/pages/Dashboard/ClientDashboard/AnimalCard.vue';
import { Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

interface Animal {
    id: string;
    name: string;
    animal_type: string;
    birthday: string;
}

const props = defineProps({
    animals: {
        type: Array as () => Animal[],
        required: true,
    },
    animalTypes: {
        type: Array,
        default: () => [],
    },
});

const itemsPerPage = ref(6);
const currentPage = ref(1);

const totalPages = computed(() => {
    return Math.ceil(props.animals.length / itemsPerPage.value);
});

const startIndex = computed(() => {
    return (currentPage.value - 1) * itemsPerPage.value;
});

const endIndex = computed(() => {
    return Math.min(startIndex.value + itemsPerPage.value, props.animals.length);
});

const paginatedAnimals = computed(() => {
    return props.animals.slice(startIndex.value, endIndex.value);
});

const displayedPages = computed(() => {
    const pages: number[] = [];
    const maxPagesToShow = 5;
    const halfWindow = Math.floor(maxPagesToShow / 2);

    let start = Math.max(1, currentPage.value - halfWindow);
    let end = Math.min(totalPages.value, currentPage.value + halfWindow);

    if (currentPage.value <= halfWindow) {
        end = Math.min(totalPages.value, maxPagesToShow);
    } else if (currentPage.value > totalPages.value - halfWindow) {
        start = Math.max(1, totalPages.value - maxPagesToShow + 1);
    }

    for (let i = start; i <= end; i++) {
        pages.push(i);
    }

    return pages;
});

const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
    }
};

const previousPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
};

const goToPage = (page: number) => {
    currentPage.value = page;
};

const handleViewDetails = (animalId: string) => {
    router.visit(route('animals.show', animalId));
};

const filters = ref({
    name: '',
    animal_type: '',
});

const hasActiveFilters = computed(() => {
    return filters.value.name !== '' || filters.value.animal_type !== '';
});

const applyFilters = () => {
    currentPage.value = 1; // Reset to first page when filtering
    router.get(route('animals'), filters.value, {
        preserveState: true,
        replace: true,
    });
};

const resetFilters = () => {
    filters.value = {
        name: '',
        animal_type: '',
    };
    currentPage.value = 1;
    router.get(
        route('animals'),
        {},
        {
            preserveState: true,
            replace: true,
        },
    );
};
</script>
