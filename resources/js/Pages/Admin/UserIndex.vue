<template>
    <AdminLayout title="Kullanıcı Yönetimi">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-semibold">Kullanıcılar</h2>
                            <Button @click="showCreateModal = true">
                                Yeni Kullanıcı
                            </Button>
                        </div>
                        
                        <UserTable :users="users" />
                        
                        <!-- Create Modal -->
                        <Modal :show="showCreateModal" @close="showCreateModal = false">
                            <div class="p-6">
                                <h3 class="text-lg font-medium mb-4">Yeni Kullanıcı</h3>
                                <CreateUserForm @created="handleUserCreated" />
                            </div>
                        </Modal>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import AdminLayout from '@/Global/Layouts/AdminLayout.vue'
import UserTable from '@/Components/Admin/UserTable.vue'
import CreateUserForm from '@/Components/Admin/CreateUserForm.vue'
import Button from '@/Global/Components/Button.vue'
import Modal from '@/Global/Components/Modal.vue'

defineProps({
    users: Array
})

const showCreateModal = ref(false)

const handleUserCreated = () => {
    showCreateModal.value = false
}
</script> 