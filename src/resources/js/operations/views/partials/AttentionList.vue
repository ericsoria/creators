<template>
    <section class="ops-card rounded-2xl p-5">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-ops-text">{{ title }}</h2>
            <RouterLink :to="to" class="text-sm font-semibold text-ops-primary">Open</RouterLink>
        </div>
        <ul v-if="items?.length" class="mt-4 divide-y divide-ops-border">
            <li v-for="item in items" :key="item.id" class="py-3 text-sm">
                <p class="font-medium text-ops-text">{{ item.name || item.handle || `#${item.id}` }}</p>
                <p class="mt-1 text-ops-muted">{{ item.status || item.platform || item.username || 'Needs review' }}</p>
            </li>
        </ul>
        <p v-else class="mt-4 text-sm text-ops-muted">No immediate action.</p>
    </section>
</template>

<script setup>
defineProps({
    title: { type: String, required: true },
    to: { type: String, required: true },
    items: { type: Array, default: () => [] },
});
</script>
