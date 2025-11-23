import { usePage } from '@inertiajs/vue3';
import { defineStore } from 'pinia';
import { computed, ref, watch } from 'vue';

interface User {
    id: string;
    name: string;
    email: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

type Roles = string[];

interface PageProps {
    auth?: {
        user?: User;
        roles?: Roles;
    };
}

export const useAuthStore = defineStore('auth', () => {
    const page = usePage();

    const user = ref<User | undefined>((page.props as PageProps).auth?.user);
    const roles = ref<Roles>((page.props as PageProps).auth?.roles || []);

    watch(
        () => (page.props as PageProps).auth,
        (newAuth) => {
            user.value = newAuth?.user;
            roles.value = newAuth?.roles || [];
        },
    );

    const setUser = (newUser: User | undefined) => {
        user.value = newUser;
    };

    const setRoles = (newRoles: Roles) => {
        roles.value = newRoles;
    };

    const isAuthenticated = computed(() => !!user.value);

    const hasRole = (role: string) => {
        return roles.value.includes(role);
    };

    return {
        user,
        roles,
        setUser,
        setRoles,
        isAuthenticated,
        hasRole,
    };
});
