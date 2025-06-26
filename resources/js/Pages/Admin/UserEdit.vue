<template>
    <AdminLayout 
        title="Kullanıcı Düzenle" 
        page-title="Kullanıcı Düzenle"
        :breadcrumbs="[
            { title: 'Kullanıcı Yönetimi', url: route('admin.users.index') },
            { title: 'Kullanıcı Düzenle' }
        ]"
    >
        <!-- Page Header -->
        <PageHeader 
            title="Kullanıcı Düzenle"
            :description="`${user.name} kullanıcısının bilgilerini güncelleyin`"
        />

        <!-- User Edit Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Kullanıcı Bilgileri</h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Aşağıdaki formu kullanarak kullanıcı bilgilerini güncelleyebilirsiniz</p>
            </div>
            
            <form @submit.prevent="submit" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

                        <FormGroup 
                            label="Şifre Doğrulama" 
                            :error="form.errors.password_confirmation"
                            help-text="Yukarıdaki şifreyi tekrar girin"
                        >
                            <TextInput
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                type="password"
                                placeholder="Şifre doğrulama"
                            />
                        </FormGroup>
                    </div>

                    <!-- User Info Panel -->
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-3">Kullanıcı Bilgileri</h4>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Durum:</span>
                                <span :class="[
                                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                    user.email_verified_at 
                                        ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' 
                                        : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400'
                                ]">
                                    {{ user.email_verified_at ? 'Aktif' : 'Bekleyen' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Kayıt Tarihi:</span>
                                <span class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ new Date(user.created_at).toLocaleDateString('tr-TR') }}
                                </span>
                            </div>
                            <div v-if="user.email_verified_at" class="flex items-center justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Doğrulama Tarihi:</span>
                                <span class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ new Date(user.email_verified_at).toLocaleDateString('tr-TR') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Success Message -->
                <div v-if="form.recentlySuccessful" class="mt-6 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-sm font-medium text-green-800 dark:text-green-400">Kullanıcı bilgileri başarıyla güncellendi!</span>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 flex items-center justify-end space-x-3">
                    <Button 
                        @click="$inertia.visit(route('admin.users.index'))" 
                        variant="secondary" 
                        size="sm"
                        type="button"
                    >
                        İptal
                    </Button>
                    <Button 
                        type="submit"
                        variant="primary" 
                        size="sm"
                        :disabled="form.processing"
                    >
                        <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <svg v-else class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ form.processing ? 'Kaydediliyor...' : 'Güncelle' }}
                    </Button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Global/Layouts/AdminLayout.vue'
import PageHeader from '@/Global/Components/PageHeader.vue'
import TextInput from '@/Global/Forms/TextInput.vue'
import FormGroup from '@/Global/Forms/FormGroup.vue'
import Button from '@/Global/Components/Button.vue'

const props = defineProps({
    user: Object
})

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: '',
    password_confirmation: '',
})

const submit = () => {
    form.put(route('admin.users.update', props.user.id))
}
</script> 