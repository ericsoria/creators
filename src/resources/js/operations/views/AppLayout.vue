<template>
    <div class="min-h-screen bg-ops-bg text-ops-text">
        <div class="flex min-h-screen flex-col lg:flex-row">
            <aside class="border-b border-ops-border bg-ops-surface/80 px-4 py-4 lg:w-64 lg:border-b-0 lg:border-r lg:px-5">
                <RouterLink to="/app/dashboard" class="block rounded-2xl border border-ops-border bg-ops-bg px-4 py-3">
                    <span class="block text-xs font-semibold uppercase tracking-[0.2em] text-ops-muted">La Gamberra</span>
                    <span class="mt-1 block text-lg font-semibold tracking-[-0.03em]">Operations</span>
                </RouterLink>

                <nav class="mt-5 space-y-5" aria-label="Primary">
                    <div class="grid grid-cols-2 gap-2 lg:grid-cols-1">
                        <RouterLink v-for="item in primaryNav" :key="item.to" :to="item.to" class="rounded-xl px-3 py-2 text-sm font-medium text-ops-muted hover:bg-ops-bg hover:text-ops-text" active-class="bg-ops-primary text-white hover:bg-ops-primary hover:text-white">
                            {{ item.label }}
                        </RouterLink>
                    </div>

                    <div v-for="group in navGroups" :key="group.label" class="space-y-2">
                        <p class="px-3 text-xs font-semibold uppercase tracking-[0.18em] text-ops-muted">{{ group.label }}</p>
                        <div class="grid grid-cols-2 gap-2 lg:grid-cols-1">
                            <RouterLink v-for="item in group.items" :key="item.to" :to="item.to" class="rounded-xl px-3 py-2 text-sm font-medium text-ops-muted hover:bg-ops-bg hover:text-ops-text" active-class="bg-ops-primary text-white hover:bg-ops-primary hover:text-white">
                                {{ item.label }}
                            </RouterLink>
                        </div>
                    </div>
                </nav>
            </aside>

            <div class="min-w-0 flex-1">
                <header class="sticky top-0 z-30 border-b border-ops-border bg-ops-bg/90 px-5 py-4 backdrop-blur">
                    <div class="flex items-center justify-between gap-4">
                        <p class="text-sm text-ops-muted">
                            Signed in as <span class="font-semibold text-ops-text">{{ auth.user?.name || 'operator' }}</span>
                        </p>
                        <ActionButton tone="ghost" :loading="leaving" @click="leave">Logout</ActionButton>
                    </div>
                </header>

                <RouterView />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import ActionButton from '../components/ActionButton.vue';
import { auth, logout } from '../auth';

const router = useRouter();
const leaving = ref(false);

const primaryNav = [
    { label: 'Dashboard', to: '/app/dashboard' },
    { label: 'Prospects', to: '/app/prospects' },
    { label: 'Creators', to: '/app/creators' },
    { label: 'Brands', to: '/app/brands' },
];

const navGroups = [
    {
        label: 'Brands',
        items: [
            { label: 'Campaigns', to: '/app/campaigns' },
        ],
    },
    {
        label: 'Settings',
        items: [
            { label: 'Cities', to: '/app/cities' },
            { label: 'Tags', to: '/app/tags' },
            { label: 'Social Accounts', to: '/app/social-accounts' },
        ],
    },
];

async function leave() {
    leaving.value = true;
    await logout();
    leaving.value = false;
    await router.push({ name: 'login' });
}
</script>
