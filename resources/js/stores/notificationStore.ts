import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useNotificationStore = defineStore('notification', () => {
    const notificationMessage = ref<string>('');
    const notificationType = ref<'success' | 'error'>('success');

    const showNotification = () => {
        return !!notificationMessage.value;
    };

    const setNotification = (message: string, type: 'success' | 'error' = 'success') => {
        notificationMessage.value = message;
        notificationType.value = type;
    };

    const clearNotification = () => {
        notificationMessage.value = '';
        notificationType.value = 'success';
    };

    return {
        notificationMessage,
        notificationType,
        showNotification,
        setNotification,
        clearNotification,
    };
});
