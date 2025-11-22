<template>
    <BaseLayout :title="props.title">
        <template v-slot:append>
            <v-btn id="menu-activator" color="white" icon="mdi-menu" class="hover:cursor-pointer"> </v-btn>

            <v-menu activator="#menu-activator">
                <v-list>
                    <v-list-item>
                        <Link :href="route('profile')">
                            <v-btn class="hover:cursor-pointer">
                                <v-icon icon="mdi-account" class="mr-2"></v-icon>
                                <v-list-item-title>Perfil</v-list-item-title>
                            </v-btn>
                        </Link>
                    </v-list-item>
                    <v-list-item>
                        <Link :href="route('profile')">
                            <v-btn class="hover:cursor-pointer">
                                <v-icon icon="mdi-paw" class="mr-2"></v-icon>
                                <v-list-item-title>Os meus animais</v-list-item-title>
                            </v-btn>
                        </Link>
                    </v-list-item>
                    <v-list-item>
                        <Link :href="route('profile')">
                            <v-btn class="hover:cursor-pointer">
                                <v-icon icon="mdi-calendar" class="mr-2"></v-icon>
                                <v-list-item-title>Consultas</v-list-item-title>
                            </v-btn>
                        </Link>
                    </v-list-item>
                    <v-list-item>
                        <Link :href="route('profile')">
                            <v-btn class="hover:cursor-pointer">
                                <v-icon icon="mdi-logout" class="mr-2"></v-icon>
                                <v-list-item-title>Sair</v-list-item-title>
                            </v-btn>
                        </Link>
                    </v-list-item>
                </v-list>
            </v-menu>
        </template>
    </BaseLayout>
    <slot />
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import BaseLayout from './BaseLayout.vue';

const props = withDefaults(defineProps<{ title?: string }>(), {
    title: 'Homepage',
});
</script>
