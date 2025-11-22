<template>
    <BaseLayout :title="props.title">
        <template v-slot:append>
            <Link :href="route('home')" v-if="page.url === '/login'">
                <v-btn class="hover:cursor-pointer">
                    <v-icon icon="mdi-chevron-left" class="ml-2"></v-icon>
                    Voltar ao menu
                </v-btn>
            </Link>
            <Link :href="route('login')" v-else>
                <v-btn class="hover:cursor-pointer">
                    Iniciar sessão
                    <v-icon icon="mdi-login" class="ml-2"></v-icon
                ></v-btn>
            </Link>
        </template>
        <template v-slot>
            <slot />
        </template>
    </BaseLayout>
</template>

<script setup lang="ts">
// import { home, login } from '@/routes';
import { Link, usePage } from '@inertiajs/vue3';
import BaseLayout from './BaseLayout.vue';

const props = withDefaults(defineProps<{ title?: string }>(), {
    title: 'Homepage',
});

const page = usePage();
</script>
