<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { FwbInput, FwbSelect, FwbTextarea, FwbButton } from 'flowbite-vue';

const form = useForm({
    name: '',
    description: '',
    status: 'active',
});

const statusOptions = [
    { value: 'active', name: 'Active' },
    { value: 'inactive', name: 'Inactive' },
];

const submit = () => {
    form.post(route('organisations.store'));
};
</script>

<template>
    <Head title="Create Organisation" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <PageTitle>Create Organisation</PageTitle>
                <Link :href="route('organisations.index')" class="text-blue-600 hover:underline text-sm font-medium">
                    Back to Organisations
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit" class="space-y-6">
                            
                            <!-- Name -->
                            <div>
                                <FwbInput v-model="form.name" label="Name" placeholder="Acme Corp" :validation-status="form.errors.name ? 'error' : ''" />
                                <p v-if="form.errors.name" class="mt-2 text-sm text-red-600 font-medium">{{ form.errors.name }}</p>
                            </div>
                            
                            <!-- Description -->
                            <div>
                                <label class="mb-2 block text-sm font-bold text-gray-900 dark:text-white">Description (Optional)</label>
                                <FwbTextarea v-model="form.description" rows="4" placeholder="Description of the organisation..." />
                                <p v-if="form.errors.description" class="mt-2 text-sm text-red-600 font-medium">{{ form.errors.description }}</p>
                            </div>

                            <!-- Status -->
                            <div>
                                <FwbSelect v-model="form.status" :options="statusOptions" label="Status" />
                                <p v-if="form.errors.status" class="mt-2 text-sm text-red-600 font-medium">{{ form.errors.status }}</p>
                            </div>

                            <div class="flex items-center gap-4">
                                <FwbButton type="submit" :disabled="form.processing">Create Organisation</FwbButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
