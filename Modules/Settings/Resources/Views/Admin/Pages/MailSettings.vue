<template>
    <AdminLayout>
        <template #header>
            <PageHeader title="Mail Ayarları" description="E-posta gönderim ayarlarını yapılandırın">
                <template #actions>
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                        <Button variant="secondary" @click="testMailModal = true" class="w-full sm:w-auto">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span class="hidden sm:inline">Test Mail</span>
                            <span class="sm:hidden">Test</span>
                        </Button>
                        <Button variant="primary" @click="saveSettings" :loading="saving" class="w-full sm:w-auto">
                            <CheckIcon class="w-4 h-4 mr-2" />
                            Kaydet
                        </Button>
                    </div>
                </template>
            </PageHeader>
        </template>

        <div class="space-y-4 sm:space-y-6">
            <!-- Mail Ayarları Formu -->
            <FormSection title="SMTP Ayarları" description="Mail sunucu konfigürasyonu">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <FormGroup label="Mail Driver" required>
                        <Select v-model="form.driver" :options="driverOptions" />
                    </FormGroup>
                    
                    <FormGroup label="Host" required>
                        <TextInput v-model="form.host" placeholder="smtp.gmail.com" />
                    </FormGroup>
                    
                    <FormGroup label="Port" required>
                        <TextInput v-model="form.port" type="number" placeholder="587" />
                    </FormGroup>
                    
                    <FormGroup label="Encryption">
                        <Select v-model="form.encryption" :options="encryptionOptions" />
                    </FormGroup>
                    
                    <FormGroup label="Kullanıcı Adı">
                        <TextInput v-model="form.username" placeholder="user@gmail.com" />
                    </FormGroup>
                    
                    <FormGroup label="Şifre">
                        <TextInput v-model="form.password" type="password" placeholder="••••••••" />
                    </FormGroup>
                </div>
            </FormSection>

            <!-- Gönderici Bilgileri -->
            <FormSection title="Gönderici Bilgileri" description="Mail gönderici adres ve isim bilgileri">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <FormGroup label="Gönderici E-posta" required>
                        <TextInput v-model="form.from_address" type="email" placeholder="noreply@example.com" />
                    </FormGroup>
                    
                    <FormGroup label="Gönderici Adı" required>
                        <TextInput v-model="form.from_name" placeholder="Uygulama Adı" />
                    </FormGroup>
                </div>
            </FormSection>

            <!-- Mevcut Ayarlar -->
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    Mevcut Ayarlar
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 text-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center">
                        <span class="font-medium text-gray-700 dark:text-gray-300 mb-1 sm:mb-0 sm:mr-2">Host:</span>
                        <span class="text-gray-600 dark:text-gray-400 truncate">{{ mailSettings.host }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center">
                        <span class="font-medium text-gray-700 dark:text-gray-300 mb-1 sm:mb-0 sm:mr-2">Port:</span>
                        <span class="text-gray-600 dark:text-gray-400">{{ mailSettings.port }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center">
                        <span class="font-medium text-gray-700 dark:text-gray-300 mb-1 sm:mb-0 sm:mr-2">Driver:</span>
                        <span class="text-gray-600 dark:text-gray-400">{{ mailSettings.driver }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center">
                        <span class="font-medium text-gray-700 dark:text-gray-300 mb-1 sm:mb-0 sm:mr-2">Encryption:</span>
                        <span class="text-gray-600 dark:text-gray-400">{{ mailSettings.encryption || 'Yok' }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center sm:col-span-2">
                        <span class="font-medium text-gray-700 dark:text-gray-300 mb-1 sm:mb-0 sm:mr-2">Gönderici:</span>
                        <span class="text-gray-600 dark:text-gray-400 truncate">{{ mailSettings.from_address }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center sm:col-span-2">
                        <span class="font-medium text-gray-700 dark:text-gray-300 mb-1 sm:mb-0 sm:mr-2">Gönderici Adı:</span>
                        <span class="text-gray-600 dark:text-gray-400 truncate">{{ mailSettings.from_name }}</span>
                    </div>
                </div>
            </div>

            <!-- Bilgi Kartı -->
            <Alert type="info" title="Mail Ayarları Hakkında">
                <div class="space-y-2 text-sm">
                    <p>• SMTP ayarlarını değiştirdikten sonra test mail göndererek kontrol edin.</p>
                    <p>• Gmail kullanıyorsanız "App Password" oluşturmanız gerekebilir.</p>
                    <p>• Ayarlar kaydedildikten sonra config cache otomatik olarak temizlenir.</p>
                </div>
            </Alert>
        </div>

        <!-- Test Mail Modal -->
        <Modal :show="testMailModal" @close="testMailModal = false">
            <div class="p-4 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                    Test Mail Gönder
                </h3>
                
                <FormGroup label="Test E-posta Adresi" required>
                    <TextInput v-model="testEmail" type="email" placeholder="test@example.com" />
                </FormGroup>
                
                <div class="mt-6 flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3">
                    <Button variant="secondary" @click="testMailModal = false" class="w-full sm:w-auto">
                        İptal
                    </Button>
                    <Button variant="primary" @click="sendTestMail" :loading="sendingTest" class="w-full sm:w-auto">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Gönder
                    </Button>
                </div>
            </div>
        </Modal>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Global/Layouts/AdminLayout.vue'
import PageHeader from '@/Global/Components/PageHeader.vue'
import FormSection from '@/Global/Components/FormSection.vue'
import FormGroup from '@/Global/Forms/FormGroup.vue'
import TextInput from '@/Global/Forms/TextInput.vue'
import Select from '@/Global/Forms/Select.vue'
import Button from '@/Global/Components/Button.vue'
import Alert from '@/Global/Components/Alert.vue'
import Modal from '@/Global/Components/Modal.vue'
import CheckIcon from '@/Global/Icons/CheckIcon.vue'

// Props
const props = defineProps({
    mailSettings: {
        type: Object,
        required: true
    }
})

// Reactive data
const mailSettings = ref(props.mailSettings)
const saving = ref(false)
const sendingTest = ref(false)
const testMailModal = ref(false)
const testEmail = ref('')

// Form data
const form = reactive({
    driver: mailSettings.value.driver || 'smtp',
    host: mailSettings.value.host || '',
    port: mailSettings.value.port || '',
    username: mailSettings.value.username || '',
    password: mailSettings.value.password || '',
    encryption: mailSettings.value.encryption || '',
    from_address: mailSettings.value.from_address || '',
    from_name: mailSettings.value.from_name || ''
})

// Options
const driverOptions = [
    { value: 'smtp', label: 'SMTP' },
    { value: 'mailpit', label: 'Mailpit' },
    { value: 'log', label: 'Log' },
    { value: 'array', label: 'Array' }
]

const encryptionOptions = [
    { value: '', label: 'Yok' },
    { value: 'tls', label: 'TLS' },
    { value: 'ssl', label: 'SSL' }
]

// Methods
const saveSettings = () => {
    saving.value = true
    
    router.post(route('admin.settings.mail.update'), form, {
        onSuccess: () => {
            saving.value = false
        },
        onError: () => {
            saving.value = false
        }
    })
}

const sendTestMail = () => {
    if (!testEmail.value) {
        alert('Lütfen test e-posta adresini girin')
        return
    }
    
    sendingTest.value = true
    
    router.post(route('admin.settings.mail.test'), {
        email: testEmail.value
    }, {
        onSuccess: () => {
            sendingTest.value = false
            testMailModal.value = false
            testEmail.value = ''
        },
        onError: () => {
            sendingTest.value = false
        }
    })
}

// Lifecycle
onMounted(() => {
    // Form'u mevcut ayarlarla doldur
    Object.assign(form, mailSettings.value)
})
</script> 