<template>
    <AdminLayout 
        title="Mail Detayı"
        page-title="Mail Detayı"
        :breadcrumbs="[
            { title: 'Mail Bildirimleri', href: route('admin.mail-notifications.index') },
            { title: 'Mail Detayı' }
        ]"
    >
        <!-- Page Header -->
        <PageHeader 
            title="Mail Detayı"
            description="Mail gönderim detaylarını görüntüleyin"
        >
            <template #actions>
                <Button 
                    @click="$router.back()" 
                    variant="secondary" 
                    size="sm"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Geri
                </Button>
            </template>
        </PageHeader>

        <!-- Flash Messages -->
        <FlashMessage />

        <!-- Mail Details -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Mail Bilgileri</h3>
            </div>

            <div class="p-6 space-y-6">
                <!-- Temel Bilgiler -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Alıcı</h4>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ mailLog.recipient }}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Durum</h4>
                        <span :class="mailLog.status_badge_class" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                            {{ mailLog.status_label }}
                        </span>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Tür</h4>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                            {{ mailLog.type }}
                        </span>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Oluşturulma Tarihi</h4>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ formatDate(mailLog.created_at) }}</p>
                    </div>
                    <div v-if="mailLog.sent_at">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Gönderim Tarihi</h4>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ formatDate(mailLog.sent_at) }}</p>
                    </div>
                    <div v-if="mailLog.retry_count > 0">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Yeniden Deneme</h4>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ mailLog.retry_count }} kez</p>
                    </div>
                </div>

                <!-- Konu -->
                <div class="border-l-4 border-blue-400 pl-4">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-1">Konu</h4>
                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ mailLog.subject }}</p>
                </div>

                <!-- İçerik -->
                <div class="border-l-4 border-green-400 pl-4">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">İçerik</h4>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <pre class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap font-mono">{{ mailLog.content }}</pre>
                    </div>
                </div>

                <!-- Hata Mesajı -->
                <div v-if="mailLog.error_message" class="border-l-4 border-red-400 pl-4">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Hata Mesajı</h4>
                    <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4">
                        <p class="text-sm text-red-700 dark:text-red-300">{{ mailLog.error_message }}</p>
                    </div>
                </div>

                <!-- Teknik Bilgiler -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-4">Teknik Bilgiler</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h5 class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">IP Adresi</h5>
                            <p class="text-sm text-gray-700 dark:text-gray-300 font-mono">{{ mailLog.ip_address || 'Bilinmiyor' }}</p>
                        </div>
                        <div>
                            <h5 class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">User Agent</h5>
                            <p class="text-sm text-gray-700 dark:text-gray-300 font-mono text-xs">{{ mailLog.user_agent || 'Bilinmiyor' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Metadata -->
                <div v-if="mailLog.metadata" class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-4">Ek Veriler</h4>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <pre class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap font-mono">{{ JSON.stringify(mailLog.metadata, null, 2) }}</pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-6 flex justify-end space-x-3">
            <Button @click="$router.back()" variant="secondary">
                Geri
            </Button>
            <Button 
                v-if="mailLog.status === 'failed' && mailLog.can_retry" 
                @click="retryMail" 
                variant="warning"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Yeniden Dene
            </Button>
        </div>
    </AdminLayout>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Global/Layouts/AdminLayout.vue'
import PageHeader from '@/Global/Components/PageHeader.vue'
import Button from '@/Global/Components/Button.vue'
import FlashMessage from '@/Global/Components/FlashMessage.vue'

const props = defineProps({
    mailLog: Object,
})

const retryMail = () => {
    if (confirm('Bu maili yeniden göndermek istediğinizden emin misiniz?')) {
        router.post(route('admin.mail-notifications.retry'), {
            mail_id: props.mailLog.id
        }, {
            onSuccess: () => {
                // Sayfa yenilenecek
            }
        })
    }
}

const formatDate = (dateString) => {
    if (!dateString) return '-'
    return new Date(dateString).toLocaleDateString('tr-TR', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    })
}
</script> 