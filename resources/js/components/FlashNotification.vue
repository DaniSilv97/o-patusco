<template>
    <v-snackbar v-model="showNotification" :color="notificationType === 'success' ? 'success' : 'error'" timeout="4000" location="top right">
        <template v-slot:default>
            <div class="flex items-center gap-3">
                <v-icon>
                    {{ notificationType === 'success' ? 'mdi-check-circle' : 'mdi-alert-circle' }}
                </v-icon>
                <span>{{ notificationMessage }}</span>
            </div>
        </template>
    </v-snackbar>
</template>

<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';

const page = usePage<{
    flash?: {
        success?: string;
        error?: string;
    };
}>();

const notificationMessage = ref<string>('');
const notificationType = ref<'success' | 'error'>('success');

const showNotification = computed({
    get: () => !!notificationMessage.value,
    set: (value) => {
        if (!value) {
            notificationMessage.value = '';
            notificationType.value = 'success';
        }
    },
});

watch(
    () => page.props.flash,
    (flash) => {
        if (flash?.success) {
            notificationMessage.value = flash.success;
            notificationType.value = 'success';
        } else if (flash?.error) {
            notificationMessage.value = flash.error;
            notificationType.value = 'error';
        }
    },
    { deep: true },
);
</script>
