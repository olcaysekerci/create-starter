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
            <span class="truncate">{{ value }}</span>
        </template>

        <!-- Custom created_at cell for mobile -->
        <template #cell-created_at="{ value }">
            <span>{{ formatDate(value) }}</span>
        </template>

        <!-- Custom last_login cell for mobile -->
        <template #cell-last_login="{ value }">
            <span>{{ formatDateTime(value) }}</span>
        </template>

        <!-- Custom actions slot -->
        <template #actions="{ item }">
            <div class="flex items-center space-x-2">
                <button
                    @click="handleEdit(item)"
                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 border border-indigo-200 dark:border-indigo-700 rounded hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors"
                >
                    Düzenle
                </button>
                <button
                    @click="handleDelete(item)"
                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 border border-red-200 dark:border-red-700 rounded hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                >
                    Sil
                </button>
            </div>
        </template>
    </DataTable>
</template>

<script setup>
import DataTable from '@/Global/Components/DataTable.vue'

const props = defineProps({
    users: Array
})

const emit = defineEmits(['edit-user', 'delete-user'])

// Mobile primary columns (id, name, email, last_login)
const mobilePrimaryColumns = ['id', 'name', 'email', 'last_login']

const columns = [
    {
        key: 'id',
        title: 'ID',
        type: 'text',
        cellClass: 'font-mono text-xs'
    },
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
    },
    {
        key: 'last_login',
        title: 'Son Giriş',
        type: 'date',
        cellClass: 'text-xs'
    }
]

const actions = [
    {
        key: 'edit',
        label: 'Düzenle',
        variant: 'primary',
        handler: (user) => {
            emit('edit-user', user)
        }
    },
    {
        key: 'delete',
        label: 'Sil',
        variant: 'danger',
        handler: (user) => {
            emit('delete-user', user)
        }
    }
]

const formatDate = (dateString) => {
    if (!dateString) return '-'
    return new Date(dateString).toLocaleDateString('tr-TR')
}

const formatDateTime = (dateString) => {
    if (!dateString) return '-'
    const d = new Date(dateString)
    return d.toLocaleDateString('tr-TR') + ' ' + d.toLocaleTimeString('tr-TR', { hour: '2-digit', minute: '2-digit' })
}

const handleEdit = (user) => {
    emit('edit-user', user)
}

const handleDelete = (user) => {
    emit('delete-user', user)
}
</script> 