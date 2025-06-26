<template>
    <AdminLayout 
        title="Kullanıcı Düzenle" 
        page-title="Kullanıcı Düzenle"
        :breadcrumbs="[
            { title: 'Kullanıcı Yönetimi', href: route('admin.users.index') },
            { title: 'Kullanıcı Düzenle' }
        ]"
    >
        <PageContainer>
            <FormSection 
                title="Kullanıcı Bilgileri"
                description="Kullanıcının bilgilerini güncelleyin"
                submit-text="Güncelle"
                cancel-text="İptal"
                :is-processing="form.processing"
                :show-success="form.recentlySuccessful"
                @submit="submit"
                @cancel="$inertia.visit(route('admin.users.index'))"
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

                    <div class="col-span-6 sm:col-span-4">
                        <TextInput
                            id="password"
                            v-model="form.password"
                            type="password"
                            label="Şifre (Değiştirmek için doldurun)"
                            :error="form.errors.password"
                            class="mt-1 block w-full"
                        />
                    </div>
                </div>
            </FormSection>
        </PageContainer>
    </AdminLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Global/Layouts/AdminLayout.vue'
import PageContainer from '@/Global/Components/PageContainer.vue'
import FormSection from '@/Global/Components/FormSection.vue'
import TextInput from '@/Global/Forms/TextInput.vue'

const props = defineProps({
    user: Object
})

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: '',
})

const submit = () => {
    form.put(route('admin.users.update', props.user.id))
}
</script> 