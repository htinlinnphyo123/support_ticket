<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { FwbBadge } from 'flowbite-vue';

defineProps({
    user: {
        type: Object,
        required: true,
    },
});

const deleteUser = (id) => {
    if (confirm('Are you sure you want to delete this user?')) {
        router.delete(route('users.destroy', id));
    }
};
</script>

<template>
    <Head title="User Details" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    User Details: {{ user.name }}
                </h2>
                <Link :href="route('users.index')" class="text-blue-600 hover:underline text-sm font-medium">
                    Back to Users
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <dl class="divide-y divide-gray-100">
                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm/6 font-medium text-gray-900">Full name</dt>
                                <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">{{ user.name }}</dd>
                            </div>
                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm/6 font-medium text-gray-900">Email address</dt>
                                <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">{{ user.email }}</dd>
                            </div>
                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm/6 font-medium text-gray-900">Phone</dt>
                                <dd class="mt-1 text-sm/6 text-gray-700 sm:col-span-2 sm:mt-0">{{ user.phone || 'N/A' }}</dd>
                            </div>
                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm/6 font-medium text-gray-900">Role</dt>
                                <dd class="mt-1 text-sm/6 text-gray-700">
                                    <FwbBadge>
                                        {{ user.type.value }}
                                    </FwbBadge>
                                </dd>
                            </div>
                            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                                <dt class="text-sm/6 font-medium text-gray-900">Status</dt>
                                <dd class="mt-1 text-sm/6 text-gray-700">
                                    <FwbBadge>
                                        {{ user.status.value }}
                                    </FwbBadge>
                                </dd>
                            </div>
                        </dl>
                        <div class="mt-6 flex justify-end space-x-3">
                            <Link :href="route('users.edit', user.id)" class="text-blue-600 hover:underline">Edit User</Link>
                            <button @click="deleteUser(user.id)" class="text-red-600 hover:underline">Delete User</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
