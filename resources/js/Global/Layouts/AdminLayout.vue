<template>
    <div>
        <Head :title="title" />
        
        <!-- Admin Navigation -->
        <nav class="bg-gray-800 border-b border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <Link href="/admin" class="text-xl font-semibold text-white">
                            Admin Panel
                        </Link>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <Link :href="route('admin.users.index')" class="text-gray-300 hover:text-white">
                            Kullanıcılar
                        </Link>
                        <Link href="/" class="text-gray-300 hover:text-white">
                            Siteye Dön
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="bg-gray-100 min-h-screen">
            <slot />
        </main>
    </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'

defineProps({
    title: String
})
</script> 