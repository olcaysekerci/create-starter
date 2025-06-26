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
import { router } from '@inertiajs/vue3'

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
        handler: (user) => {
            console.log('Edit button clicked for user:', user)
            console.log('Navigating to:', `/admin/users/${user.id}/edit`)
            // Emit event to parent or navigate to edit page
            emit('edit-user', user)
            // Navigate directly:
            router.visit(`/admin/users/${user.id}/edit`)
        }
    },
    {
        key: 'delete',
        label: 'Sil',
        variant: 'danger',
        handler: (user) => {
            console.log('Delete button clicked for user:', user)
            // Confirm before delete
            if (confirm(`${user.name} kullanıcısını silmek istediğinize emin misiniz?`)) {
                console.log('Deleting user:', `/admin/users/${user.id}`)
                emit('delete-user', user)
                // Delete directly:
                router.delete(`/admin/users/${user.id}`, {
                    onSuccess: () => {
                        console.log(`${user.name} kullanıcısı başarıyla silindi`)
                    },
                    onError: (errors) => {
                        console.error('Kullanıcı silinirken hata oluştu:', errors)
                        alert('Kullanıcı silinirken bir hata oluştu. Lütfen tekrar deneyin.')
                    }
                })
            }
        }
    }
]
</script> 