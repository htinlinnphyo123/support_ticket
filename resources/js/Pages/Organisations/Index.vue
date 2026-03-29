<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageTitle from '@/Components/PageTitle.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Table from '@/Components/Table.vue';
import Pagination from '@/Components/Pagination.vue';

defineProps({
    organisations: {
        type: Object,
        required: true,
    },
});

const columns = [
    { key: 'name', label: 'Name' },
    { key: 'description', label: 'Description' },
    { key: 'status', label: 'Status' },
];

const deleteOrganisation = (id) => {
    if (confirm('Are you sure you want to delete this organisation?')) {
        router.delete(route('organisations.destroy', id), { preserveScroll: true });
    }
};
</script>

<template>
    <Head title="Organisations" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <PageTitle>Organisations</PageTitle>
                <Link :href="route('organisations.create')" class="bg-blue-600 outline-none text-white px-4 py-2 rounded font-medium hover:bg-blue-700">
                    Create Organisation
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <Table :columns="columns" :rows="organisations.data">
                            <!-- Custom rendering for status cell -->
                            <template #cell-status="{ value }">
                                <div v-if="value" class="font-medium" :class="value.color">
                                    {{ value.label }}
                                </div>
                            </template>
                            
                            <!-- Custom rendering for description cell (truncate) -->
                            <template #cell-description="{ value }">
                                <div class="truncate max-w-xs" :title="value">
                                    {{ value || 'N/A' }}
                                </div>
                            </template>

                            <!-- Actions column -->
                            <template #actions="{ row }">
                                <div class="flex space-x-3">
                                    <Link :href="route('organisations.show', row.id)" class="text-gray-500 hover:underline font-medium">
                                        View
                                    </Link>
                                    <Link :href="route('organisations.edit', row.id)" class="text-blue-600 hover:underline font-medium">
                                        Edit
                                    </Link>
                                    <button @click="deleteOrganisation(row.id)" class="text-red-600 hover:underline font-medium">
                                        Delete
                                    </button>
                                </div>
                            </template>
                        </Table>

                        <!-- Pagination Links -->
                        <Pagination :meta="organisations.meta" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
