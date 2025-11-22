<template>
    <GuestLayout title="Iniciar Sessão">
        <div class="flex items-center justify-center">
            <div class="max-w-md">
                <v-alert v-if="status" type="success" variant="tonal" class="mb-6" closable>
                    {{ status }}
                </v-alert>

                <v-card elevation="2" class="rounded-xl">
                    <div class="from-primary to-secondary bg-gradient-to-r px-6 py-8 text-center">
                        <v-icon icon="mdi-paw" size="48" color="white" class="mb-3"></v-icon>
                        <h1 class="text-3xl font-bold text-white">O Patusco</h1>
                        <p class="mt-1 text-sm text-white opacity-90">Gerencie as consultas dos seus animais</p>
                    </div>

                    <div class="px-6 py-8">
                        <form @submit.prevent="submit" class="space-y-4">
                            <div>
                                <v-text-field
                                    v-model="form.email"
                                    label="Endereço de Email"
                                    type="email"
                                    name="email"
                                    placeholder="seu@email.com"
                                    prepend-inner-icon="mdi-email"
                                    variant="outlined"
                                    required
                                    autofocus
                                    class="mb-1"
                                    :error="!!form.errors.email"
                                    :error-messages="form.errors.email ? [form.errors.email] : []"
                                    autocomplete="email"
                                    @input="handleEmailInput"
                                />
                            </div>

                            <div>
                                <v-text-field
                                    v-model="form.password"
                                    label="Password"
                                    :type="showPassword ? 'text' : 'password'"
                                    name="password"
                                    placeholder="Introduza a sua palavra-passe"
                                    prepend-inner-icon="mdi-lock"
                                    :append-inner-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
                                    variant="outlined"
                                    required
                                    :error="!!form.errors.password"
                                    :error-messages="form.errors.password ? [form.errors.password] : []"
                                    autocomplete="current-password"
                                    @click:append-inner="showPassword = !showPassword"
                                />
                                <!-- <Link
                                    v-if="canResetPassword"
                                    :href="route('password.request')"
                                    class="text-primary hover:text-secondary text-xs font-medium transition"
                                >
                                    Recuperar palavra-passe
                                </Link> -->
                            </div>

                            <div class="flex gap-3">
                                <v-checkbox v-model="form.remember">
                                    <template v-slot:label>
                                        <v-label>
                                            <p class="text-sm">Manter-me conectado</p>
                                        </v-label>
                                    </template>
                                </v-checkbox>
                            </div>

                            <v-btn type="submit" color="primary" size="large" block :loading="form.processing" :disabled="form.processing">
                                <v-icon icon="mdi-login" class="mr-2"></v-icon>
                                Iniciar Sessão
                            </v-btn>
                        </form>
                    </div>

                    <div class="border-t border-gray-200 bg-gray-50 px-6 py-4 text-center text-xs text-gray-500">
                        Protegemos a sua privacidade. Nenhum dado é partilhado.
                    </div>
                </v-card>
            </div>
        </div>
    </GuestLayout>
</template>
<script setup lang="ts">
import GuestLayout from '@/layouts/GuestLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const showPassword = ref(false);

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    console.log(form);

    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

const handleEmailInput = () => {
    form.clearErrors('email');
    if (!form.email) {
        form.setError('email', 'Email is required');
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.email)) {
        form.setError('email', 'Invalid email format');
    }
};
</script>
