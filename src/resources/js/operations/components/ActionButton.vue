<template>
    <button
        :type="type"
        :disabled="disabled || loading"
        :class="classes"
    >
        <span v-if="loading">Working...</span>
        <slot v-else />
    </button>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    type: { type: String, default: 'button' },
    tone: { type: String, default: 'primary' },
    loading: Boolean,
    disabled: Boolean,
});

const classes = computed(() => [
    'inline-flex items-center justify-center rounded-full px-4 py-2 text-sm font-semibold transition disabled:cursor-not-allowed disabled:opacity-60',
    props.tone === 'primary' && 'bg-ops-primary text-white hover:bg-[#2b2b2b]',
    props.tone === 'danger' && 'bg-ops-danger text-white hover:bg-[#8f3025]',
    props.tone === 'ghost' && 'border border-ops-border bg-ops-surface text-ops-text hover:bg-[#f7f3ec]',
]);
</script>
