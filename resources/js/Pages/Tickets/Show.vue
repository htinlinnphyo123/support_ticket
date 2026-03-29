<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { FwbSelect, FwbButton, FwbBadge } from 'flowbite-vue';
import PageTitle from '@/Components/PageTitle.vue';
import CommentBox from '@/Components/CommentBox.vue';
import { onMounted } from 'vue';

const props = defineProps({
    ticket: Object,
    agents: Array,
    statusOptions: Array,
    priorityOptions: Array,
});

// Update Ticket Form (For Agents)
const ticketForm = useForm({
    status: props.ticket.status.value,
    priority: props.ticket.priority.value,
    agent_id: props.ticket.agent_id ? String(props.ticket.agent_id) : '',
});

const updateTicket = () => {
    ticketForm.patch(route('tickets.update', props.ticket.id));
};

const agentOptions = props.agents.length 
    ? [{ value: '', name: 'Unassigned' }, ...props.agents.map(a => ({ value: String(a.id), name: a.name }))] 
    : [];

const commentForm = useForm({
    description: '',
    file: null,
    is_public: true,
});

const submitComment = () => {
    commentForm.post(route('comments.store', props.ticket.id), {
        preserveScroll: true,
        onSuccess: () => {
            commentForm.reset();
            // Best effort clear the file input visually
            const fileInput = document.getElementById('comment-file-input');
            if (fileInput) fileInput.value = '';
        }
    });
};
</script>

<template>
    <Head :title="ticket.title" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <PageTitle>Ticket #{{ ticket.id }}: {{ ticket.title }}</PageTitle>
                <Link :href="route('tickets.index')" class="text-blue-600 hover:underline">
                    &larr; Back to Tickets
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 flex flex-col md:flex-row gap-6">
                
                <!-- Main Ticket & Comments Column -->
                <div class="w-full md:w-2/3 space-y-6">
                    <!-- Original Ticket Body -->
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-bold mb-4">{{ ticket.title }}</h3>
                            <p class="whitespace-pre-wrap text-gray-700">{{ ticket.description }}</p>
                            <div v-if="ticket.link" class="mt-4">
                                <a :href="ticket.link" target="_blank" class="text-blue-600 hover:underline break-all">{{ ticket.link }}</a>
                            </div>
                            <div class="mt-4 text-sm text-gray-500 flex justify-between">
                                <span>Opened by {{ ticket.creator_name }} ({{ ticket.organisation_name }})</span>
                                <span>{{ ticket.created_at }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Comments Timeline -->
                    <div class="mt-8 space-y-6">
                        <h4 class="text-lg font-bold text-gray-800">Comments</h4>
                        
                        <div v-if="!ticket.comments || ticket.comments.length === 0" class="text-gray-500 italic p-4 bg-gray-50 rounded-lg">
                            No comments yet.
                        </div>

                        <div class="flex flex-col bg-gray-50/50 p-4 rounded-xl border border-gray-100">
                            <CommentBox 
                                v-for="comment in ticket.comments.data" 
                                :key="comment.id" 
                                :comment="comment" 
                            />
                        </div>
                    </div>

                    <!-- Add Comment Form -->
                    <div class="mt-8 bg-white p-6 rounded-lg border border-gray-200 shadow-sm">
                        <h4 class="text-md font-bold text-gray-800 mb-4">Add a Reply</h4>
                        <form @submit.prevent="submitComment" class="space-y-4">
                            <div>
                                <textarea v-model="commentForm.description" rows="4" class="block w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 p-3" placeholder="Write your reply here..." required></textarea>
                                <p v-if="commentForm.errors.description" class="mt-1 text-sm text-red-600">{{ commentForm.errors.description }}</p>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between bg-gray-50 p-3 rounded-lg border border-gray-100">
                                <div class="flex items-center gap-6">
                                    <input type="file" id="comment-file-input" @input="commentForm.file = $event.target.files[0]" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 cursor-pointer transition" />
                                    
                                    <!-- Only Agents can set is_public manually -->
                                    <label v-if="$page.props.auth.user.type === 'agent'" class="flex items-center gap-2 cursor-pointer bg-white px-3 py-1.5 rounded border border-gray-200 shadow-sm hover:bg-gray-50">
                                        <input type="checkbox" v-model="commentForm.is_public" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="text-sm font-medium text-gray-700 select-none">Public Reply</span>
                                    </label>
                                </div>

                                <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-6 py-2.5 outline-none transition" :disabled="commentForm.processing">
                                    Post Reply
                                </button>
                            </div>
                            <p v-if="commentForm.errors.file" class="text-sm text-red-600">{{ commentForm.errors.file }}</p>
                        </form>
                    </div>

                </div>

                <!-- Sidebar -> Ticket Meta info -->
                <div class="w-full md:w-1/3">
                    <div class="bg-white p-6 shadow-sm sm:rounded-lg sticky top-6">
                        <h4 class="font-semibold text-lg text-gray-800 mb-4">Ticket Details</h4>
                        
                        <!-- Client View (Read-only status) -->
                        <div v-if="$page.props.auth.user.type === 'employee'" class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Status</p>
                                <fwb-badge v-if="ticket.status" :type="ticket.status.color">{{ ticket.status.label }}</fwb-badge>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Priority</p>
                                <fwb-badge v-if="ticket.priority" :type="ticket.priority.color">{{ ticket.priority.label }}</fwb-badge>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">SLA Tracker</p>
                                <fwb-badge v-if="ticket.sla_status" :type="ticket.sla_status.color">{{ ticket.sla_status.label }}</fwb-badge>
                                <p v-if="ticket.due_date && ticket.sla_status.value !== 'completed'" class="text-xs text-gray-500 mt-1">Due: {{ ticket.due_date }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Assigned Agent</p>
                                <span class="font-medium text-gray-900">{{ ticket.agent ? ticket.agent.name : 'Unassigned' }}</span>
                            </div>
                        </div>

                        <!-- Agent View (Editable forms) -->
                        <form v-if="$page.props.auth.user.type === 'agent'" @submit.prevent="updateTicket" class="space-y-5">
                            <div>
                                <FwbSelect label="Status" v-model="ticketForm.status" :options="statusOptions" />
                            </div>
                            <div>
                                <FwbSelect label="Priority" v-model="ticketForm.priority" :options="priorityOptions" />
                            </div>
                            <div>
                                <FwbSelect label="Assign To" v-model="ticketForm.agent_id" :options="agentOptions" />
                            </div>
                            
                            <FwbButton type="submit" color="dark" class="w-full justify-center" :disabled="ticketForm.processing">
                                Update Ticket
                            </FwbButton>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
