<template>
    <div class="min-h-[70vh] bg-gray-50">
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <article
                v-for="card in cards"
                :key="card.key"
                class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm"
            >
                <p class="text-sm font-medium text-gray-500">{{ card.label }}</p>
                <p class="mt-2 text-3xl font-bold text-gray-900">{{ card.value }}</p>
            </article>
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-[1.4fr_.8fr]">
            <section class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-base font-semibold text-gray-900">Import rapide</h2>
                    <a href="/admin/imports" class="rounded-md bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-primary-600">
                        Ouvrir
                    </a>
                </div>
                <p class="mt-3 text-sm text-gray-600">
                    Import CSV/XLSX pour institutions, filieres, etudiants, enseignants, inscriptions, accreditations et calendrier.
                </p>
            </section>

            <section class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                <h2 class="text-base font-semibold text-gray-900">Controle public</h2>
                <p class="mt-3 text-sm text-gray-600">
                    Les visiteurs peuvent verifier un etablissement, une formation ou un etudiant sans compte.
                </p>
            </section>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    stats: { type: Object, default: () => ({}) },
});

const cards = computed(() => [
    { key: 'institutions', label: 'Etablissements', value: props.stats.institutions ?? 0 },
    { key: 'filieres', label: 'Formations', value: props.stats.filieres ?? 0 },
    { key: 'etudiants', label: 'Etudiants', value: props.stats.etudiants ?? 0 },
    { key: 'enseignants', label: 'Enseignants', value: props.stats.enseignants ?? 0 },
]);
</script>
