<template>
    <AdminLayout title="Kullanıcı Yönetimi" page-title="Kullanıcı Yönetimi">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Kullanıcılar</h2>
                <p class="text-sm text-gray-600 mt-1">Tüm kullanıcıları görüntüle ve yönet</p>
            </div>
            <Button @click="showCreateModal = true" variant="primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Yeni Kullanıcı
            </Button>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Toplam Kullanıcı</p>
                        <p class="text-2xl font-bold text-gray-900">{{ users.length }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Aktif Kullanıcı</p>
                        <p class="text-2xl font-bold text-gray-900">{{ activeUsersCount }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Bekleyen Onay</p>
                        <p class="text-2xl font-bold text-gray-900">{{ pendingUsersCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Kullanıcı Listesi</h3>
                    <div class="flex items-center space-x-3">
                        <!-- Search -->
                        <div class="relative">
                            <input 
                                type="text" 
                                placeholder="Kullanıcı ara..." 
                                class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                v-model="searchQuery"
                            >
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>
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
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import AdminLayout from '@/Global/Layouts/AdminLayout.vue'
import UserTable from '../Components/UserTable.vue'
import CreateUserForm from '../Components/CreateUserForm.vue'
import Button from '@/Global/Components/Button.vue'
import Modal from '@/Global/Components/Modal.vue'

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
</script> 