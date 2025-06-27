<template>
    <Modal :show="show" @close="$emit('close')">
        <div class="p-6 w-full max-w-2xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Mail Detayları</h3>
                <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div v-if="mailLog" class="space-y-6">
                <!-- Mail Bilgileri -->
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Mail Bilgileri</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">Alıcı:</span>
                            <span class="ml-2 text-gray-600 dark:text-gray-400">{{ mailLog.recipient }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">Konu:</span>
                            <span class="ml-2 text-gray-600 dark:text-gray-400">{{ mailLog.subject }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">Tür:</span>
                            <span class="ml-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                    {{ mailLog.type }}
                                </span>
                            </span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">Durum:</span>
                            <span class="ml-2">
                                <span :class="mailLog.status_badge_class" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium">
                                    {{ mailLog.status_label }}
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Zaman Bilgileri -->
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Zaman Bilgileri</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">Oluşturulma:</span>
                            <span class="ml-2 text-gray-600 dark:text-gray-400">{{ formatDate(mailLog.created_at) }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">Gönderim:</span>
                            <span class="ml-2 text-gray-600 dark:text-gray-400">
                                {{ mailLog.sent_at ? formatDate(mailLog.sent_at) : 'Henüz gönderilmedi' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Teknik Bilgiler -->
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Teknik Bilgiler</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">IP Adresi:</span>
                            <span class="ml-2 text-gray-600 dark:text-gray-400">{{ mailLog.ip_address || '-' }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">Deneme Sayısı:</span>
                            <span class="ml-2 text-gray-600 dark:text-gray-400">{{ mailLog.retry_count || 0 }}</span>
                        </div>
                    </div>
                </div>

                <!-- Mail İçeriği -->
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Mail İçeriği</h4>
                    <div class="bg-white dark:bg-gray-900 rounded border border-gray-200 dark:border-gray-700 p-3">
                        <pre class="text-sm text-gray-600 dark:text-gray-400 whitespace-pre-wrap font-mono">{{ mailLog.content }}</pre>
                    </div>
                </div>

                <!-- Hata Bilgisi -->
                <div v-if="mailLog.error_message" class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-red-900 dark:text-red-100 mb-2">Hata Bilgisi</h4>
                    <p class="text-sm text-red-700 dark:text-red-300">{{ mailLog.error_message }}</p>
                </div>

                <!-- Aksiyonlar -->
                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <Button @click="$emit('close')" variant="secondary">
                        Kapat
                    </Button>
                    <Button 
                        v-if="mailLog.status === 'failed' && mailLog.retry_count < 3"
                        @click="retryMail" 
                        variant="warning"
                        :loading="retrying"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Yeniden Dene
                    </Button>
                </div>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import Modal from '@/Global/Components/Modal.vue'
import Button from '@/Global/Components/Button.vue'

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    mailLog: {
        type: Object,
        default: null
    }
})

const emit = defineEmits(['close', 'retry'])

const retrying = ref(false)

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

const retryMail = () => {
    if (!props.mailLog) return
    
    retrying.value = true
    
    router.post(route('admin.mail-notifications.retry-single', props.mailLog.id), {}, {
        onSuccess: () => {
            retrying.value = false
            emit('close')
        },
        onError: () => {
            retrying.value = false
        }
    })
}
</script> 