<template>
    <DataTable
        :data="users"
        :columns="columns"
        :actions="actions"
        empty-message="Henüz kullanıcı bulunmuyor"
    >
        <template #cell-email_verified_at="{ item }">
            <span :class="[
                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                item.email_verified_at ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
            ]">
                {{ item.email_verified_at ? 'Aktif' : 'Bekleyen' }}
            </span>
        </template>
    </DataTable>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import DataTable from '@/Global/Components/DataTable.vue'

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
        key: 'email_verified_at',
        title: 'Durum',
        type: 'custom'
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
            editUser(user)
        }
    },
    {
        key: 'delete',
        label: 'Sil',
        variant: 'danger',
        handler: (user) => {
            deleteUser(user)
        }
    }
]

// Action handlers
const editUser = (user) => {
    // Kullanıcı düzenleme sayfasına yönlendir
    router.visit(`/admin/users/${user.id}/edit`)
}

const deleteUser = (user) => {
    if (confirm(`${user.name} kullanıcısını silmek istediğinizden emin misiniz?`)) {
        router.delete(`/admin/users/${user.id}`, {
            onSuccess: () => {
                // Başarı mesajı gösterilebilir
                alert('Kullanıcı başarıyla silindi')
            },
            onError: (errors) => {
                // Hata mesajı gösterilebilir
                alert('Kullanıcı silinirken bir hata oluştu')
                console.error(errors)
            }
        })
    }
}
</script> 