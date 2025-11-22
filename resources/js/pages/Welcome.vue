<template>
    <GuestLayout title="O Patusco - Pet Care Management">
        <div class="space-y-12 px-4 py-8 sm:px-8">
            <div class="space-y-4 text-center">
                <h1 class="text-primary text-4xl font-bold sm:text-5xl">Clínica veterinária O Patusco</h1>
                <p class="mx-auto max-w-2xl text-lg text-gray-600">
                    Gerencie consultas veterinárias dos seus animais de estimação de forma fácil e segura.
                </p>
            </div>

            <div class="from-primary to-secondary space-y-6 rounded-lg bg-gradient-to-r p-8 text-center shadow-lg sm:p-12">
                <h2 class="text-3xl font-bold text-white">Agendar uma Consulta</h2>
                <p class="text-lg text-white opacity-90">Crie uma nova consulta para o seu animal de estimação. Sem necessidade de login.</p>
                <Link :href="route('login')">
                    <v-btn color="white" size="x-large" class="hover:cursor-pointer">
                        <v-icon icon="mdi-calendar-plus" class="mr-2"></v-icon>
                        Criar Consulta
                    </v-btn>
                </Link>
            </div>

            <div class="border-secondary space-y-4 rounded-lg border bg-gradient-to-r from-gray-200 to-white p-6 text-center">
                <p class="text-gray-800">Já tem uma conta? Inicie sessão para aceder a todas as suas consultas e animais.</p>
                <Link :href="route('login')">
                    <v-btn color="primary" size="large" class="hover:cursor-pointer">
                        <v-icon icon="mdi-login" class="mr-2"></v-icon>
                        Iniciar Sessão
                    </v-btn>
                </Link>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <div class="rounded-lg border border-gray-200 bg-white p-6 text-center transition">
                    <v-icon icon="mdi-paw" size="48" color="primary" class="mb-4"></v-icon>
                    <h3 class="mb-2 text-xl font-semibold text-gray-800">Gerencie Seus Animais</h3>
                    <p class="text-gray-600">Cadastre e mantenha as informações de todos os seus animais de estimação organizadas.</p>
                </div>

                <div class="rounded-lg border border-gray-200 bg-white p-6 text-center transition">
                    <v-icon icon="mdi-calendar-check" size="48" color="accent" class="mb-4"></v-icon>
                    <h3 class="mb-2 text-xl font-semibold text-gray-800">Consultas Fáceis</h3>
                    <p class="text-gray-600">Crie e acompanhe consultas veterinárias sem complicações.</p>
                </div>

                <div class="rounded-lg border border-gray-200 bg-white p-6 text-center transition">
                    <v-icon icon="mdi-shield-check" size="48" color="secondary" class="mb-4"></v-icon>
                    <h3 class="mb-2 text-xl font-semibold text-gray-800">Seguro e Privado</h3>
                    <p class="text-gray-600">Seus dados e os dos seus animais estão sempre protegidos.</p>
                </div>
            </div>

            <div class="space-y-4 rounded-lg border border-gray-200 bg-gray-50 p-8">
                <h2 class="text-2xl font-bold text-gray-800">Como Funciona?</h2>
                <ol class="space-y-3 text-gray-700">
                    <li class="flex items-start gap-4">
                        <span class="bg-primary flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full font-semibold text-white">
                            1
                        </span>
                        <span>Clique em "Criar Consulta" para iniciar o agendamento de uma nova consulta.</span>
                    </li>
                    <li class="flex items-start gap-4">
                        <span class="bg-primary flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full font-semibold text-white">
                            2
                        </span>
                        <span>Preencha os dados do seu animal de estimação e informações da consulta.</span>
                    </li>
                    <li class="flex items-start gap-4">
                        <span class="bg-primary flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full font-semibold text-white">
                            3
                        </span>
                        <span>Confirme o agendamento e acompanhe o status da sua consulta.</span>
                    </li>
                </ol>
            </div>
        </div>
    </GuestLayout>
</template>

<script setup lang="ts">
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Link } from '@inertiajs/vue3';
</script>
