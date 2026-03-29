<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { FwbInput, FwbSelect, FwbButton } from 'flowbite-vue';

const props = defineProps({
    organisations: {
        type: Array,
        default: () => [],
    }
});

const form = useForm({
    name: '',
    email: '',
    password: '',
    phone: '',
    type: 'employee',
    status: 'active',
    organisation_id: '',
});

const typeOptions = [
    { value: 'employee', name: 'Employee' },
    { value: 'agent', name: 'Agent' },
];

const statusOptions = [
    { value: 'active', name: 'Active' },
    { value: 'inactive', name: 'Inactive' },
];

const orgOptions = props.organisations.map(org => ({ value: String(org.id), name: org.name }));
orgOptions.unshift({ value: '', name: 'Select Organisation' });

const submit = () => {
    form.post(route('users.store'));
};
</script>

<template>
    <Head title="Create User" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <PageTitle>Create User</PageTitle>
                <Link :href="route('users.index')" class="text-blue-600 hover:underline text-sm font-medium">
                    Back to Users
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
                                <FwbInput v-model="form.name" label="Name" placeholder="John Doe" :validation-status="form.errors.name ? 'error' : ''" />
                                <p v-if="form.errors.name" class="mt-2 text-sm text-red-600 font-medium">{{ form.errors.name }}</p>
                            </div>
                            
                            <!-- Email -->
                            <div>
                                <FwbInput v-model="form.email" type="email" label="Email" placeholder="john@example.com" :validation-status="form.errors.email ? 'error' : ''" />
                                <p v-if="form.errors.email" class="mt-2 text-sm text-red-600 font-medium">{{ form.errors.email }}</p>
                            </div>
                            
                            <!-- Password -->
                            <div>
                                <FwbInput v-model="form.password" type="password" label="Password" :validation-status="form.errors.password ? 'error' : ''" />
                                <p v-if="form.errors.password" class="mt-2 text-sm text-red-600 font-medium">{{ form.errors.password }}</p>
                            </div>

                            <!-- Phone -->
                            <div>
                                <FwbInput v-model="form.phone" type="text" label="Phone" placeholder="1234567890" :validation-status="form.errors.phone ? 'error' : ''" />
                                <p v-if="form.errors.phone" class="mt-2 text-sm text-red-600 font-medium">{{ form.errors.phone }}</p>
                            </div>

                            <!-- Role -->
                            <div v-if="$page.props.auth.user.type === 'agent'">
                                <FwbSelect v-model="form.type" :options="typeOptions" label="Role" />
                                <p v-if="form.errors.type" class="mt-2 text-sm text-red-600 font-medium">{{ form.errors.type }}</p>
                            </div>

                            <!-- Organisation -->
                            <div v-if="$page.props.auth.user.type === 'agent' && form.type === 'employee'">
                                <FwbSelect v-model="form.organisation_id" :options="orgOptions" label="Organisation" />
                                <p v-if="form.errors.organisation_id" class="mt-2 text-sm text-red-600 font-medium">{{ form.errors.organisation_id }}</p>
                            </div>

                            <!-- Status -->
                            <div>
                                <FwbSelect v-model="form.status" :options="statusOptions" label="Status" />
                                <p v-if="form.errors.status" class="mt-2 text-sm text-red-600 font-medium">{{ form.errors.status }}</p>
                            </div>

                            <div class="flex items-center gap-4">
                                <FwbButton type="submit" :disabled="form.processing">Create User</FwbButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
