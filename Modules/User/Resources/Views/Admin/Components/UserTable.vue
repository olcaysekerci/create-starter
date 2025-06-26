<template>
    <DataTable
        :data="users"
        :columns="columns"
        :actions="actions"
        empty-message="Henüz kullanıcı bulunmuyor"
    />
</template>

<script setup>
import DataTable from '@/Global/Components/DataTable.vue'
import EditIcon from '@/Global/Icons/EditIcon.vue'
import TrashIcon from '@/Global/Icons/TrashIcon.vue'

const props = defineProps({
    users: Array
})

const emit = defineEmits(['edit-user', 'delete-user'])

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
</script> 