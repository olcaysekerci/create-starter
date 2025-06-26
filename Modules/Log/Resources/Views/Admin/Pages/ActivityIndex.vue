<template>
    <AdminLayout 
        title="Aktivite Logları"
        page-title="Aktivite Logları"
        :breadcrumbs="[
            { title: 'Aktivite Logları' }
        ]"
    >
        <!-- Page Header -->
        <PageHeader 
            title="Aktivite Logları"
            description="Sistem ve kullanıcı aktivitelerini görüntüleyin, filtreleyin ve yönetin."
        >
        </PageHeader>

        <!-- İstatistikler -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-6">
            <StatItem
                title="Bugün"
                :value="stats.today"
                color="blue"
                :icon="RefreshIcon"
            />
            
            <StatItem
                title="Bu Hafta"
                :value="stats.this_week"
                color="emerald"
                :icon="CheckIcon"
            />
            
            <StatItem
                title="Bu Ay"
                :value="stats.this_month"
                color="orange"
                :icon="WarningIcon"
            />
            
            <StatItem
                title="Toplam"
                :value="stats.total"
                color="purple"
                :icon="ServerIcon"
            />
        </div>

        <!-- Filtreler -->
        <FilterCard>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <FormGroup label="Kullanıcı">
                    <Select
                        v-model="filters.user_id"
                        :options="userOptions"
                        placeholder="Kullanıcı seçin"
                        @change="applyFilters"
                    />
                </FormGroup>

                <FormGroup label="Olay Türü">
                    <Select
                        v-model="filters.event"
                        :options="eventOptions"
                        placeholder="Olay türü seçin"
                        @change="applyFilters"
                    />
                </FormGroup>

                <FormGroup label="Model Türü">
                    <Select
                        v-model="filters.model_type"
                        :options="modelOptions"
                        placeholder="Model türü seçin"
                        @change="applyFilters"
                    />
                </FormGroup>

                <FormGroup label="Başlangıç Tarihi">
                    <TextInput
                        v-model="filters.date_from"
                        type="date"
                        @change="applyFilters"
                    />
                </FormGroup>

                <FormGroup label="Bitiş Tarihi">
                    <TextInput
                        v-model="filters.date_to"
                        type="date"
                        @change="applyFilters"
                    />
                </FormGroup>

                <FormGroup label="Arama">
                    <SearchInput
                        v-model="filters.search"
                        placeholder="Açıklama veya kullanıcı ara..."
                        @search="applyFilters"
                    />
                </FormGroup>
            </div>

            <template #actions>
                <Button @click="clearFilters" variant="secondary" size="sm">
                    Filtreleri Temizle
                </Button>
            </template>
        </FilterCard>

        <!-- Flash Messages -->
        <FlashMessage />

        <!-- Aktivite Tablosu -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Aktivite Listesi</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                ID
                            </th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Kullanıcı
                            </th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                İşlem
                            </th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Kaynak
                            </th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                IP
                            </th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Tarih
                            </th>
                            <th scope="col" class="relative px-4 py-2">
                                <span class="sr-only">İşlemler</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="activity in activities.data" :key="activity.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-2 whitespace-nowrap text-sm">
                                <div class="text-gray-500 dark:text-gray-400 font-mono text-xs">
                                    #{{ activity.id }}
                                </div>
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm">
                                <div class="font-medium text-gray-900 dark:text-white">
                                    {{ activity.user_name }}
                                </div>
                            </td>
                            <td class="px-4 py-2 text-sm">
                                <div class="flex items-center space-x-2">
                                    <span v-if="activity.event" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium"
                                          :class="getEventBadgeClass(activity.event)">
                                        {{ activity.formatted_description }}
                                    </span>
                                </div>
                                <div class="text-gray-900 dark:text-white mt-1">
                                    {{ activity.description }}
                                </div>
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm">
                                <div v-if="activity.subject_type" class="text-gray-900 dark:text-white">
                                    {{ activity.model_name }}
                                </div>
                                <div v-if="activity.subject_id" class="text-xs text-gray-500 dark:text-gray-400">
                                    ID: {{ activity.subject_id }}
                                </div>
                                <div v-if="!activity.subject_type" class="text-gray-400 dark:text-gray-500 text-xs italic">
                                    Sistem
                                </div>
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm">
                                <div class="text-gray-600 dark:text-gray-400 font-mono text-xs">
                                    {{ activity.ip_address || '-' }}
                                </div>
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm">
                                <div class="text-gray-900 dark:text-white">
                                    {{ formatDate(activity.created_at) }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ formatTime(activity.created_at) }}
                                </div>
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-right text-sm">
                                <Button
                                    @click="viewActivity(activity)"
                                    variant="ghost"
                                    size="sm"
                                    class="p-1"
                                    title="Detayları Görüntüle"
                                >
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </Button>
                            </td>
                        </tr>
                        
                        <tr v-if="activities.data.length === 0">
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center">
                                    <svg class="w-8 h-8 text-gray-300 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="font-medium">Aktivite bulunamadı</p>
                                    <p class="text-sm">Filtreleri değiştirmeyi deneyin.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                <Pagination :data="activities" />
            </div>
        </div>

        <!-- Activity Detail Modal -->
        <Modal :show="showDetailModal" @close="showDetailModal = false">
            <div v-if="selectedActivity" class="p-6">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Aktivite Detayları
                    </h3>
                    <button @click="showDetailModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Content -->
                <div class="space-y-4 max-h-96 overflow-y-auto">
                    <!-- Ana Bilgiler -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">#{{ selectedActivity.id }}</span>
                                <span class="inline-flex items-center px-2 py-1 rounded text-sm font-medium"
                                      :class="getEventBadgeClass(selectedActivity.event)">
                                    {{ selectedActivity.formatted_description }}
                                </span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span v-if="selectedActivity.is_admin_action" 
                                      class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-400">
                                    Admin
                                </span>
                                <span v-if="selectedActivity.is_password_change" 
                                      class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-400">
                                    🔒 Şifre
                                </span>
                                <span v-if="selectedActivity.is_email_change" 
                                      class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400">
                                    📧 Email
                                </span>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Kullanıcı:</span>
                                <span class="text-gray-900 dark:text-white font-medium ml-2">{{ selectedActivity.user_name }}</span>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Tarih:</span>
                                <span class="text-gray-900 dark:text-white ml-2">{{ formatDate(selectedActivity.created_at) }} {{ formatTime(selectedActivity.created_at) }}</span>
                            </div>
                        </div>

                        <div v-if="selectedActivity.subject_type" class="grid grid-cols-2 gap-4 text-sm mt-2">
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Kaynak:</span>
                                <span class="text-gray-900 dark:text-white ml-2">{{ selectedActivity.model_name }}</span>
                            </div>
                            <div v-if="selectedActivity.subject_id">
                                <span class="text-gray-600 dark:text-gray-400">ID:</span>
                                <span class="text-gray-900 dark:text-white font-mono ml-2">{{ selectedActivity.subject_id }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Açıklama -->
                    <div class="border-l-4 border-blue-400 pl-8">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-1">Açıklama</h4>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ selectedActivity.description }}</p>
                    </div>

                    <!-- Değişiklikler -->
                    <div v-if="selectedActivity.has_changes && selectedActivity.formatted_changes.length > 0">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                            Değişiklikler
                        </h4>
                        <div class="space-y-3">
                            <div v-for="change in selectedActivity.formatted_changes" :key="change.field" 
                                 class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ change.field_name }}</span>
                                    <span v-if="change.is_important" 
                                          class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/20 dark:text-red-400">
                                        Önemli
                                    </span>
                                </div>
                                <div class="flex items-center space-x-3 text-xs">
                                    <div class="flex-1">
                                        <div class="text-gray-500 dark:text-gray-400 mb-1">Önceki</div>
                                        <div class="bg-red-50 dark:bg-red-900/10 text-red-700 dark:text-red-400 px-2 py-1 rounded font-mono">
                                            {{ change.old_value }}
                                        </div>
                                    </div>
                                    <div class="text-gray-400">→</div>
                                    <div class="flex-1">
                                        <div class="text-gray-500 dark:text-gray-400 mb-1">Yeni</div>
                                        <div class="bg-green-50 dark:bg-green-900/10 text-green-700 dark:text-green-400 px-2 py-1 rounded font-mono">
                                            {{ change.new_value }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Silinen Kayıt Bilgileri -->
                    <div v-if="selectedActivity.deleted_info" class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-red-800 dark:text-red-400 mb-3 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Silinen Kayıt
                        </h4>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <span class="text-red-700 dark:text-red-400">Ad:</span>
                                <span class="text-red-900 dark:text-red-300 font-medium ml-2">{{ selectedActivity.deleted_info.name }}</span>
                            </div>
                            <div>
                                <span class="text-red-700 dark:text-red-400">E-posta:</span>
                                <span class="text-red-900 dark:text-red-300 ml-2">{{ selectedActivity.deleted_info.email }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Teknik Bilgiler -->
                    <details class="border border-gray-200 dark:border-gray-600 rounded-lg">
                        <summary class="cursor-pointer p-3 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            🔧 Teknik Bilgiler
                        </summary>
                        <div class="p-3 border-t border-gray-200 dark:border-gray-600 space-y-2">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">IP:</span>
                                    <span class="text-gray-900 dark:text-white font-mono ml-2">{{ selectedActivity.ip_address || '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">Browser:</span>
                                    <span class="text-gray-900 dark:text-white text-xs ml-2">{{ getBrowserName(selectedActivity.user_agent) }}</span>
                                </div>
                            </div>
                            <details class="mt-2">
                                <summary class="cursor-pointer text-xs text-gray-500 dark:text-gray-400">Raw Data</summary>
                                <pre class="text-xs text-gray-600 dark:text-gray-400 mt-2 p-2 bg-gray-100 dark:bg-gray-800 rounded overflow-x-auto">{{ JSON.stringify(selectedActivity.properties, null, 2) }}</pre>
                            </details>
                        </div>
                    </details>
                </div>

                <!-- Footer -->
                <div class="flex justify-end mt-6 pt-4 border-t border-gray-200 dark:border-gray-600">
                    <button @click="showDetailModal = false" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-md hover:bg-gray-50 dark:hover:bg-gray-500 transition-colors">
                        Kapat
                    </button>
                </div>
            </div>
        </Modal>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Global/Layouts/AdminLayout.vue'
import PageHeader from '@/Global/Components/PageHeader.vue'
import StatItem from '@/Global/Components/StatItem.vue'
import FilterCard from '@/Global/Components/FilterCard.vue'
import FormGroup from '@/Global/Forms/FormGroup.vue'
import Select from '@/Global/Forms/Select.vue'
import TextInput from '@/Global/Forms/TextInput.vue'
import SearchInput from '@/Global/Components/SearchInput.vue'
import Button from '@/Global/Components/Button.vue'
import Modal from '@/Global/Components/Modal.vue'
import Pagination from '@/Global/Components/Pagination.vue'
import FlashMessage from '@/Global/Components/FlashMessage.vue'
import UserIcon from '@/Global/Icons/UserIcon.vue'
import RefreshIcon from '@/Global/Icons/RefreshIcon.vue'
import CheckIcon from '@/Global/Icons/CheckIcon.vue'
import WarningIcon from '@/Global/Icons/WarningIcon.vue'
import ServerIcon from '@/Global/Icons/ServerIcon.vue'

const props = defineProps({
    activities: Object,
    stats: Object,
    filters: Object,
    current_filters: Object,
})

const loading = ref(false)
const showDetailModal = ref(false)
const selectedActivity = ref(null)

const filters = ref({
    user_id: props.current_filters?.user_id || '',
    event: props.current_filters?.event || '',
    model_type: props.current_filters?.model_type || '',
    date_from: props.current_filters?.date_from || '',
    date_to: props.current_filters?.date_to || '',
    search: props.current_filters?.search || '',
})

const userOptions = computed(() => [
    { value: '', label: 'Tüm Kullanıcılar' },
    ...props.filters.users.map(user => ({
        value: user.id,
        label: user.name
    }))
])

const eventOptions = computed(() => [
    { value: '', label: 'Tüm Olaylar' },
    ...props.filters.event_types.map(event => ({
        value: event,
        label: event
    }))
])

const modelOptions = computed(() => [
    { value: '', label: 'Tüm Modeller' },
    ...props.filters.model_types.map(model => ({
        value: model.value,
        label: model.label
    }))
])

const applyFilters = () => {
    loading.value = true
    router.get(route('admin.logs.index'), filters.value, {
        preserveState: true,
        onFinish: () => loading.value = false
    })
}

const clearFilters = () => {
    filters.value = {
        user_id: '',
        event: '',
        model_type: '',
        date_from: '',
        date_to: '',
        search: '',
    }
    applyFilters()
}

const viewActivity = (activity) => {
    selectedActivity.value = activity
    showDetailModal.value = true
}

const getEventBadgeClass = (event) => {
    const classes = {
        'created': 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
        'updated': 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400',
        'deleted': 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400',
        'login': 'bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-400',
        'logout': 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
        'profile_updated': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400',
        'password_changed': 'bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-400'
    }
    return classes[event] || 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
}

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('tr-TR')
}

const formatTime = (dateString) => {
    return new Date(dateString).toLocaleTimeString('tr-TR', { 
        hour: '2-digit', 
        minute: '2-digit' 
    })
}

const getBrowserName = (userAgent) => {
    if (!userAgent) return '-'
    if (userAgent.includes('Chrome')) return 'Chrome'
    if (userAgent.includes('Firefox')) return 'Firefox'
    if (userAgent.includes('Safari')) return 'Safari'
    if (userAgent.includes('Edge')) return 'Edge'
    return 'Diğer'
}
</script> 