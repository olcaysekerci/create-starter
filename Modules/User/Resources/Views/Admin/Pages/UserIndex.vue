<template>
    <AdminLayout 
        title="Kullanıcı Yönetimi" 
        page-title="Kullanıcı Yönetimi"
        :breadcrumbs="[
            { title: 'Kullanıcı Yönetimi' }
        ]"
    >
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 space-y-4 sm:space-y-0">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">Kullanıcılar</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Tüm kullanıcıları görüntüle ve yönet</p>
            </div>
            <Button @click="showCreateModal = true" variant="primary" class="w-full sm:w-auto">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span class="hidden sm:inline">Yeni Kullanıcı</span>
                <span class="sm:hidden">Kullanıcı Ekle</span>
            </Button>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900/40 rounded-lg">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Toplam Kullanıcı</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">{{ users.length }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 dark:bg-green-900/40 rounded-lg">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Aktif Kullanıcı</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">{{ activeUsersCount }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 sm:p-6 sm:col-span-2 lg:col-span-1">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 dark:bg-yellow-900/40 rounded-lg">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <p class="text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">Bekleyen Onay</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100">{{ pendingUsersCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Kullanıcı Listesi</h3>
                    <div class="flex items-center space-x-3">
                        <!-- Search -->
                        <SearchInput
                            v-model="searchQuery"
                            placeholder="Kullanıcı ara..."
                            clearable
                            class="w-full sm:w-64"
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
            <div class="p-4 sm:p-6">
                <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-gray-100">Yeni Kullanıcı Ekle</h3>
                <CreateUserForm @created="handleUserCreated" />
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

const props = defineProps({
    users: Array
})

const showCreateModal = ref(false)
const searchQuery = ref('')

// Computed properties for stats
const activeUsersCount = computed(() => {
    return props.users.filter(user => user.email_verified_at !== null).length
})

const pendingUsersCount = computed(() => {
    return props.users.filter(user => user.email_verified_at === null).length
})

// Filtered users based on search
const filteredUsers = computed(() => {
    if (!searchQuery.value) return props.users
    
    return props.users.filter(user => 
        user.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        user.email.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
})

const handleUserCreated = () => {
    showCreateModal.value = false
}

const handleEditUser = (user) => {
    console.log('handleEditUser called:', user)
    // Navigate to edit user page
    router.visit(`/admin/users/${user.id}/edit`)
}

const handleDeleteUser = (user) => {
    console.log('handleDeleteUser called:', user)
    // Delete user via Inertia
    router.delete(`/admin/users/${user.id}`, {
        onSuccess: () => {
            // User will be automatically removed from the list
            console.log(`${user.name} kullanıcısı başarıyla silindi`)
        },
        onError: (errors) => {
            console.error('Kullanıcı silinirken hata oluştu:', errors)
            alert('Kullanıcı silinirken bir hata oluştu. Lütfen tekrar deneyin.')
        }
    })
}
</script> 