<template>
    <AdminLayout 
        title="Mail Bildirimleri"
        page-title="Mail Bildirimleri"
        :breadcrumbs="[
            { title: 'Mail Bildirimleri' }
        ]"
    >
        <!-- Page Header -->
        <PageHeader 
            title="Mail Bildirimleri"
            description="Sistemdeki tüm mail gönderimlerini görüntüleyin, filtreleyin ve test edin. Tüm mailler otomatik olarak loglanır."
        >
            <template #actions>
                <Button 
                    @click="showTestModal = true" 
                    variant="primary" 
                    size="sm"
                    title="Test Mail Gönder"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Test Mail
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

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-6">
            <StatItem
                title="Toplam Mail"
                :value="stats.total"
                color="blue"
                :icon="ServerIcon"
            />
            <StatItem
                title="Gönderildi"
                :value="stats.sent"
                color="emerald"
                :icon="CheckIcon"
            />
            <StatItem
                title="Başarısız"
                :value="stats.failed"
                color="red"
                :icon="WarningIcon"
            />
            <StatItem
                title="Bugün"
                :value="stats.today"
                color="purple"
                :icon="RefreshIcon"
            />
        </div>

        <!-- Filter Card -->
        <FilterCard 
            :show="showFilterModal"
            :filters="filters"
            :filter-options="filterOptions"
            @update-filter="handleFilterUpdate"
            @apply-filters="applyFilters"
            @clear-filters="clearFilters"
            @close="showFilterModal = false"
        />

        <!-- Flash Messages -->
        <FlashMessage />

        <!-- Mail Logs Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Mail Logları</h3>
                    <div class="flex items-center space-x-3">
                        <!-- Search -->
                        <SearchInput
                            v-model="searchQuery"
                            placeholder="Mail ara..."
                            clearable
                            @input="debouncedSearch"
                        />
                        <!-- Retry Failed -->
                        <Button 
                            @click="retryFailedMails" 
                            variant="warning" 
                            size="sm"
                            :disabled="stats.failed === 0"
                            title="Başarısız Mailleri Yeniden Dene"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Yeniden Dene
                        </Button>
                    </div>
                </div>
            </div>
            
            <DataTable
                :data="mailLogs.data"
                :columns="columns"
                :actions="actions"
                empty-message="Henüz mail logu bulunmuyor"
                @action="handleTableAction"
            >
                <!-- ID Column -->
                <template #cell-id="{ value }">
                    <span class="font-mono text-xs text-gray-500 dark:text-gray-400">#{{ value }}</span>
                </template>

                <!-- Recipient Column -->
                <template #cell-recipient="{ value }">
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ value }}</span>
                </template>

                <!-- Subject Column -->
                <template #cell-subject="{ value }">
                    <span class="text-gray-900 dark:text-gray-100">{{ value }}</span>
                </template>

                <!-- Type Column -->
                <template #cell-type="{ value }">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                        {{ value }}
                    </span>
                </template>

                <!-- Status Column -->
                <template #cell-status="{ value, item }">
                    <span :class="getStatusBadgeClass(item.status)" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                        {{ getStatusLabel(item.status) }}
                    </span>
                </template>

                <!-- Sent At Column -->
                <template #cell-sent_at="{ value }">
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        {{ value ? formatDate(value) : '-' }}
                    </span>
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
                    :currentPage="mailLogs.current_page"
                    :totalPages="mailLogs.last_page"
                    :total="mailLogs.total"
                    :perPage="mailLogs.per_page"
                    @page-changed="goToPage"
                />
            </div>
        </div>

        <!-- Test Mail Modal -->
        <Modal :show="showTestModal" @close="showTestModal = false">
            <div class="p-6 w-full max-w-md mx-auto">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Test Mail Gönder</h3>
                    <button @click="showTestModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="sendTestMail" class="space-y-4">
                    <FormGroup label="E-posta Adresi" required>
                        <TextInput
                            v-model="testMailForm.email"
                            type="email"
                            placeholder="test@example.com"
                            required
                        />
                    </FormGroup>

                    <FormGroup label="Konu">
                        <TextInput
                            v-model="testMailForm.subject"
                            placeholder="Test Mail"
                        />
                    </FormGroup>

                    <div class="flex justify-end space-x-3 pt-4">
                        <Button @click="showTestModal = false" variant="secondary">
                            İptal
                        </Button>
                        <Button type="submit" variant="primary" :loading="sendingTestMail">
                            Gönder
                        </Button>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Mail Detail Modal -->
        <MailDetailModal
            :show="showDetailModal"
            :mail-log="selectedMailLog"
            @close="showDetailModal = false"
        />
    </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import { debounce } from 'lodash'
