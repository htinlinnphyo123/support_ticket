<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageTitle from '@/Components/PageTitle.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import Table from '@/Components/Table.vue';
import { FwbBadge } from 'flowbite-vue';

const props = defineProps({
    stats: Object,
    recent_tickets: Object,
});

const page = usePage();
const userType = computed(() => page.props.auth.user.type);
const isAgent = computed(() => userType.value === 'agent');

const columns = computed(() => {
    return isAgent.value ? [
        { key: 'title', label: 'Title' },
        { key: 'creator_name', label: 'Created By' },
        { key: 'agent_name', label: 'Agent' },
        { key: 'organisation_name', label: 'Organisation' },
        { key: 'status', label: 'Status' },
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
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <PageTitle>Dashboard</PageTitle>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
                <!-- KPI Cards Row -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Global Active Stat (Both) -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-500">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">
                                {{ isAgent ? 'Total Active Tickets' : 'Active Tickets (Your Org)' }}
                            </div>
                            <div class="text-3xl font-bold text-gray-900">{{ stats.total_active || 0 }}</div>
                        </div>
                    </div>

                    <!-- Agent Only Stats -->
                    <template v-if="isAgent">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-yellow-500">
                            <div class="p-6">
                                <div class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">
                                    Unassigned Tickets
                                </div>
                                <div class="text-3xl font-bold text-gray-900">{{ stats.unassigned || 0 }}</div>
                            </div>
                        </div>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-red-500">
                            <div class="p-6">
                                <div class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">
                                    Overdue Tickets
                                </div>
                                <div class="text-3xl font-bold text-red-600">{{ stats.overdue || 0 }}</div>
                            </div>
                        </div>
                    </template>

                    <!-- Employee Only Stats -->
                    <template v-else>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500">
                            <div class="p-6">
                                <div class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-1">
                                    Recently Resolved (30 Days)
                                </div>
                                <div class="text-3xl font-bold text-gray-900">{{ stats.recently_resolved || 0 }}</div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Recent Tickets Table -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900">Recent Tickets</h3>
                        <Link :href="route('tickets.index')" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                            View All &rarr;
                        </Link>
                    </div>
                    <div class="p-4">
                        <Table v-if="recent_tickets.data.length" :columns="columns" :rows="recent_tickets.data">
                            <!-- Status styling -->
                            <template #cell-status="{ value }">
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
                            
                            <!-- SLA styling -->
                            <template #cell-sla_status="{ value }">
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
                        <div v-else class="text-center py-6 text-gray-500 italic">
                            No recent tickets found.
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
