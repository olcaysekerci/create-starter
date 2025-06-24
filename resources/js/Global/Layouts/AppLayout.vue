<template>
    <div>
        <Head :title="title" />
        
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <Link href="/" class="text-xl font-semibold">
                            Uygulama
                        </Link>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <Link :href="route('user.profile.show')" class="text-gray-700 hover:text-gray-900">
                            Profil
                        </Link>
                        <form @submit.prevent="logout" class="inline">
                            <button type="submit" class="text-gray-700 hover:text-gray-900">
                                Çıkış
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            <slot />
        </main>
    </div>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3'

defineProps({
    title: String
})

const logout = () => {
    router.post(route('logout'))
}
</script> 