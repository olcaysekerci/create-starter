<template>
    <AdminLayout>
        <template #header>
            <PageHeader title="Sistem Ayarları" description="Tüm sistem ayarlarını buradan yönetebilirsiniz">
                <template #actions>
                    <Button variant="primary" @click="refreshSettings">
                        <RefreshIcon class="w-4 h-4 mr-2" />
                        Yenile
                    </Button>
                </template>
            </PageHeader>
        </template>

        <div class="space-y-6">
            <!-- Ayar Kategorileri -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <ActionCard
                    v-for="(category, key) in categories"
                    :key="key"
                    :title="category.name"
                    :description="category.description"
                    :icon="getCategoryIcon(key)"
                    :color="category.color"
                    :href="route(`admin.settings.${key}.index`)"
                    class="hover:shadow-lg transition-shadow duration-200"
                />
            </div>

            <!-- Hızlı Durum Özeti -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                        Sistem Durumu
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Mail Durumu -->
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    Mail Ayarları
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ currentSettings.mail.host }}:{{ currentSettings.mail.port }}
                                </p>
                            </div>
                        </div>

                        <!-- Uygulama Durumu -->
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    Uygulama
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ currentSettings.app.name }}
                                </p>
                            </div>
                        </div>

                        <!-- Sistem Durumu -->
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    Sistem
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ currentSettings.system.debug_mode ? 'Debug' : 'Production' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bilgi Kartı -->
            <Alert type="info" title="Bilgi">
                <p class="text-sm">
                    Sistem ayarlarını değiştirirken dikkatli olun. Bazı değişiklikler uygulamanın yeniden başlatılmasını gerektirebilir.
                </p>
            </Alert>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted, defineAsyncComponent } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Global/Layouts/AdminLayout.vue'
import PageHeader from '@/Global/Components/PageHeader.vue'
import ActionCard from '@/Global/Components/ActionCard.vue'
import Button from '@/Global/Components/Button.vue'
import Alert from '@/Global/Components/Alert.vue'
import RefreshIcon from '@/Global/Icons/RefreshIcon.vue'

// Icon bileşenleri
const MailIcon = defineAsyncComponent(() => import('@/Global/Icons/MailIcon.vue'))
const AppIcon = defineAsyncComponent(() => import('@/Global/Icons/AppIcon.vue'))
const SettingsIcon = defineAsyncComponent(() => import('@/Global/Icons/SettingsIcon.vue'))

// Props
const props = defineProps({
    categories: {
        type: Object,
        required: true
    },
    currentSettings: {
        type: Object,
        required: true
    }
})

// Reactive data
const categories = ref(props.categories)
const currentSettings = ref(props.currentSettings)

// Methods
const refreshSettings = () => {
    router.reload()
}

const getCategoryIcon = (iconName) => {
    const icons = {
        mail: MailIcon,
        app: AppIcon,
        system: SettingsIcon
    }
    
    return icons[iconName] || SettingsIcon
}

// Lifecycle
onMounted(() => {
    // Component mounted
})
</script> 