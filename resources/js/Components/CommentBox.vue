<script setup>
import { computed } from 'vue';

const props = defineProps({
    comment: {
        type: Object,
        required: true
    }
});

const isAgent = computed(() => props.comment.creator_type === 'agent');
</script>

<template>
    <div 
        class="flex w-full mb-6" 
        :class="isAgent ? 'justify-start' : 'justify-end'"
    >
        <div 
            class="flex max-w-[85%] md:max-w-[75%] rounded-2xl p-4 gap-3 sm:gap-4"
            :class="[
                isAgent 
                    ? (comment.is_public ? 'bg-white border border-gray-200 shadow-sm rounded-tl-sm' : 'bg-yellow-50 border border-yellow-300 shadow-sm rounded-tl-sm') 
                    : 'bg-blue-600 text-white shadow-md rounded-tr-sm flex-row-reverse text-right'
            ]"
        >
            <!-- Avatar -->
            <div class="flex-none">
                <div 
                    class="w-10 h-10 rounded-full flex items-center justify-center font-bold shadow-sm"
                    :class="isAgent ? 'bg-blue-100 text-blue-700' : 'bg-blue-500 text-white border border-blue-400'"
                >
                    {{ comment.creator_name ? comment.creator_name.charAt(0) : '?' }}
                </div>
            </div>

            <!-- Content -->
            <div class="flex-grow">
                <div 
                    class="flex items-center gap-2 mb-2"
                    :class="!isAgent ? 'justify-end' : ''"
                >
                    <!-- Badges -->
                    <span v-if="isAgent && !comment.is_public" class="text-xs bg-yellow-200 text-yellow-800 px-2 py-0.5 rounded font-medium flex items-center gap-1" :class="!isAgent ? 'order-first' : ''">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 20"><path d="M14 7h-1.5V4.5a4.5 4.5 0 1 0-9 0V7H2a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2Zm-5 8a1 1 0 1 1-2 0v-3a1 1 0 1 1 2 0v3Zm1.5-8h-5V4.5a2.5 2.5 0 1 1 5 0V7Z"/></svg>
                        Internal Note
                    </span>
                
                    <span class="font-bold text-sm" :class="[isAgent ? 'text-gray-900' : 'text-blue-50', !isAgent ? 'order-last' : '']">
                        {{ comment.creator_name }}
                    </span>
                    <span class="text-xs opacity-75" :class="isAgent ? 'text-gray-500' : 'text-blue-200'">
                        {{ comment.created_at }}
                    </span>
                </div>
                
                <p 
                    class="whitespace-pre-wrap leading-relaxed text-sm"
                    :class="isAgent ? 'text-gray-800' : 'text-white'"
                >
                    {{ comment.description }}
                </p>
                
                <div v-if="comment.file" class="mt-3 flex" :class="!isAgent ? 'justify-end' : ''">
                    <a 
                        :href="comment.file" 
                        target="_blank" 
                        class="inline-flex items-center gap-1 text-xs px-3 py-1.5 rounded transition"
                        :class="isAgent ? 'bg-gray-100 text-gray-700 hover:bg-gray-200' : 'bg-blue-700 text-white hover:bg-blue-800 border border-blue-500'"
                    >
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 20"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 11.5c0 .3-.1.6-.2.8-.2.2-.4.4-.7.5-.3.1-.6.1-.8 0-.3-.1-.5-.2-.7-.4L4.8 8.6c-.6-.6-.7-1.6-.3-2.3.4-.7 1.2-1.1 2-.9l7.3 3.4c1 .5 1.7 1.5 1.7 2.6 0 1.2-.7 2.2-1.8 2.6L6 17c-1.6.7-3.4.1-4.3-1.4-1-.8-1.5-2-.6-3.2L5 8"/></svg>
                        Attachment
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>