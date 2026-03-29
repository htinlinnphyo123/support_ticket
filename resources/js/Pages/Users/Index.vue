<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageTitle from '@/Components/PageTitle.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Table from '@/Components/Table.vue';
import Pagination from '@/Components/Pagination.vue';

defineProps({
    users: {
        type: Object,
        required: true,
    },
});

const columns = [
    { key: 'name', label: 'Name' },
    { key: 'email', label: 'Email' },
    { key: 'phone', label: 'Phone' },
    { key: 'type', label: 'Role' },
    { key: 'status', label: 'Status' },
];

const deleteUser = (id) => {
    if (confirm('Are you sure you want to delete this user?')) {
        router.delete(route('users.destroy', id), { preserveScroll: true });
    }
};
</script>

<template>
    <Head title="Users" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <PageTitle>Users</PageTitle>
                <Link :href="route('users.create')" class="bg-blue-600 text-white px-4 py-2 rounded font-medium hover:bg-blue-700">
                    Create User
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                        <Table :columns="columns" :rows="users.data">
                            <!-- Custom rendering for type cell -->
                            <template #cell-type="{ value }">
                                <div v-if="value" class="px-2 py-1 text-xs rounded font-medium inline-block" :class="value.color">
                                    {{ value.label }}
                                </div>
                            </template>

                            <!-- Custom rendering for status cell -->
                            <template #cell-status="{ value }">
                                <div v-if="value" class="font-medium" :class="value.color">
                                    {{ value.label }}
                                </div>
                            </template>

                            <!-- Actions column -->
                            <template #actions="{ row }">
                                <div class="flex space-x-3">
                                    <Link :href="route('users.show', row.id)" class="text-gray-500 hover:underline font-medium">
                                        View
                                    </Link>
                                    <Link :href="route('users.edit', row.id)" class="text-blue-600 hover:underline font-medium">
                                        Edit
                                    </Link>
                                    <button 
                                        v-if="row.id !== $page.props.auth.user.id" 
                                        @click="deleteUser(row.id)" 
                                        class="text-red-600 hover:underline font-medium"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </template>
                        </Table>

                        <!-- Pagination Links -->
                        <Pagination :meta="users.meta" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