import AdminLayout from '@/Global/Layouts/AdminLayout.vue'
import PageHeader from '@/Global/Components/PageHeader.vue'
import StatItem from '@/Global/Components/StatItem.vue'
import FilterCard from '@/Global/Components/FilterCard.vue'
import DataTable from '@/Global/Components/DataTable.vue'
import Pagination from '@/Global/Components/Pagination.vue'
import Modal from '@/Global/Components/Modal.vue'
import Button from '@/Global/Components/Button.vue'
import SearchInput from '@/Global/Components/SearchInput.vue'
import FormGroup from '@/Global/Forms/FormGroup.vue'
import TextInput from '@/Global/Forms/TextInput.vue'
import FlashMessage from '@/Global/Components/FlashMessage.vue'
import MailDetailModal from '@/Global/Components/MailDetailModal.vue'
import ServerIcon from '@/Global/Icons/ServerIcon.vue'
import CheckIcon from '@/Global/Icons/CheckIcon.vue'
import WarningIcon from '@/Global/Icons/WarningIcon.vue'
import RefreshIcon from '@/Global/Icons/RefreshIcon.vue'
import MailIcon from '@/Global/Icons/MailIcon.vue'

const props = defineProps({
    mailLogs: Object,
    stats: Object,
    filters: Object,
    filterOptions: Object,
})

const showFilterModal = ref(false)
const showTestModal = ref(false)
const showDetailModal = ref(false)
const selectedMailLog = ref(null)
const searchQuery = ref(props.filters.search || '')
const sendingTestMail = ref(false)

const testMailForm = ref({
    email: '',
    subject: 'Test Mail - ' + document.title
})

// Computed properties
const activeFilterCount = computed(() => {
    let count = 0
    if (props.filters.status) count++
    if (props.filters.type) count++
    if (props.filters.recipient) count++
    if (props.filters.date_from || props.filters.date_to) count++
    return count
})

const hasActiveFilters = computed(() => activeFilterCount.value > 0)

// Table columns
const columns = [
    {
        key: 'id',
        title: 'ID',
        type: 'primary'
    },
    {
        key: 'recipient',
        title: 'Alıcı',
        type: 'primary'
    },
    {
        key: 'subject',
        title: 'Konu'
    },
    {
        key: 'type',
        title: 'Tür'
    },
    {
        key: 'status',
        title: 'Durum'
    },
    {
        key: 'sent_at',
        title: 'Gönderim Tarihi',
        type: 'date'
    },
    {
        key: 'created_at',
        title: 'Oluşturulma',
        type: 'date'
    }
]

// Table actions
const actions = [
    {
        key: 'view',
        label: 'Görüntüle',
        variant: 'info'
    }
]

// Methods
const debouncedSearch = debounce(() => {
    applyFilters()
}, 300)

const handleFilterUpdate = (filter, value) => {
    // Filter güncelleme işlemi
}

const applyFilters = () => {
    const params = {
        search: searchQuery.value,
        ...props.filters
    }
    
    router.get(route('admin.mail-notifications.index'), params, {
        preserveState: true,
        preserveScroll: true,
    })
}

const clearFilters = () => {
    searchQuery.value = ''
    router.get(route('admin.mail-notifications.index'), {}, {
        preserveState: true,
        preserveScroll: true,
    })
}

const goToPage = (page) => {
    router.get(route('admin.mail-notifications.index'), {
        ...props.filters,
        page: page
    }, {
        preserveState: true,
        preserveScroll: true,
    })
}

const sendTestMail = async () => {
    sendingTestMail.value = true
    
    try {
        await router.post(route('admin.mail-notifications.test'), testMailForm.value, {
            onSuccess: () => {
                showTestModal.value = false
                testMailForm.value = { email: '', subject: 'Test Mail - ' + document.title }
            },
            onError: (errors) => {
                // Test mail gönderim hatası
            }
        })
    } finally {
        sendingTestMail.value = false
    }
}

const retryFailedMails = () => {
    if (confirm('Başarısız mailleri yeniden göndermek istediğinizden emin misiniz?')) {
        router.post(route('admin.mail-notifications.retry'), {}, {
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
        minute: '2-digit'
    })
}

// Status helpers
const getStatusLabel = (status) => {
    const labels = {
        pending: 'Bekliyor',
        sent: 'Gönderildi',
        failed: 'Başarısız'
    }
    return labels[status] || status
}

const getStatusBadgeClass = (status) => {
    const classes = {
        pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400',
        sent: 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
        failed: 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400'
    }
    return classes[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'
}

// Table action handler
const handleTableAction = (actionKey, item) => {
    if (actionKey === 'view') {
        selectedMailLog.value = item
        showDetailModal.value = true
    }
}

// Initialize filters from props
onMounted(() => {
    if (props.filters) {
        searchQuery.value = props.filters.search || ''
    }
})
</script> 