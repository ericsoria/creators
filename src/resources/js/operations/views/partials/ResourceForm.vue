<template>
    <form class="space-y-5" @submit.prevent="submit">
        <div v-if="error && !Object.keys(error.errors || {}).length" class="rounded-xl border border-ops-danger/30 bg-[#fff7f1] p-3 text-sm text-ops-danger">
            {{ error.message }}
        </div>

        <label v-for="field in fields" :key="field[0]" class="block">
            <span class="ops-label">{{ field[1] }}</span>
            <textarea
                v-if="field[2] === 'textarea'"
                v-model="form[field[0]]"
                class="ops-input mt-2 min-h-24"
                :required="field[3]"
                :disabled="submitting"
            ></textarea>
            <select
                v-else-if="field[2] === 'relation'"
                v-model="form[field[0]]"
                class="ops-input mt-2"
                :required="field[3]"
                :disabled="submitting || optionLoading(field)"
            >
                <option value="">{{ optionLoading(field) ? 'Loading...' : 'Choose' }}</option>
                <option v-for="option in relationOptions(field)" :key="option.value" :value="String(option.value)">{{ option.label }}</option>
            </select>
            <select
                v-else-if="field[2] === 'multi-relation'"
                v-model="form[field[0]]"
                class="ops-input mt-2 min-h-32"
                multiple
                :required="field[3]"
                :disabled="submitting || optionLoading(field)"
            >
                <option v-for="option in relationOptions(field)" :key="option.value" :value="String(option.value)">{{ option.label }}</option>
            </select>
            <select
                v-else-if="field[2] === 'owner-relation'"
                v-model="form[field[0]]"
                class="ops-input mt-2"
                :required="field[3]"
                :disabled="submitting || ownerLoading(field)"
            >
                <option value="">{{ ownerLoading(field) ? 'Loading...' : 'Choose owner' }}</option>
                <option v-for="option in ownerOptions(field)" :key="option.value" :value="option.value">{{ option.label }}</option>
            </select>
            <select
                v-else-if="field[2] === 'select'"
                v-model="form[field[0]]"
                class="ops-input mt-2"
                :required="field[3]"
                :disabled="submitting"
            >
                <option value="">Choose</option>
                <option v-for="option in field[4]" :key="option" :value="option">{{ option }}</option>
            </select>
            <label v-else-if="field[2] === 'checkbox'" class="mt-2 flex items-center gap-2 text-sm text-ops-text">
                <input v-model="form[field[0]]" type="checkbox" :disabled="submitting">
                Enabled
            </label>
            <input
                v-else
                v-model="form[field[0]]"
                class="ops-input mt-2"
                :type="field[2] === 'number' ? 'number' : field[2] === 'date' ? 'date' : 'text'"
                :required="field[3]"
                :disabled="submitting"
            >
            <span v-if="field[2] === 'multi-relation'" class="mt-1 block text-xs text-ops-muted">Hold Command or Shift to select multiple.</span>
            <span v-if="optionError(field)" class="mt-1 block text-sm text-ops-danger">{{ optionError(field) }}</span>
            <span v-if="fieldError(field[0])" class="mt-1 block text-sm text-ops-danger">{{ fieldError(field[0]) }}</span>
        </label>

        <ActionButton type="submit" :loading="submitting">{{ submitLabel }}</ActionButton>
    </form>
</template>

<script setup>
import { reactive, watch } from 'vue';
import { validationMessage } from '../../api';
import ActionButton from '../../components/ActionButton.vue';

const props = defineProps({
    fields: { type: Array, required: true },
    record: { type: Object, default: () => ({}) },
    optionState: { type: Object, default: () => ({}) },
    submitting: Boolean,
    error: { type: Object, default: null },
    submitLabel: { type: String, default: 'Save' },
});

const emit = defineEmits(['submit']);
const form = reactive({});

watch(() => [props.record, props.fields], hydrate, { immediate: true });

function hydrate() {
    props.fields.forEach((field) => {
        const [name, , type] = field;
        const config = fieldConfig(field);
        const value = props.record?.[name];
        if (type === 'checkbox') {
            form[name] = Boolean(value);
        } else if (type === 'csv') {
            form[name] = Array.isArray(value) ? value.map((item) => item.id || item).join(', ') : '';
        } else if (type === 'multi-relation') {
            const relationValue = config.relation ? props.record?.[config.relation] : value;
            form[name] = Array.isArray(relationValue) ? relationValue.map((item) => String(item.id || item)) : [];
        } else if (type === 'owner-relation') {
            form[name] = ownerValue(value) || ownerValue(props.record) || '';
        } else {
            form[name] = value ?? '';
        }
    });
}

function submit() {
    const payload = {};
    props.fields.forEach(([name, , type]) => {
        const value = form[name];
        if (type === 'checkbox') {
            payload[name] = Boolean(value);
        } else if (type === 'number' || type === 'relation') {
            payload[name] = value === '' ? null : Number(value);
        } else if (type === 'multi-relation') {
            payload[name] = Array.isArray(value) ? value.map(Number) : [];
        } else if (type === 'owner-relation') {
            const [accountableType, accountableId] = String(value || '').split('|');
            payload.accountable_type = accountableType || null;
            payload.accountable_id = accountableId ? Number(accountableId) : null;
        } else if (type === 'csv') {
            payload[name] = String(value || '')
                .split(',')
                .map((item) => item.trim())
                .filter(Boolean)
                .map(Number);
        } else if (value !== '') {
            payload[name] = value;
        } else {
            payload[name] = null;
        }
    });
    emit('submit', payload);
}

function fieldError(field) {
    if (field === 'owner') {
        return validationMessage(props.error, 'accountable_type') || validationMessage(props.error, 'accountable_id');
    }

    return validationMessage(props.error, field);
}

function fieldConfig(field) {
    return field[4] || {};
}

function relationOptions(field) {
    return props.optionState[fieldConfig(field).source]?.options || [];
}

function optionLoading(field) {
    return Boolean(props.optionState[fieldConfig(field).source]?.loading);
}

function optionError(field) {
    return props.optionState[fieldConfig(field).source]?.error || '';
}

function ownerOptions(field) {
    const config = fieldConfig(field);
    return (config.sources || []).flatMap((source) => {
        const state = props.optionState[source.source] || {};
        return (state.options || []).map((option) => ({
            value: `${source.type}|${option.value}`,
            label: `${source.label}: ${option.label}`,
        }));
    });
}

function ownerLoading(field) {
    return fieldConfig(field).sources?.some((source) => props.optionState[source.source]?.loading) || false;
}

function ownerValue(record) {
    if (!record?.accountable_type || !record?.accountable_id) return '';
    return `${record.accountable_type}|${record.accountable_id}`;
}
</script>
