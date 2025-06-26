<template>
    <form @submit.prevent="submit">
        <div class="space-y-6">
            <FormGroup label="İsim" :required="true" :error="form.errors.name">
                <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    placeholder="Kullanıcının tam adını girin"
                    clearable
                />
            </FormGroup>

            <FormGroup label="E-posta" :required="true" :error="form.errors.email">
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    placeholder="ornek@email.com"
                    clearable
                />
            </FormGroup>

            <FormGroup label="Şifre" :required="true" :error="form.errors.password">
                <TextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    placeholder="En az 8 karakter"
                />
            </FormGroup>

            <FormGroup label="Şifre Tekrar" :required="true" :error="form.errors.password_confirmation">
                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    placeholder="Şifreyi tekrar girin"
                />
            </FormGroup>
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
import FormGroup from '@/Global/Forms/FormGroup.vue'
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