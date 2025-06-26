<template>
    <AdminLayout 
        title="Kullanıcı Yönetimi"
        page-title="Kullanıcı Yönetimi"
        :breadcrumbs="[
            { title: 'Kullanıcı Yönetimi' }
        ]"
    >
        <!-- Page Header -->
        <PageHeader 
            title="Kullanıcı Yönetimi"
            description="Kullanıcı hesaplarını görüntüleyin, düzenleyin ve yönetin. Yeni kullanıcı ekleyebilir, mevcut kullanıcıları güncelleyebilirsiniz."
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
                <Button 
                    @click="showCreateModal = true" 
                    variant="primary" 
                    size="sm"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Yeni Kullanıcı
                </Button>
            </template>
        </PageHeader>

        <!-- Stats Items -->
        <div v-if="showStatsModal" class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-6">
            <StatItem
                title="Toplam Kullanıcı"
                :value="users.length"
                color="blue"
                :icon="UserIcon"
            />
            
            <StatItem
                title="Aktif Kullanıcı"
                :value="activeUsersCount"
                color="emerald"
                :icon="CheckIcon"
                change="+12%"
                changeType="increase"
            />
            
            <StatItem
                title="Bekleyen Onay"
                :value="pendingUsersCount"
                color="orange"
                :icon="WarningIcon"
                change="-3%"
                changeType="decrease"
            />
        </div>

        <!-- Filter Card -->
        <FilterCard 
            :show="showFilterModal"
            :filters="filters"
            @update-filter="handleFilterUpdate"
            @apply-filters="applyFilters"
            @clear-filters="clearFilters"
            @close="showFilterModal = false"
        />

        <!-- Flash Messages -->
        <FlashMessage />

        <!-- Users Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Kullanıcı Listesi</h3>
                    <div class="flex items-center space-x-3">
                        <!-- Search -->
                        <SearchInput
                            v-model="searchQuery"
                            placeholder="Kullanıcı ara..."
                            clearable
                        />
                        <!-- Excel Export -->
                        <ExcelExportButton
                            :data="filteredUsers"
                            :columns="tableColumns"
                            filename="kullanicilar"
                            @export-complete="handleExportComplete"
                        />
                    </div>
                </div>
            </div>
            
            <UserTable 
                :users="filteredUsers" 
                @edit-user="handleEditUser"
                @delete-user="handleDeleteUser"
            />
        </div>
        
        <!-- Create Modal -->
        <Modal :show="showCreateModal" @close="showCreateModal = false">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Yeni Kullanıcı Ekle</h3>
                <CreateUserForm @created="handleUserCreated" />
            </div>
        </Modal>

        <!-- Delete Confirmation Modal -->
        <DeleteConfirmationModal
            :show="showDeleteModal"
            :title="`${userToDelete?.name || 'Kullanıcı'} Silinecek`"
            :message="`${userToDelete?.name || 'Bu kullanıcı'} kalıcı olarak silinecek. Bu işlem geri alınamaz.`"
            confirm-text="Evet, Sil"
            cancel-text="İptal"
            :processing="deleteProcessing"
            @close="handleDeleteModalClose"
            @confirm="confirmDeleteUser"
        >
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ userToDelete?.name }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">
                            {{ userToDelete?.email }}
                        </p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">
                            Kayıt: {{ userToDelete?.created_at ? new Date(userToDelete.created_at).toLocaleDateString('tr-TR') : '' }}
                        </p>
                    </div>
                </div>
            </div>
        </DeleteConfirmationModal>

    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Global/Layouts/AdminLayout.vue'
import UserTable from '../../../../Modules/User/Resources/Views/Admin/Components/UserTable.vue'
import CreateUserForm from '../../../../Modules/User/Resources/Views/Admin/Components/CreateUserForm.vue'
import Button from '@/Global/Components/Button.vue'
import Modal from '@/Global/Components/Modal.vue'
import SearchInput from '@/Global/Components/SearchInput.vue'
import StatItem from '@/Global/Components/StatItem.vue'
import FilterCard from '@/Global/Components/FilterCard.vue'
import ExcelExportButton from '@/Global/Components/ExcelExportButton.vue'
import PageHeader from '@/Global/Components/PageHeader.vue'
import UserIcon from '@/Global/Icons/UserIcon.vue'
import CheckIcon from '@/Global/Icons/CheckIcon.vue'
import WarningIcon from '@/Global/Icons/WarningIcon.vue'
import DeleteConfirmationModal from '@/Global/Components/DeleteConfirmationModal.vue'
import FlashMessage from '@/Global/Components/FlashMessage.vue'

