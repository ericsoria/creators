<template>
    <main class="p-5 sm:p-8">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-ops-muted">Today</p>
                <h1 class="mt-2 text-3xl font-semibold tracking-[-0.04em] text-ops-text">Operational overview</h1>
                <p class="mt-2 text-sm text-ops-muted">Counts, queues, and shortcuts that point to the next action.</p>
            </div>
            <ActionButton tone="ghost" :loading="loading" @click="load">Refresh</ActionButton>
        </div>

        <LoadingState v-if="loading" class="mt-8" />
        <div v-else-if="error" class="ops-card mt-8 rounded-2xl p-6">
            <p class="font-semibold text-ops-danger">Dashboard could not load.</p>
            <p class="mt-2 text-sm text-ops-muted">{{ error.message }}</p>
            <ActionButton class="mt-4" tone="ghost" @click="load">Retry</ActionButton>
        </div>
        <section v-else class="mt-8 space-y-8">
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
                <RouterLink v-for="card in cards" :key="card.label" :to="card.to" class="ops-card rounded-2xl p-5 hover:border-ops-primary">
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-ops-muted">{{ card.label }}</p>
                    <p class="mt-4 text-3xl font-semibold tracking-[-0.05em]">{{ card.value }}</p>
                </RouterLink>
            </div>

            <div class="grid gap-4 lg:grid-cols-2">
                <AttentionList title="Interested prospects" to="/app/prospects" :items="overview.attention.interestedLeads" />
                <AttentionList title="Draft campaigns" to="/app/campaigns" :items="overview.attention.draftCampaigns" />
                <AttentionList title="Active campaigns" to="/app/campaigns" :items="overview.attention.activeCampaigns" />
                <AttentionList title="Recently added creators" to="/app/creators" :items="overview.attention.recentCreators" />
            </div>
        </section>
    </main>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import ActionButton from '../components/ActionButton.vue';
import LoadingState from '../components/LoadingState.vue';
import { dashboardClient } from '../resources';
import AttentionList from './partials/AttentionList.vue';

const loading = ref(false);
const error = ref(null);
const overview = ref({ counts: {}, attention: {} });

const cards = computed(() => [
    { label: 'Prospects', value: overview.value.counts.leads || 0, to: '/app/prospects' },
    { label: 'Creators', value: overview.value.counts.creators || 0, to: '/app/creators' },
    { label: 'Brands', value: overview.value.counts.brands || 0, to: '/app/brands' },
    { label: 'Active campaigns', value: overview.value.counts.activeCampaigns || 0, to: '/app/campaigns' },
    { label: 'Draft campaigns', value: overview.value.counts.draftCampaigns || 0, to: '/app/campaigns' },
]);

async function load() {
    loading.value = true;
    error.value = null;
    try {
        overview.value = await dashboardClient.overview();
    } catch (caught) {
        error.value = caught;
    } finally {
        loading.value = false;
    }
}

onMounted(load);
</script>
