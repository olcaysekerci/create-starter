<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import TextInput from '@/Global/Forms/TextInput.vue';
import FormGroup from '@/Global/Forms/FormGroup.vue';
import Button from '@/Global/Components/Button.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <FormGroup label="Email" :required="true" :error="form.errors.email">
                <TextInput
                    id="email"
                    type="email"
                    v-model="form.email"
                    placeholder="your@email.com"
                    clearable
                    autofocus
                    autocomplete="username"
                />
            </FormGroup>

            <FormGroup label="Password" :required="true" :error="form.errors.password">
                <TextInput
                    id="password"
                    type="password"
                    v-model="form.password"
                    placeholder="Enter your password"
                    autocomplete="current-password"
                />
            </FormGroup>

            <div class="mt-4 block">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600"
                        >Remember me</span
                    >
                </label>
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Forgot your password?
                </Link>

                <Button
                    type="submit"
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Log in
                </Button>
            </div>
        </form>
    </GuestLayout>
</template>
