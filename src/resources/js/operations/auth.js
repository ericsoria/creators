import { reactive } from 'vue';
import { api, onUnauthorized } from './api';
import { authTokenKey } from './config';

export const auth = reactive({
    token: window.localStorage.getItem(authTokenKey),
    user: null,
    booted: false,
    loading: false,
});

export function hasToken() {
    return Boolean(auth.token);
}

export function setToken(token) {
    auth.token = token;
    if (token) {
        window.localStorage.setItem(authTokenKey, token);
    } else {
        window.localStorage.removeItem(authTokenKey);
    }
}

export async function bootstrapAuth() {
    if (auth.booted) {
        return auth.user;
    }

    auth.loading = true;
    try {
        if (auth.token) {
            const response = await api.user();
            auth.user = response.data;
        }
    } finally {
        auth.booted = true;
        auth.loading = false;
    }

    return auth.user;
}

export async function login(credentials) {
    auth.loading = true;
    try {
        const response = await api.login(credentials);
        setToken(response.data.token);
        auth.user = response.data.user.data || response.data.user;
        auth.booted = true;
        return auth.user;
    } finally {
        auth.loading = false;
    }
}

export async function logout() {
    try {
        if (auth.token) {
            await api.logout();
        }
    } finally {
        auth.user = null;
        auth.booted = true;
        setToken(null);
    }
}

export function clearAuth() {
    auth.user = null;
    auth.booted = true;
    setToken(null);
}

onUnauthorized(clearAuth);
