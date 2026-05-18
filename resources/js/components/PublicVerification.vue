<template>
    <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-xl shadow-green-100/50">
        <div class="border-b border-gray-100 bg-gray-50 p-5 md:p-6">
            <div class="flex flex-wrap gap-2">
                <button v-for="tab in tabs" :key="tab.key" type="button"
                    class="rounded-xl px-4 py-2 text-sm font-semibold transition"
                    :class="activeTab === tab.key ? 'bg-green-700 text-white shadow-sm' : 'bg-white text-gray-700 ring-1 ring-gray-100 hover:bg-green-50 hover:text-green-700'"
                    @click="activeTab = tab.key">
                    {{ tab.label }}
                </button>
            </div>
        </div>

        <form class="space-y-5 p-5 md:p-6" method="post" :action="formAction">
            <input type="hidden" name="_token" :value="csrfToken">
            <input type="hidden" name="type" :value="activeTab">

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700" :for="inputId">
                    {{ currentTab.inputLabel }}
                </label>
                <input :id="inputId" :name="currentTab.inputName" type="text" required
                    class="block min-h-12 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-green-600 focus:outline-none focus:ring-2 focus:ring-green-100"
                    :placeholder="currentTab.placeholder">
            </div>

            <div class="grid gap-3 sm:grid-cols-[auto_1fr] sm:items-center">
                <div class="rounded-xl bg-gray-100 px-5 py-3 text-center font-mono text-lg font-bold text-gray-800">
                    {{ captcha }}
                </div>
                <div>
                    <label class="sr-only" for="captcha">{{ labels.securityCode }}</label>
                    <input id="captcha" name="captcha" type="text" maxlength="6" required
                        class="block min-h-12 w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-green-600 focus:outline-none focus:ring-2 focus:ring-green-100"
                        :placeholder="labels.copyCode">
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                <button type="submit"
                    class="inline-flex min-h-12 items-center justify-center rounded-xl bg-green-700 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-green-100 transition hover:bg-green-800">
                    {{ labels.verify }}
                </button>

            </div>
        </form>
    </section>
</template>

<script setup>
import { computed, ref } from 'vue';

const props = defineProps({
    formAction: { type: String, required: true },
    csrfToken: { type: String, required: true },
    captcha: { type: String, required: true },
    labels: { type: Object, required: true },
    tabs: { type: Array, required: true },
});

const activeTab = ref('student');
const tabs = computed(() => props.tabs);
const labels = computed(() => props.labels);
const currentTab = computed(() => tabs.value.find((tab) => tab.key === activeTab.value) ?? tabs.value[0]);
const inputId = computed(() => `verify-${currentTab.value.inputName}`);
</script>
