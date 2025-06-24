<template>
    <form @submit.prevent="submit">
        <div class="space-y-4">
            <TextInput
                id="name"
                v-model="form.name"
                type="text"
                label="İsim"
                :error="form.errors.name"
            />

            <TextInput
                id="email"
                v-model="form.email"
                type="email"
                label="E-posta"
                :error="form.errors.email"
            />

            <TextInput
                id="password"
                v-model="form.password"
                type="password"
                label="Şifre"
                :error="form.errors.password"
            />

            <TextInput
                id="password_confirmation"
                v-model="form.password_confirmation"
                type="password"
                label="Şifre Tekrar"
                :error="form.errors.password_confirmation"
            />
        </div>

        <div class="flex items-center justify-end mt-6 space-x-3">
            <Button 
                type="button" 
                variant="secondary"
                @click="$emit('cancel')"
            >
                İptal
            </Button>
            <Button 
                type="submit"
                :disabled="form.processing"
                :class="{ 'opacity-25': form.processing }"
            >
                Oluştur
            </Button>
        </div>
    </form>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import TextInput from '@/Global/Forms/TextInput.vue'
import Button from '@/Global/Components/Button.vue'

const emit = defineEmits(['created', 'cancel'])

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
})

const submit = () => {
    form.post(route('admin.users.store'), {
        onSuccess: () => {
            form.reset()
            emit('created')
        }
    })
}
</script> 