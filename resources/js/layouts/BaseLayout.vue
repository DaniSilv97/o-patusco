<template>
    <Head :title="props.title"></Head>
    <v-layout class="base-layout">
        <v-app-bar scroll-behavior="hide" color="primary">
            <template v-slot:prepend>
                <v-app-bar-title class="ml-4">O Patusco</v-app-bar-title>
            </template>

            <template v-slot:append>
                <slot name="append"></slot>
            </template>
        </v-app-bar>
        <main class="mt-16 h-[calc(100vh-4rem)] w-screen content-center overflow-y-scroll bg-gray-200">
            <div class="mx-auto h-fit w-fit bg-white shadow-2xl sm:my-4 sm:rounded-lg">
                <slot />
            </div>
        </main>
    </v-layout>
</template>

<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

const props = withDefaults(defineProps<{ title?: string }>(), {
    title: 'Homepage',
});
</script>
