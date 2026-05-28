<template>
    <Teleport to="body">
        <div v-if="open" class="fixed inset-0 z-50" role="presentation">
            <button class="absolute inset-0 bg-black/20" aria-label="Close drawer" @click="$emit('close')"></button>
            <aside
                ref="drawer"
                role="dialog"
                aria-modal="true"
                :aria-label="title"
                tabindex="-1"
                class="absolute right-0 top-0 flex h-full w-full max-w-xl flex-col overflow-y-auto border-l border-ops-border bg-ops-surface p-6 shadow-2xl transition sm:w-[32rem]"
                @keydown="handleKeydown"
            >
                <div class="flex items-start justify-between gap-4 border-b border-ops-border pb-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-ops-muted">Record drawer</p>
                        <h2 class="mt-1 text-xl font-semibold text-ops-text">{{ title }}</h2>
                    </div>
                    <button class="rounded-full border border-ops-border px-3 py-1 text-sm text-ops-muted" @click="$emit('close')">Close</button>
                </div>
                <slot />
            </aside>
        </div>
    </Teleport>
</template>

<script setup>
import { nextTick, ref, watch } from 'vue';

const props = defineProps({
    open: Boolean,
    title: { type: String, default: 'Record' },
});

const emit = defineEmits(['close']);
const drawer = ref(null);
let trigger = null;

watch(() => props.open, async (open) => {
    if (open) {
        trigger = document.activeElement;
        await nextTick();
        drawer.value?.focus();
    } else if (trigger instanceof HTMLElement) {
        trigger.focus();
    }
});

function focusable() {
    return [...drawer.value.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])')]
        .filter((element) => !element.disabled && element.offsetParent !== null);
}

function handleKeydown(event) {
    if (event.key === 'Escape') {
        emit('close');
        return;
    }

    if (event.key !== 'Tab') {
        return;
    }

    const items = focusable();
    if (!items.length) {
        event.preventDefault();
        return;
    }

    const first = items[0];
    const last = items[items.length - 1];

    if (event.shiftKey && document.activeElement === first) {
        event.preventDefault();
        last.focus();
    } else if (!event.shiftKey && document.activeElement === last) {
        event.preventDefault();
        first.focus();
    }
}
</script>
