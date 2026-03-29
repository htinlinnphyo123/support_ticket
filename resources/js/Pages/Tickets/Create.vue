<script setup>
import PageTitle from '@/Components/PageTitle.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { FwbInput, FwbButton, FwbTextarea } from 'flowbite-vue';

const form = useForm({
    title: '',
    description: '',
    file: null,
});

const submit = () => {
    form.post(route('tickets.store'));
};
</script>

<template>
    <Head title="Create Ticket" />

    <AuthenticatedLayout>
        <template #header>
            <PageTitle>Create Support Ticket</PageTitle>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit" class="space-y-6">
                            
                            <!-- Title -->
                            <div>
                                <FwbInput v-model="form.title" label="Title" placeholder="Brief summary of the issue" required />
                                <p v-if="form.errors.title" class="mt-2 text-sm text-red-600 font-medium">{{ form.errors.title }}</p>
                            </div>

                            <!-- Description -->
                            <div>
                                <FwbTextarea rows="5" v-model="form.description" label="Description" placeholder="Provide detailed information..." required />
                                <p v-if="form.errors.description" class="mt-2 text-sm text-red-600 font-medium">{{ form.errors.description }}</p>
                            </div>

                            <!-- File Upload -->
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900">Attachment (Optional)</label>
                                <input 
                                    type="file" 
                                    @input="form.file = $event.target.files[0]" 
                                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                                />
                                <p class="mt-1 text-xs text-gray-500">Supported: JPG, PNG, PDF, DOC, ZIP (Max 10MB)</p>
                                <p v-if="form.errors.file" class="mt-2 text-sm text-red-600 font-medium">{{ form.errors.file }}</p>
                            </div>

                            <div class="flex justify-end gap-3 mt-8">
                                <Link :href="route('tickets.index')" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                                    Cancel
                                </Link>
                                <FwbButton type="submit" color="blue" :disabled="form.processing">
                                    Create Ticket
                                </FwbButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