const props = defineProps({
    users: Array
})

const showCreateModal = ref(false)
const showFilterModal = ref(false)
const showStatsModal = ref(false) // Default olarak kapalı
const showDeleteModal = ref(false)
const userToDelete = ref(null)
const deleteProcessing = ref(false)
const searchQuery = ref('')

// Filter states
const filters = ref({
    status: '',
    dateFrom: '',
    dateTo: '',
    emailDomain: ''
})

// Table columns for Excel export
const tableColumns = [
    { key: 'name', title: 'İsim' },
    { key: 'email', title: 'E-posta' },
    { key: 'created_at', title: 'Kayıt Tarihi', type: 'date' }
]

// Icon components are imported above

// Computed properties for stats
const activeUsersCount = computed(() => {
    return props.users.filter(user => user.email_verified_at !== null).length
})

const pendingUsersCount = computed(() => {
    return props.users.filter(user => user.email_verified_at === null).length
})

// Filtered users with search and filters
const filteredUsers = computed(() => {
    let result = props.users

    // Search filter
    if (searchQuery.value) {
        result = result.filter(user => 
            user.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            user.email.toLowerCase().includes(searchQuery.value.toLowerCase())
        )
    }

    // Status filter
    if (filters.value.status) {
        if (filters.value.status === 'active') {
            result = result.filter(user => user.email_verified_at !== null)
        } else if (filters.value.status === 'pending') {
            result = result.filter(user => user.email_verified_at === null)
        }
    }

    // Date range filter
    if (filters.value.dateFrom) {
        result = result.filter(user => new Date(user.created_at) >= new Date(filters.value.dateFrom))
    }

    if (filters.value.dateTo) {
        result = result.filter(user => new Date(user.created_at) <= new Date(filters.value.dateTo))
    }

    // Email domain filter
    if (filters.value.emailDomain) {
        result = result.filter(user => user.email.toLowerCase().includes(filters.value.emailDomain.toLowerCase()))
    }

    return result
})

// Filter indicators
const hasActiveFilters = computed(() => {
    return Object.values(filters.value).some(value => value !== '')
})

const activeFilterCount = computed(() => {
    return Object.values(filters.value).filter(value => value !== '').length
})

// Methods
const handleUserCreated = () => {
    showCreateModal.value = false
    // Refresh page to show new user
    router.reload({ only: ['users'] })
}

const handleFilterUpdate = ({ key, value }) => {
    filters.value[key] = value
}

const applyFilters = () => {
    // Filtreler artık real-time çalışıyor, bu metod boş kalabilir
    console.log('Filters applied:', filters.value)
}

const clearFilters = () => {
    filters.value = {
        status: '',
        dateFrom: '',
        dateTo: '',
        emailDomain: ''
    }
}

const handleExportComplete = (filename) => {
    console.log('Excel export completed:', filename)
}

const handleEditUser = (user) => {
    console.log('handleEditUser called:', user)
    // Navigate to edit user page
    router.visit(`/admin/users/${user.id}/edit`)
}

const handleDeleteUser = (user) => {
    console.log('handleDeleteUser called:', user)
    userToDelete.value = user
    showDeleteModal.value = true
}

const handleDeleteModalClose = () => {
    if (!deleteProcessing.value) {
        showDeleteModal.value = false
        userToDelete.value = null
    }
}

const confirmDeleteUser = () => {
    if (!userToDelete.value) return
    
    deleteProcessing.value = true
    
    // Delete user via Inertia with preserveState: false to ensure flash messages are received
    router.delete(`/admin/users/${userToDelete.value.id}`, {
        preserveState: false,
        preserveScroll: true,
        onSuccess: (page) => {
            // User will be automatically removed from the list
            showDeleteModal.value = false
            userToDelete.value = null
            deleteProcessing.value = false
            // Flash message will be shown automatically from backend
        },
        onError: (errors) => {
            console.error('Kullanıcı silinirken hata oluştu:', errors)
            deleteProcessing.value = false
            // Modal açık kalır, kullanıcı tekrar deneyebilir
        }
    })
}
</script> 