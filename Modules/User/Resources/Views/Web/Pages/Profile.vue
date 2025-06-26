<template>
    <AppLayout title="Profil">
        <PageContainer>
            <ProfileForm :user="user" />
        </PageContainer>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Global/Layouts/AppLayout.vue'
import ProfileForm from '../Components/ProfileForm.vue'
import PageContainer from '@/Global/Components/PageContainer.vue'

defineProps({
    user: Object
})
</script> 