<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import TextInput from '@/Global/Forms/TextInput.vue';
import FormGroup from '@/Global/Forms/FormGroup.vue';
import Button from '@/Global/Components/Button.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit" class="space-y-6">
            <FormGroup label="Name" :required="true" :error="form.errors.name">
                <TextInput
                    id="name"
                    type="text"
                    v-model="form.name"
                    placeholder="Enter your full name"
                    clearable
                    autofocus
                    autocomplete="name"
                />
            </FormGroup>

            <FormGroup label="Email" :required="true" :error="form.errors.email">
                <TextInput
                    id="email"
                    type="email"
                    v-model="form.email"
                    placeholder="your@email.com"
                    clearable
                    autocomplete="username"
                />
            </FormGroup>

            <FormGroup label="Password" :required="true" :error="form.errors.password">
                <TextInput
                    id="password"
                    type="password"
                    v-model="form.password"
                    placeholder="Enter a strong password"
                    autocomplete="new-password"
                />
            </FormGroup>

            <FormGroup label="Confirm Password" :required="true" :error="form.errors.password_confirmation">
                <TextInput
                    id="password_confirmation"
                    type="password"
                    v-model="form.password_confirmation"
                    placeholder="Confirm your password"
                    autocomplete="new-password"
                />
            </FormGroup>

            <div class="mt-4 flex items-center justify-end">
                <Link
                    :href="route('login')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Already registered?
                </Link>

                <Button
                    type="submit"
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Register
                </Button>
            </div>
        </form>
    </GuestLayout>
</template>
