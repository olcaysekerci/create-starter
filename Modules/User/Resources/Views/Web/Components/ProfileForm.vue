<template>
    <FormSection 
        title="Profil Bilgileri"
        description="Hesap bilgilerinizi güncelleyin"
        submit-text="Güncelle"
        :is-processing="form.processing"
        :show-success="form.recentlySuccessful"
        @submit="submit"
    >
        <div class="grid grid-cols-6 gap-6">
            <div class="col-span-6 sm:col-span-4">
                <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    label="İsim"
                    :error="form.errors.name"
                    class="mt-1 block w-full"
                />
            </div>

            <div class="col-span-6 sm:col-span-4">
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    label="E-posta"
                    :error="form.errors.email"
                    class="mt-1 block w-full"
                />
            </div>
        </div>
    </FormSection>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import TextInput from '@/Global/Forms/TextInput.vue'
import Button from '@/Global/Components/Button.vue'
import FormSection from '@/Global/Components/FormSection.vue'

const props = defineProps({
    user: Object
})

const form = useForm({
    name: props.user.name,
    email: props.user.email,
})

const submit = () => {
    form.put(route('user.profile.update'))
}
</script> 