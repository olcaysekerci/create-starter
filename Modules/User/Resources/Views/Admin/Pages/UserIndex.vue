<template>
    <AdminLayout 
        title="Kullanıcı Yönetimi" 
        page-title="Kullanıcı Yönetimi"
        :breadcrumbs="[
            { title: 'Kullanıcı Yönetimi' }
        ]"
    >
        <!-- Header Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="mb-4 sm:mb-0">
                    <h1 class="text-2xl font-bold text-gray-900">Kullanıcılar</h1>
                    <p class="text-sm text-gray-500 mt-1">Tüm kullanıcıları görüntüle ve yönet</p>
                </div>
                <div class="flex items-center space-x-3">
                    <Button @click="showFilterModal = true" variant="secondary" :class="{ 'bg-blue-50 border-blue-200': hasActiveFilters }">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"/>
                        </svg>
                        Filtrele
                        <span v-if="hasActiveFilters" class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-blue-100 bg-blue-600 rounded-full">
                            {{ activeFilterCount }}
                        </span>
                    </Button>
                    <Button @click="showCreateModal = true" variant="primary">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Yeni Kullanıcı
                    </Button>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <StatCard
                title="Toplam Kullanıcı"
                :value="users.length"
                color="blue"
                :icon="userIcon"
            />
            
            <StatCard
                title="Aktif Kullanıcı"
                :value="activeUsersCount"
                color="green"
                :icon="checkIcon"
            />
            
            <StatCard
                title="Bekleyen Onay"
                :value="pendingUsersCount"
                color="yellow"
                :icon="warningIcon"
            />
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Kullanıcı Listesi</h3>
                    <div class="flex items-center space-x-3">
                        <!-- Search -->
                        <SearchInput
                            v-model="searchQuery"
                            placeholder="Kullanıcı ara..."
                            clearable
                        />
                    </div>
                </div>
            </div>
            
            <UserTable :users="filteredUsers" />
        </div>
        
        <!-- Create Modal -->
        <Modal :show="showCreateModal" @close="showCreateModal = false">
            <div class="p-6">
                <h3 class="text-lg font-medium mb-4">Yeni Kullanıcı Ekle</h3>
                <CreateUserForm @created="handleUserCreated" />
            </div>
        </Modal>

        <!-- Filter Modal -->
        <Modal :show="showFilterModal" @close="showFilterModal = false" max-width="lg">
            <div class="p-6">
                <h3 class="text-lg font-medium mb-4">Filtrele</h3>
                
                <div class="space-y-4">
                    <!-- Status Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Durum</label>
                        <select v-model="filters.status" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tümü</option>
                            <option value="active">Aktif</option>
                            <option value="pending">Bekleyen</option>
                        </select>
                    </div>

                    <!-- Date Range Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kayıt Tarihi</label>
                        <div class="grid grid-cols-2 gap-3">
                            <input
                                type="date"
                                v-model="filters.dateFrom"
                                class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Başlangıç"
                            >
                            <input
                                type="date"
                                v-model="filters.dateTo"
                                class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Bitiş"
                            >
                        </div>
                    </div>

                    <!-- Email Domain Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">E-posta Domain</label>
                        <input
                            type="text"
                            v-model="filters.emailDomain"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Örn: gmail.com"
                        >
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3 mt-6">
                    <Button variant="secondary" @click="clearFilters">
                        Temizle
                    </Button>
                    <Button variant="primary" @click="applyFilters">
                        Filtrele
                    </Button>
                </div>
            </div>
        </Modal>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Global/Layouts/AdminLayout.vue'
import UserTable from '../Components/UserTable.vue'
import CreateUserForm from '../Components/CreateUserForm.vue'
import Button from '@/Global/Components/Button.vue'
import Modal from '@/Global/Components/Modal.vue'
import SearchInput from '@/Global/Components/SearchInput.vue'
import StatCard from '@/Global/Components/StatCard.vue'

const props = defineProps({
    users: Array
})

const showCreateModal = ref(false)
const showFilterModal = ref(false)
const searchQuery = ref('')

// Filter states
const filters = ref({
    status: '',
    dateFrom: '',
    dateTo: '',
    emailDomain: ''
})

const appliedFilters = ref({
    status: '',
    dateFrom: '',
    dateTo: '',
    emailDomain: ''
})

// Icons
const userIcon = {
    template: `<svg fill="currentColor" viewBox="0 0 20 20">
        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
    </svg>`
}

const checkIcon = {
    template: `<svg fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
    </svg>`
}

const warningIcon = {
    template: `<svg fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
    </svg>`
}

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
    if (appliedFilters.value.status) {
        if (appliedFilters.value.status === 'active') {
            result = result.filter(user => user.email_verified_at !== null)
        } else if (appliedFilters.value.status === 'pending') {
            result = result.filter(user => user.email_verified_at === null)
        }
    }

    // Date range filter
    if (appliedFilters.value.dateFrom) {
        result = result.filter(user => new Date(user.created_at) >= new Date(appliedFilters.value.dateFrom))
    }

    if (appliedFilters.value.dateTo) {
        result = result.filter(user => new Date(user.created_at) <= new Date(appliedFilters.value.dateTo))
    }

    // Email domain filter
    if (appliedFilters.value.emailDomain) {
        result = result.filter(user => user.email.toLowerCase().includes(appliedFilters.value.emailDomain.toLowerCase()))
    }

    return result
})

// Filter indicators
const hasActiveFilters = computed(() => {
    return Object.values(appliedFilters.value).some(value => value !== '')
})

const activeFilterCount = computed(() => {
    return Object.values(appliedFilters.value).filter(value => value !== '').length
})

// Methods
const handleUserCreated = () => {
    showCreateModal.value = false
    // Refresh page to show new user
    router.reload({ only: ['users'] })
}

const applyFilters = () => {
    appliedFilters.value = { ...filters.value }
    showFilterModal.value = false
}

const clearFilters = () => {
    filters.value = {
        status: '',
        dateFrom: '',
        dateTo: '',
        emailDomain: ''
    }
    appliedFilters.value = {
        status: '',
        dateFrom: '',
        dateTo: '',
        emailDomain: ''
    }
    showFilterModal.value = false
}
</script> 