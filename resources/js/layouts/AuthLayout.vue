<template>
    <BaseLayout :title="props.title">
        <template v-slot:append>
            <v-btn id="menu-activator" color="white" icon="mdi-menu" class="hover:cursor-pointer"></v-btn>
            <v-menu activator="#menu-activator">
                <v-list>
                    <v-list-item v-can="['is-client']">
                        <Link :href="route('dashboard')">
                            <v-btn class="!flex w-full !justify-start hover:cursor-pointer">
                                <v-icon icon="mdi-view-dashboard" class="mr-2"></v-icon>
                                <v-list-item-title>Dashboard</v-list-item-title>
                            </v-btn>
                        </Link>
                    </v-list-item>
                    <v-list-item v-can="['is-receptionist']">
                        <Link :href="route('receptionist.dashboard')">
                            <v-btn class="!flex w-full !justify-start hover:cursor-pointer">
                                <v-icon icon="mdi-view-dashboard" class="mr-2"></v-icon>
                                <v-list-item-title>Dashboard Rececionista</v-list-item-title>
                            </v-btn>
                        </Link>
                    </v-list-item>
                    <v-list-item v-can="['is-veterinarian']">
                        <Link :href="route('veterinarian.dashboard')">
                            <v-btn class="!flex w-full !justify-start hover:cursor-pointer">
                                <v-icon icon="mdi-view-dashboard" class="mr-2"></v-icon>
                                <v-list-item-title>Dashboard Veterinário</v-list-item-title>
                            </v-btn>
                        </Link>
                    </v-list-item>
                    <v-list-item>
                        <Link :href="route('profile.edit')">
                            <v-btn class="!flex w-full !justify-start hover:cursor-pointer">
                                <v-icon icon="mdi-account mr-2"></v-icon>
                                <v-list-item-title>Perfil</v-list-item-title>
                            </v-btn>
                        </Link>
                    </v-list-item>
                    <v-list-item v-can="['is-client']">
                        <Link :href="route('animals')">
                            <v-btn class="!flex w-full !justify-start hover:cursor-pointer">
                                <v-icon icon="mdi-paw" class="mr-2"></v-icon>
                                <v-list-item-title>Os meus animais</v-list-item-title>
                            </v-btn>
                        </Link>
                    </v-list-item>
                    <v-list-item v-can="['is-client']">
                        <Link :href="route('animals.create')">
                            <v-btn class="!flex w-full !justify-start hover:cursor-pointer">
                                <v-icon icon="mdi-paw-outline" class="mr-2"></v-icon>
                                <v-list-item-title>Criar animail</v-list-item-title>
                            </v-btn>
                        </Link>
                    </v-list-item>
                    <v-list-item v-can="['is-client']">
                        <Link :href="route('consultations')">
                            <v-btn class="!flex w-full !justify-start hover:cursor-pointer">
                                <v-icon icon="mdi-calendar" class="mr-2"></v-icon>
                                <v-list-item-title>Consultas</v-list-item-title>
                            </v-btn>
                        </Link>
                    </v-list-item>
                    <v-list-item>
                        <v-btn class="!flex w-full !justify-start hover:cursor-pointer" @click="logout">
                            <v-icon icon="mdi-logout" class="mr-2"></v-icon>
                            <v-list-item-title>Sair</v-list-item-title>
                        </v-btn>
                    </v-list-item>
                </v-list>
            </v-menu>
        </template>
        <template v-slot>
            <slot />
        </template>
    </BaseLayout>
</template>

<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import BaseLayout from './BaseLayout.vue';

const props = withDefaults(defineProps<{ title?: string }>(), {
    title: 'Homepage',
});

const logout = () => {
    router.post(route('logout'));
};
</script>
