<template>
    <AppLayout title="Profil">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <ProfileForm :user="user" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Global/Layouts/AppLayout.vue'
import ProfileForm from '@/Components/User/ProfileForm.vue'

defineProps({
    user: Object
})
</script> 