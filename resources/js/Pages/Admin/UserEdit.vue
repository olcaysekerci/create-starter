<template>
    <AdminLayout 
        title="Kullanıcı Düzenle" 
        page-title="Kullanıcı Düzenle"
        :breadcrumbs="[
            { title: 'Dashboard', url: '/admin' },
            { title: 'Kullanıcı Yönetimi', url: route('admin.users.index') },
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
                        <FormGroup label="İsim" :required="true" :error="form.errors.name">
                            <TextInput
                                id="name"
                                v-model="form.name"
                                type="text"
                                placeholder="Kullanıcının tam adını girin"
                                clearable
                            />
                        </FormGroup>
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <FormGroup label="E-posta" :required="true" :error="form.errors.email">
                            <TextInput
                                id="email"
                                v-model="form.email"
                                type="email"
                                placeholder="ornek@email.com"
                                clearable
                            />
                        </FormGroup>
                    </div>

                    <div class="col-span-6 sm:col-span-4">
                        <FormGroup 
                            label="Şifre" 
                            :error="form.errors.password"
                            help-text="Değiştirmek istemiyorsanız boş bırakın"
                        >
                            <TextInput
                                id="password"
                                v-model="form.password"
                                type="password"
                                placeholder="Yeni şifre (opsiyonel)"
                            />
                        </FormGroup>
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
import FormGroup from '@/Global/Forms/FormGroup.vue'

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