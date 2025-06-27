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
            description="Sistem ve kullanıcı aktivitelerini görüntüleyin, filtreleyin ve analiz edin. Tüm işlemler otomatik olarak kaydedilir."
        >
            <template #actions>
                <Button 
                    @click="showStatsModal = !showStatsModal" 
                    variant="ghost" 
                    size="sm"
                    :class="{ 'bg-gray-100 dark:bg-gray-700': showStatsModal }"
                    title="İstatistikleri Göster/Gizle"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </Button>
                <Button 
                    @click="showFilterModal = !showFilterModal" 
                    variant="ghost" 
                    size="sm"
                    :class="{ 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-700': showFilterModal || hasActiveFilters }"
                    title="Filtreleri Göster/Gizle"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"/>
                    </svg>
                    <span v-if="activeFilterCount > 0" class="ml-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-blue-100 bg-blue-600 rounded-full">
                        {{ activeFilterCount }}
                    </span>
                </Button>
            </template>
        </PageHeader>

        <!-- Stats Items -->
        <div v-if="showStatsModal" class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-6">
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
                title="Toplam Aktivite"
                :value="stats.total"
                color="purple"
                :icon="ServerIcon"
            />
        </div>

        <!-- Filter Card -->
        <ActivityLogFilterCard 
            :show="showFilterModal"
            :filters="filters"
            :user-options="userOptions"
            :event-options="eventOptions"
            :model-options="modelOptions"
            @update-filter="handleFilterUpdate"
            @apply-filters="applyFilters"
            @clear-filters="clearFilters"
            @close="showFilterModal = false"
        />

        <!-- Flash Messages -->
        <FlashMessage />

        <!-- Activities Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Aktivite Listesi</h3>
                    <div class="flex items-center space-x-3">
                        <!-- Search -->
                        <SearchInput
                            v-model="searchQuery"
                            placeholder="Aktivite ara..."
                            clearable
                            @input="debouncedSearch"
                        />
                        <!-- Excel Export -->
                        <ExcelExportButton 
                            :data="filteredActivities"
                            :columns="columns"
                            filename="aktivite-loglari"
                            title="Excel"
                        />
                    </div>
                </div>
            </div>
            
            <DataTable
                :data="filteredActivities"
                :columns="columns"
                :actions="actions"
                empty-message="Henüz aktivite logu bulunmuyor"
            >
                <!-- ID Column -->
                <template #cell-id="{ value }">
                    <span class="font-mono text-xs text-gray-500 dark:text-gray-400">#{{ value }}</span>
                </template>

                <!-- Description Column -->
                <template #cell-description="{ value, item }">
                    <div class="flex items-center space-x-2">
                        <span v-if="item.is_admin_action" class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                            Admin
                        </span>
                        <span>{{ value }}</span>
                    </div>
                </template>

                <!-- Model Name Column -->
                <template #cell-model_name="{ value, item }">
                    <span>{{ item.subject_id ? `${value} #${item.subject_id}` : value }}</span>
                </template>

                <!-- IP Address Column -->
                <template #cell-ip_address="{ value }">
                    <span class="font-mono text-xs">{{ value || '-' }}</span>
                </template>

                <!-- Created At Column -->
                <template #cell-created_at="{ value }">
                    <div>
                        <div class="text-sm text-gray-900 dark:text-white">
                            {{ formatDate(value) }}
                        </div>
                    </div>
                </template>
            </DataTable>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                <Pagination 
                    :currentPage="activities.current_page"
                    :totalPages="activities.last_page"
                    :total="activities.total"
                    :perPage="activities.per_page"
                    @page-changed="goToPage"
                />
            </div>
        </div>

        <!-- Activity Detail Modal -->
        <Modal :show="showDetailModal" @close="showDetailModal = false">
            <div class="p-6 w-full max-w-2xl mx-auto">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Aktivite Detayı</h3>
                    <button @click="showDetailModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div v-if="selectedActivity" class="space-y-6">
                    <!-- Temel Bilgiler -->
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Kullanıcı</h4>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ selectedActivity.user_name }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Tarih</h4>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ formatDate(selectedActivity.created_at) }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">IP Adresi</h4>
                            <p class="text-sm text-gray-700 dark:text-gray-300 font-mono">{{ selectedActivity.ip_address || 'Bilinmiyor' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Model</h4>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ selectedActivity.model_name }}</p>
                        </div>
                    </div>

                    <!-- Açıklama -->
                    <div class="border-l-4 border-blue-400 pl-8">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-1">Açıklama</h4>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ selectedActivity.description }}</p>
                    </div>

                    <!-- Değişiklikler -->
                    <div v-if="selectedActivity.has_changes" class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Değişiklikler</h4>
                        <div class="space-y-3">
                            <div v-for="change in selectedActivity.formatted_changes" :key="change.field" class="border-l-2 border-gray-300 pl-3">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ change.field_name }}</p>
                                <div class="mt-1 text-sm">
                                    <span class="text-red-600 dark:text-red-400">Eski: {{ change.old_value }}</span>
                                    <span class="text-gray-500 mx-2">→</span>
                                    <span class="text-green-600 dark:text-green-400">Yeni: {{ change.new_value }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Özel Durumlar -->
                    <div v-if="selectedActivity.is_admin_action || selectedActivity.is_password_change || selectedActivity.is_email_change" class="flex flex-wrap gap-2">
                        <span v-if="selectedActivity.is_admin_action" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                            Admin İşlemi
                        </span>
                        <span v-if="selectedActivity.is_password_change" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                            Şifre Değişikliği
                        </span>
                        <span v-if="selectedActivity.is_email_change" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            Email Değişikliği
                        </span>
                    </div>

                    <!-- Teknik Detaylar -->
                    <details class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <summary class="text-sm font-medium text-gray-900 dark:text-white cursor-pointer">Teknik Detaylar</summary>
                        <div class="mt-3 space-y-2 text-sm text-gray-600 dark:text-gray-400">
                            <p><strong>ID:</strong> {{ selectedActivity.id }}</p>
                            <p><strong>Event:</strong> {{ selectedActivity.event }}</p>
                            <p><strong>Subject Type:</strong> {{ selectedActivity.subject_type }}</p>
                            <p><strong>Subject ID:</strong> {{ selectedActivity.subject_id }}</p>
                            <p><strong>User Agent:</strong> {{ selectedActivity.user_agent || 'Bilinmiyor' }}</p>
                        </div>
                    </details>
                </div>
            </div>
        </Modal>
    </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import { debounce } from 'lodash'
import AdminLayout from '@/Global/Layouts/AdminLayout.vue'
import DataTable from '@/Global/Components/DataTable.vue'
import SearchInput from '@/Global/Components/SearchInput.vue'
import Button from '@/Global/Components/Button.vue'
import Modal from '@/Global/Components/Modal.vue'
import Pagination from '@/Global/Components/Pagination.vue'
import ExcelExportButton from '@/Global/Components/ExcelExportButton.vue'
import PageHeader from '@/Global/Components/PageHeader.vue'
import StatItem from '@/Global/Components/StatItem.vue'
import FlashMessage from '@/Global/Components/FlashMessage.vue'
import RefreshIcon from '@/Global/Icons/RefreshIcon.vue'
import CheckIcon from '@/Global/Icons/CheckIcon.vue'
import ServerIcon from '@/Global/Icons/ServerIcon.vue'
import ActivityLogFilterCard from '@/Global/Components/ActivityLogFilterCard.vue'

const props = defineProps({
    activities: Object,
    stats: Object,
    filters: Object,
    current_filters: Object
})

const loading = ref(false)
const showFilterModal = ref(false)
const showStatsModal = ref(false) // Default olarak kapalı
const showDetailModal = ref(false)
const selectedActivity = ref(null)
const searchQuery = ref(props.current_filters?.search || '')

const filters = ref({
    user_id: props.current_filters?.user_id || '',
    event: props.current_filters?.event || '',
    model_type: props.current_filters?.model_type || '',
    date_from: props.current_filters?.date_from || '',
    date_to: props.current_filters?.date_to || '',
    search: props.current_filters?.search || '',
    admin_action: props.current_filters?.admin_action || '',
    ip_address: props.current_filters?.ip_address || '',
    has_changes: props.current_filters?.has_changes || '',
})

const userOptions = computed(() => {
    if (!props.filters?.users) return []
    return props.filters.users.map(user => ({
        value: user.id,
        label: user.name
    }))
})

const eventOptions = computed(() => {
    if (!props.filters?.event_types) return []
    return props.filters.event_types.map(event => ({
        value: event,
        label: event
    }))
})

const modelOptions = computed(() => {
    if (!props.filters?.model_types) return []
    return props.filters.model_types.map(model => ({
        value: model.value,
        label: model.label
    }))
})

// Filtered activities based on search
const filteredActivities = computed(() => {
    if (!searchQuery.value) return props.activities.data
    
    return props.activities.data.filter(activity => 
        activity.description.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        activity.user_name.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
})

// Active filter count
const activeFilterCount = computed(() => {
    let count = 0
    if (filters.value.user_id) count++
    if (filters.value.event) count++
    if (filters.value.model_type) count++
    if (filters.value.date_from) count++
    if (filters.value.date_to) count++
    if (filters.value.admin_action) count++
    if (filters.value.ip_address) count++
    if (filters.value.has_changes) count++
    return count
})

const hasActiveFilters = computed(() => {
    return activeFilterCount.value > 0
})

// DataTable columns
const columns = [
    {
        key: 'id',
        title: 'ID',
        type: 'text',
        cellClass: 'font-mono text-xs'
    },
    {
        key: 'user_name',
        title: 'Kullanıcı',
        type: 'primary'
    },
    {
        key: 'description', 
        title: 'Eylem',
        type: 'text'
    },
    {
        key: 'model_name',
        title: 'Kaynak',
        type: 'text'
    },
    {
        key: 'ip_address',
        title: 'IP',
        type: 'text',
        cellClass: 'font-mono text-xs'
    },
    {
        key: 'created_at',
        title: 'Tarih',
        type: 'date'
    }
]

// DataTable actions
const actions = [
    {
        key: 'view',
        label: 'Detay',
        variant: 'primary',
        size: 'sm',
        handler: (activity) => {
            viewActivity(activity)
        }
    }
]

const debouncedSearch = debounce(() => {
    // Search is handled by filteredActivities computed property
}, 300)

const applyFilters = () => {
    loading.value = true
    showFilterModal.value = false
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
        admin_action: '',
        ip_address: '',
        has_changes: '',
    }
    searchQuery.value = ''
    applyFilters()
}

const viewActivity = (activity) => {
    selectedActivity.value = activity
    showDetailModal.value = true
}

const formatDate = (date) => {
    return new Date(date).toLocaleString('tr-TR', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    })
}

const goToPage = (page) => {
    loading.value = true
    const params = { ...filters.value, page }
    router.get(route('admin.logs.index'), params, {
        preserveState: true,
        onFinish: () => loading.value = false
    })
}

const handleFilterUpdate = (key, value) => {
    filters.value[key] = value
}
</script> 