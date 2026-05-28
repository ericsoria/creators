<template>
    <div class="ops-card rounded-2xl">
        <div class="ops-table-wrap hidden sm:block">
            <table class="min-w-full text-left text-sm">
                <thead class="text-xs uppercase tracking-[0.12em] text-ops-muted">
                    <tr>
                        <th v-for="column in columns" :key="column" class="border-b border-ops-border px-4 py-3 font-semibold">
                            {{ label(column) }}
                        </th>
                        <th class="border-b border-ops-border px-4 py-3 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="record in records" :key="record.id" class="border-b border-ops-border last:border-b-0">
                        <td v-for="column in columns" :key="column" class="max-w-[16rem] truncate px-4 py-3 text-ops-text">
                            {{ value(record, column) }}
                        </td>
                        <td class="px-4 py-3">
                            <button class="text-sm font-semibold text-ops-primary underline-offset-4 hover:underline" @click="$emit('select', record)">
                                Open
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="divide-y divide-ops-border sm:hidden">
            <article v-for="record in records" :key="record.id" class="p-4">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="font-semibold text-ops-text">{{ record.name || record.handle || `#${record.id}` }}</h3>
                        <p class="text-xs text-ops-muted">ID {{ record.id }}</p>
                    </div>
                    <button class="text-sm font-semibold text-ops-primary" @click="$emit('select', record)">Open</button>
                </div>
                <dl class="mt-4 grid grid-cols-1 gap-2 text-sm">
                    <div v-for="column in columns" :key="column" class="flex justify-between gap-4">
                        <dt class="text-ops-muted">{{ label(column) }}</dt>
                        <dd class="text-right text-ops-text">{{ value(record, column) }}</dd>
                    </div>
                </dl>
            </article>
        </div>
    </div>
</template>

<script setup>
defineProps({
    records: { type: Array, required: true },
    columns: { type: Array, required: true },
});

defineEmits(['select']);

function label(column) {
    return column.replaceAll('_', ' ');
}

function value(record, column) {
    const output = record[column];
    if (typeof output === 'boolean') {
        return output ? 'Yes' : 'No';
    }
    if (output === null || output === undefined || output === '') {
        return '—';
    }
    return output;
}
</script>
