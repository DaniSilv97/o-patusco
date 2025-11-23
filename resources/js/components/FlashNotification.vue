<template>
    <v-snackbar
        v-model="showNotif"
        :color="notificationStore.notificationType === 'success' ? 'success' : 'error'"
        timeout="4000"
        location="top right"
    >
        <template v-slot:default>
            <div class="flex items-center gap-3">
                <v-icon>
                    {{ notificationStore.notificationType === 'success' ? 'mdi-check-circle' : 'mdi-alert-circle' }}
                </v-icon>
                <span>{{ notificationStore.notificationMessage }}</span>
            </div>
        </template>
    </v-snackbar>
</template>

<script setup lang="ts">
import { useNotificationStore } from '@/stores/notificationStore';
import { usePage } from '@inertiajs/vue3';
import { computed, watch } from 'vue';

const page = usePage<{
    flash?: {
        success?: string;
        error?: string;
    };
}>();

const notificationStore = useNotificationStore();

const showNotif = computed({
    get: () => notificationStore.showNotification(),
    set: (value) => {
        if (!value) {
            notificationStore.clearNotification();
        }
    },
});

watch(
    () => page.props.flash,
    (flash) => {
        if (flash?.success) {
            notificationStore.setNotification(flash.success, 'success');
        } else if (flash?.error) {
            notificationStore.setNotification(flash.error, 'error');
        }
    },
    { deep: true, immediate: true },
);
</script>
