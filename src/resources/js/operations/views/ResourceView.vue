<template>
    <main class="p-5 sm:p-8">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-ops-muted">Records</p>
                <h1 class="mt-2 text-3xl font-semibold tracking-[-0.04em] text-ops-text">{{ config.title }}</h1>
            </div>
            <ActionButton v-if="!config.readonly" @click="openCreate">{{ config.createLabel || 'New record' }}</ActionButton>
        </div>

        <form v-if="config.filters.length" class="ops-card mt-6 grid gap-3 rounded-2xl p-4 sm:grid-cols-2 lg:grid-cols-4" @submit.prevent="load">
            <label v-for="filter in config.filters" :key="filter">
                <span class="ops-label">{{ label(filter) }}</span>
                <input v-model="filters[filter]" class="ops-input mt-2" :type="dateFilter(filter) ? 'date' : 'text'">
            </label>
            <div class="flex items-end gap-2">
                <ActionButton type="submit" tone="ghost" :loading="loading">Apply</ActionButton>
                <ActionButton type="button" tone="ghost" @click="clearFilters">Clear</ActionButton>
            </div>
        </form>

        <LoadingState v-if="loading" class="mt-6" />
        <div v-else-if="error" class="ops-card mt-6 rounded-2xl p-6">
            <p class="font-semibold text-ops-danger">Could not load {{ config.title.toLowerCase() }}.</p>
            <p class="mt-2 text-sm text-ops-muted">{{ error.message }}</p>
            <ActionButton class="mt-4" tone="ghost" @click="load">Retry</ActionButton>
        </div>
        <EmptyState v-else-if="!records.length" class="mt-6">
            <template #title>No {{ config.title.toLowerCase() }} found.</template>
        </EmptyState>
        <RecordTable v-else class="mt-6" :records="records" :columns="config.columns" @select="openRecord" />

        <div v-if="meta" class="mt-4 text-sm text-ops-muted">
            Showing page {{ meta.current_page || 1 }} of {{ meta.last_page || 1 }} · {{ meta.total ?? records.length }} total
        </div>

        <RecordDrawer :open="drawerOpen" :title="drawerTitle" @close="closeDrawer">
            <ResourceForm
                v-if="mode !== 'show'"
                class="mt-6"
                :fields="activeFields"
                :record="selected"
                :option-state="optionState"
                :submitting="submitting"
                :error="formError"
                :submit-label="submitLabel"
                @submit="save"
            />

            <div v-else-if="selected" class="mt-6 space-y-6">
                <dl class="grid gap-3">
                    <div v-for="column in Object.keys(selected)" :key="column" class="rounded-xl border border-ops-border p-3">
                        <dt class="ops-label">{{ label(column) }}</dt>
                        <dd class="mt-1 break-words text-sm text-ops-text">{{ display(selected[column]) }}</dd>
                    </div>
                </dl>
                <div class="flex flex-wrap gap-2">
                    <ActionButton v-if="!config.readonly && !config.createOnly" tone="ghost" @click="mode = 'edit'">Edit</ActionButton>
                    <ActionButton v-if="resourceName === 'prospects' && selected.status !== 'approved'" tone="ghost" @click="mode = 'approve'">Approve</ActionButton>
                    <ActionButton v-if="!config.readonly && !config.createOnly" tone="danger" :loading="submitting" @click="destroy">Delete</ActionButton>
                </div>
            </div>
        </RecordDrawer>
    </main>
</template>

