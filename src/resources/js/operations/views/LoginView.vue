<template>
    <main class="min-h-screen bg-ops-bg px-6 py-10 text-ops-text">
        <section class="mx-auto grid min-h-[calc(100vh-5rem)] max-w-5xl items-center gap-10 lg:grid-cols-[1.1fr_0.9fr]">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-ops-muted">Creators operations</p>
                <h1 class="mt-4 max-w-xl text-4xl font-semibold tracking-[-0.04em] text-ops-text sm:text-6xl">
                    Quiet control for creator work.
                </h1>
                <p class="mt-5 max-w-lg text-base leading-7 text-ops-muted">
                    Manage prospects, creators, brands, campaigns, and settings from one API-driven cockpit.
                </p>
            </div>

            <form class="ops-card rounded-3xl p-6 sm:p-8" @submit.prevent="submit">
                <h2 class="text-2xl font-semibold tracking-[-0.03em]">Sign in</h2>
                <p class="mt-2 text-sm text-ops-muted">Use your internal account credentials.</p>

                <div class="mt-8 space-y-5">
                    <label class="block">
                        <span class="ops-label">Email</span>
                        <input v-model="form.email" class="ops-input mt-2" type="email" autocomplete="email" required>
                        <span v-if="fieldError('email')" class="mt-1 block text-sm text-ops-danger">{{ fieldError('email') }}</span>
                    </label>
                    <label class="block">
                        <span class="ops-label">Password</span>
                        <input v-model="form.password" class="ops-input mt-2" type="password" autocomplete="current-password" required>
                        <span v-if="fieldError('password')" class="mt-1 block text-sm text-ops-danger">{{ fieldError('password') }}</span>
                    </label>
                </div>

                <p v-if="error && !Object.keys(error.errors || {}).length" class="mt-5 text-sm text-ops-danger">{{ error.message }}</p>

                <ActionButton class="mt-8 w-full" type="submit" :loading="auth.loading">Enter operations</ActionButton>
            </form>
        </section>
    </main>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import ActionButton from '../components/ActionButton.vue';
import { auth, login } from '../auth';
import { validationMessage } from '../api';

const route = useRoute();
const router = useRouter();
const form = reactive({ email: '', password: '' });
const error = ref(null);

async function submit() {
    error.value = null;
    try {
        await login(form);
        await router.push(route.query.redirect || { name: 'dashboard' });
    } catch (caught) {
        error.value = caught;
    }
}

function fieldError(field) {
    return validationMessage(error.value, field);
}
</script>
