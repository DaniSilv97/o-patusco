import { useAuthStore } from '@/stores/useAuthStore';
import type { DirectiveBinding } from 'vue';

// Map gate names to role names
const gateToRoleMap: Record<string, string> = {
    'is-client': 'Cliente',
    'is-receptionist': 'Rececionista',
    'is-veterinarian': 'Veterinário',
};

const resolveRole = (gate: string): string => {
    return gateToRoleMap[gate] || gate;
};

export const vCan = {
    mounted(el: HTMLElement, binding: DirectiveBinding<string | string[]>) {
        const authStore = useAuthStore();
        const gates = Array.isArray(binding.value) ? binding.value : [binding.value];

        if (gates.length === 0) {
            console.warn('[v-can] At least one gate is required');
            return;
        }

        const roles = gates.map(resolveRole);
        const hasAccess = roles.some((role) => authStore.hasRole(role));

        if (!hasAccess) {
            el.style.display = 'none';
            el.setAttribute('aria-hidden', 'true');
        }
    },
    updated(el: HTMLElement, binding: DirectiveBinding<string | string[]>) {
        const authStore = useAuthStore();
        const gates = Array.isArray(binding.value) ? binding.value : [binding.value];

        if (gates.length === 0) {
            console.warn('[v-can] At least one gate is required');
            return;
        }

        const roles = gates.map(resolveRole);
        const hasAccess = roles.some((role) => authStore.hasRole(role));

        if (!hasAccess) {
            el.style.display = 'none';
            el.setAttribute('aria-hidden', 'true');
        } else {
            el.style.display = '';
            el.removeAttribute('aria-hidden');
        }
    },
};

export default vCan;