<script setup>
import { computed, onMounted, reactive, ref, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import ActionButton from '../components/ActionButton.vue';
import EmptyState from '../components/EmptyState.vue';
import LoadingState from '../components/LoadingState.vue';
import RecordDrawer from '../components/RecordDrawer.vue';
import RecordTable from '../components/RecordTable.vue';
import { api } from '../api';
import { resources } from '../resources';
import ResourceForm from './partials/ResourceForm.vue';

const props = defineProps({ resourceName: { type: String, required: true } });
const route = useRoute();
const router = useRouter();

const config = computed(() => resources[props.resourceName]);
const records = ref([]);
const meta = ref(null);
const loading = ref(false);
const submitting = ref(false);
const error = ref(null);
const formError = ref(null);
const drawerOpen = ref(false);
const selected = ref(null);
const mode = ref('show');
const filters = reactive({});
const optionState = reactive({});

const activeFields = computed(() => {
    if (mode.value !== 'approve') return config.value.fields;
    return selected.value?.prospect_type === 'brand' ? config.value.approveBrandFields : config.value.approveFields;
});
const drawerTitle = computed(() => {
    if (mode.value === 'create') return `Create ${config.value.title}`;
    if (mode.value === 'edit') return `Edit ${selected.value?.name || selected.value?.handle || 'record'}`;
    if (mode.value === 'approve') return `Approve ${selected.value?.name || selected.value?.handle || 'prospect'}`;
    return selected.value?.name || selected.value?.handle || config.value.title;
});
const submitLabel = computed(() => mode.value === 'approve' ? 'Approve prospect' : mode.value === 'create' ? 'Create' : 'Save changes');

watch(() => props.resourceName, () => {
    Object.keys(filters).forEach((key) => delete filters[key]);
    restoreFilters();
    loadOptions();
    load();
});

const optionSources = computed(() => {
    const sources = new Map();
    const fields = [config.value.fields, config.value.approveFields, config.value.approveBrandFields].flat().filter(Boolean);

    fields.forEach((field) => {
        const type = field[2];
        const fieldConfig = field[4] || {};

        if (['relation', 'multi-relation'].includes(type) && fieldConfig.source) {
            sources.set(fieldConfig.source, fieldConfig);
        }

        if (type === 'owner-relation') {
            (fieldConfig.sources || []).forEach((source) => sources.set(source.source, source));
        }
    });

    return [...sources.entries()].map(([source, sourceConfig]) => ({ source, ...sourceConfig }));
});

function restoreFilters() {
    config.value.filters.forEach((filter) => {
        filters[filter] = route.query[filter] || '';
    });
}

async function syncQuery() {
    await router.replace({ query: Object.fromEntries(Object.entries(filters).filter(([, value]) => value)) });
}

async function load() {
    loading.value = true;
    error.value = null;
    await syncQuery();
    try {
        const response = await api.list(config.value.path, { ...filters, per_page: 25 });
        records.value = response.data || [];
        meta.value = response.meta || null;
    } catch (caught) {
        error.value = caught;
    } finally {
        loading.value = false;
    }
}

async function loadOptions() {
    await Promise.all(optionSources.value.map(async (source) => {
        optionState[source.source] = { options: [], loading: true, error: '' };
        try {
            const response = await api.list(source.path || `/${source.source}`, { per_page: source.perPage || 100 });
            optionState[source.source] = {
                options: (response.data || []).map((record) => ({
                    value: record[source.value || 'id'],
                    label: optionLabel(record, source.labelField || 'name'),
                })),
                loading: false,
                error: '',
            };
        } catch (caught) {
            optionState[source.source] = {
                options: [],
                loading: false,
                error: caught.message || 'Options could not load.',
            };
        }
    }));
}

function clearFilters() {
    Object.keys(filters).forEach((key) => filters[key] = '');
    load();
}

function openCreate() {
    selected.value = {};
    formError.value = null;
    mode.value = 'create';
    drawerOpen.value = true;
}

function openRecord(record) {
    selected.value = record;
    formError.value = null;
    mode.value = 'show';
    drawerOpen.value = true;
}

function closeDrawer() {
    drawerOpen.value = false;
}

async function save(payload) {
    submitting.value = true;
    formError.value = null;
    try {
        if (mode.value === 'approve') {
            const approve = selected.value.prospect_type === 'brand' ? api.approveProspectAsBrand : api.approveProspectAsCreator;
            const response = await approve(selected.value.id, payload);
            selected.value = response.data;
            mode.value = 'show';
        } else if (mode.value === 'create') {
            const response = await api.create(config.value.path, payload);
            selected.value = response.data;
            mode.value = 'show';
        } else {
            const response = await api.update(config.value.path, selected.value.id, payload);
            selected.value = response.data;
            mode.value = 'show';
        }
        await load();
    } catch (caught) {
        formError.value = caught;
    } finally {
        submitting.value = false;
    }
}

async function destroy() {
    if (!window.confirm('Delete this record?')) return;

    submitting.value = true;
    try {
        await api.delete(config.value.path, selected.value.id);
        closeDrawer();
        await load();
    } finally {
        submitting.value = false;
    }
}

function label(value) {
    return value.replaceAll('_', ' ');
}

function display(value) {
    if (Array.isArray(value)) return `${value.length} items`;
    if (typeof value === 'boolean') return value ? 'Yes' : 'No';
    if (value && typeof value === 'object') return JSON.stringify(value);
    return value ?? '—';
}

function optionLabel(record, labelField) {
    if (Array.isArray(labelField)) {
        return labelField.map((field) => record[field]).filter(Boolean).join(' · ') || `#${record.id}`;
    }

    return record[labelField] || record.name || record.handle || record.slug || `#${record.id}`;
}

function dateFilter(filter) {
    return filter.endsWith('_at') || filter.startsWith('starts') || filter.startsWith('ends');
}

onMounted(() => {
    restoreFilters();
    loadOptions();
    load();
});
</script>
