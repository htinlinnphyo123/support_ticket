<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Table from '@/Components/Table.vue';

defineProps({
    organisation: {
        type: Object,
        required: true,
    },
});

const userColumns = [
    { key: 'name', label: 'Name' },
    { key: 'email', label: 'Email' },
    { key: 'phone', label: 'Phone' },
    { key: 'type', label: 'Role' },
    { key: 'status', label: 'Status' },
];

const deleteOrganisation = (id) => {
    if (confirm('Are you sure you want to delete this organisation?')) {
        router.delete(route('organisations.destroy', id));
    }
};
</script>

<template>
    <Head title="Organisation Details" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Organisation Details: {{ organisation.name }}
                </h2>
                <Link :href="route('organisations.index')" class="text-blue-600 hover:underline text-sm font-medium">
                    Back to Organisations
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
                <!-- Org Details Card -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <dl class="divide-y divide-gray-100">
                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm/6 font-medium text-gray-900">Name</dt>
                                <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">{{ organisation.name }}</dd>
                            </div>
                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm/6 font-medium text-gray-900">Description</dt>
                                <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">{{ organisation.description || 'No description provided.' }}</dd>
                            </div>
                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm/6 font-medium text-gray-900">Status</dt>
                                <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">
                                    <div v-if="organisation.status" class="font-medium" :class="organisation.status.color">
                                        {{ organisation.status.label }}
                                    </div>
                                </dd>
                            </div>
                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm/6 font-medium text-gray-900">Created By</dt>
                                <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">
                                    {{ organisation.creator ? organisation.creator.name : 'Unknown' }}
                                </dd>
                            </div>
                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm/6 font-medium text-gray-900">Created At</dt>
                                <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">
                                    {{ new Date(organisation.created_at).toLocaleString() }}
                                </dd>
                            </div>
                        </dl>
                        <div class="mt-6 flex justify-end space-x-3">
                            <Link :href="route('organisations.edit', organisation.id)" class="text-blue-600 hover:underline">Edit Organisation</Link>
                            <button @click="deleteOrganisation(organisation.id)" class="text-red-600 hover:underline">Delete Organisation</button>
                        </div>
                    </div>
                </div>

                <!-- Related Users -->
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Associated Users</h3>
                        <div v-if="organisation.users.data && organisation.users.data.length > 0">
                            <Table :columns="userColumns" :rows="organisation.users.data">
                                <template #cell-type="{ value }">
                                    <div v-if="value" class="px-2 py-1 text-xs rounded font-medium inline-block text-white" :class="value.color">{{ value.label }}</div>
                                </template>
                                <template #cell-status="{ value }">
                                    <div v-if="value" class="font-medium" :class="value.color">{{ value.label }}</div>
                                </template>
                            </Table>
                        </div>
                        <div v-else class="text-sm text-gray-500">
                            No users are associated with this organisation yet.
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
