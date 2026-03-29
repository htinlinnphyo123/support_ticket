<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    meta: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div class="mt-4 flex flex-col md:flex-row justify-between items-center space-y-3 md:space-y-0" v-if="meta && meta.links">
        <div class="text-sm text-gray-500">
            Showing {{ meta.from || 0 }} to {{ meta.to || 0 }} of {{ meta.total }} entries
        </div>
        <div class="flex gap-1 overflow-x-auto">
            <Link 
                v-for="(link, index) in meta.links" 
                :key="index" 
                :href="link.url || '#'"
                v-html="link.label"
                class="px-3 py-1 text-sm border rounded text-center whitespace-nowrap"
                :class="{
                    'bg-blue-600 border-blue-600 text-white': link.active,
                    'bg-white text-gray-700 hover:bg-gray-100': !link.active && link.url,
                    'bg-gray-100 text-gray-400 cursor-not-allowed': !link.url
                }"
            />
        </div>
    </div>
</template>
