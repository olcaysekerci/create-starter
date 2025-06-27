<template>
    <DataTable
        :data="users"
        :columns="columns"
        :actions="actions"
        :mobile-primary-columns="mobilePrimaryColumns"
        empty-message="Henüz kullanıcı bulunmuyor"
    >
        <!-- Custom email cell for mobile -->
        <template #cell-email="{ value }">
            <div class="flex items-center">
                <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span class="truncate">{{ value }}</span>
            </div>
        </template>

        <!-- Custom created_at cell for mobile -->
        <template #cell-created_at="{ value }">
            <div class="flex items-center">
                <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>{{ formatDate(value) }}</span>
            </div>
        </template>

        <!-- Custom actions slot -->
        <template #actions="{ item }">
            <div class="flex items-center space-x-2">
                <button
                    @click="handleEdit(item)"
                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 border border-indigo-200 dark:border-indigo-700 rounded hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors"
                >
                    <EditIcon class="w-3 h-3 mr-1" />
                    <span class="hidden sm:inline">Düzenle</span>
                    <span class="sm:hidden">Düzenle</span>
                </button>
                <button
                    @click="handleDelete(item)"
                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 border border-red-200 dark:border-red-700 rounded hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                >
                    <TrashIcon class="w-3 h-3 mr-1" />
                    <span class="hidden sm:inline">Sil</span>
                    <span class="sm:hidden">Sil</span>
                </button>
            </div>
        </template>
    </DataTable>
</template>

<script setup>
import DataTable from '@/Global/Components/DataTable.vue'
import EditIcon from '@/Global/Icons/EditIcon.vue'
import TrashIcon from '@/Global/Icons/TrashIcon.vue'

const props = defineProps({
    users: Array
})

const emit = defineEmits(['edit-user', 'delete-user'])

// Mobile primary columns (name and email)
const mobilePrimaryColumns = ['name', 'email']

const columns = [
    {
        key: 'name',
        title: 'İsim',
        type: 'primary'
    },
    {
        key: 'email',
        title: 'E-posta'
    },
    {
        key: 'created_at',
        title: 'Kayıt Tarihi',
        type: 'date'
    }
]

const actions = [
    {
        key: 'edit',
        label: 'Düzenle',
        variant: 'primary',
        icon: EditIcon,
        handler: (user) => {
            console.log('Edit button clicked for user:', user)
            // Emit event to parent component
            emit('edit-user', user)
        }
    },
    {
        key: 'delete',
        label: 'Sil',
        variant: 'danger',
        icon: TrashIcon,
        handler: (user) => {
            console.log('Delete button clicked for user:', user)
            // Emit event to parent component to handle the modal
            emit('delete-user', user)
        }
    }
]

const formatDate = (dateString) => {
    if (!dateString) return '-'
    return new Date(dateString).toLocaleDateString('tr-TR')
}

const handleEdit = (user) => {
    emit('edit-user', user)
}

const handleDelete = (user) => {
    emit('delete-user', user)
}
</script> 