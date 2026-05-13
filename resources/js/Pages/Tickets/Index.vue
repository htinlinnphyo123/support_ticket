<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageTitle from '@/Components/PageTitle.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { FwbSelect, FwbInput, FwbBadge } from 'flowbite-vue';
import { onMounted, ref, watch, computed } from 'vue';
import Table from '@/Components/Table.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    tickets: {
        type: Object,
        required: true,
    },
    filters: Object,
    organisations: Array,
    agents: Array,
    statusOptions: Array,
    priorityOptions: Array,
});

const filterForm = ref({
    search: props.filters.search || '',
    status: props.filters.status || '',
    priority: props.filters.priority || '',
    organisation_id: props.filters.organisation_id || '',
    agent_id: props.filters.agent_id || '',
});

// Watch for filter changes and debounce
let timeout = null;
watch(filterForm, (newVal) => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        router.get(route('tickets.index'), newVal, { preserveState: true, preserveScroll: true, replace: true });
    }, 300);
}, { deep: true });

const page = usePage();
const userType = computed(() => page.props.auth.user.type);

const columns = computed(() => {
    return userType.value === 'agent' ? [
        { key: 'title', label: 'Title' },
        { key: 'creator_name', label: 'Created By' },
        { key: 'agent_name', label: 'Agent' },
        { key: 'organisation_name', label: 'Organisation' },
        { key: 'status', label: 'Status' },
        { key: 'due_date', label: 'Due Date' },
        { key: 'priority', label: 'Priority' },
        { key: 'sla_status', label: 'SLA' },
    ] : [
        { key: 'title', label: 'Title' },
        { key: 'creator_name', label: 'Created By' },
        { key: 'status', label: 'Status' },
        { key: 'priority', label: 'Priority' },
        { key: 'sla_status', label: 'SLA' },
    ];
});

const statusOptions = [{ value: '', name: 'All Statuses' }, ...props.statusOptions];
const priorityOptions = [{ value: '', name: 'All Priorities' }, ...props.priorityOptions];

const orgOptions = props.organisations?.length 
    ? [{ value: '', name: 'All Organisations' }, ...props.organisations.map(o => ({ value: String(o.id), name: o.name }))]
    : [];

const agentOptions = props.agents?.length
    ? [
        { value: '', name: 'All Agents' },
        { value: 'unassigned', name: 'Unassigned Tickets' },
        ...props.agents.map(a => ({ value: String(a.id), name: a.name }))
      ]
    : [];
  onMounted(()=>{
    console.log(props.tickets.data)
  })
</script>

<template>
    <Head title="Tickets" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <PageTitle>Support Tickets</PageTitle>
                <Link
                    v-if="$page.props.auth.user.type=='employee'" 
                    :href="route('tickets.create')" class="bg-blue-600 outline-none text-white px-4 py-2 rounded font-medium hover:bg-blue-700">
                    Create Ticket
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

                <!-- Filters for Agents -->
                <div class="mb-6 grid grid-cols-1 gap-4 p-4 bg-white rounded-lg shadow-sm"
                  :class="$page.props.auth.user.type === 'agent' ? 'md:grid-cols-5' : 'md:grid-cols-3'">
                    <FwbInput v-model="filterForm.search" placeholder="Search title..." label="Search" />
                    <FwbSelect v-model="filterForm.status" :options="statusOptions" label="Status" />
                    <FwbSelect v-model="filterForm.priority" :options="priorityOptions" label="Priority" />
                    <template v-if="$page.props.auth.user.type === 'agent'">
                        <FwbSelect v-model="filterForm.organisation_id" :options="orgOptions" label="Organisation" />
                        <FwbSelect v-model="filterForm.agent_id" :options="agentOptions" label="Agent" />
                    </template>
                </div>

                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <Table :columns="columns" :rows="tickets.data">
                            <!-- Status styling -->
                            <template #cell-status="{ value }">
                                <fwb-badge v-if="value" :type="value.color">
                                    {{ value.label }}
                                </fwb-badge>
                            </template>
                            
                            <!-- SLA styling -->
                            <template #cell-sla_status="{ value }">
                                <fwb-badge v-if="value" :type="value.color">
                                    {{ value.label }}
                                </fwb-badge>
                            </template>
                            
                            <!-- Priority styling -->
                            <template #cell-priority="{ value }">
                                <fwb-badge v-if="value" :type="value.color">
                                    {{ value.label }}
                                </fwb-badge>
                            </template>

                            <template #actions="{ row }">
                                <div class="flex space-x-3">
                                    <Link :href="route('tickets.show', row.id)" class="text-blue-600 hover:underline font-medium">
                                        View
                                    </Link>
                                </div>
                            </template>
                        </Table>

                        <!-- Pagination Links -->
                        <Pagination :meta="tickets.meta" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
