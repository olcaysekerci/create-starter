<template>
    <div class="flex h-screen bg-gray-50">
        <Head :title="title" />
        
        <!-- Mobile overlay -->
        <div 
            v-if="sidebarCollapsed && isMobile"
            class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
            @click="closeSidebar"
        ></div>
        
        <!-- Sidebar -->
        <div 
            :class="[
                'bg-white shadow-lg transition-all duration-300 ease-in-out flex flex-col relative z-50',
                sidebarCollapsed ? (isMobile ? '-translate-x-full lg:translate-x-0 lg:w-16' : 'w-16') : 'w-64',
                isMobile ? 'fixed h-full' : 'relative'
            ]"
        >
            <!-- Logo Area -->
            <div class="flex items-center justify-center h-16 border-b border-gray-200">
                <div v-if="!sidebarCollapsed || isMobile" class="text-xl font-bold text-gray-800">
                    Admin Panel
                </div>
                <div v-else class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                    </svg>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 px-3 py-4 space-y-2">
                <!-- Dashboard -->
                <Link 
                    :href="route('admin.dashboard')" 
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-lg hover:bg-gray-100 text-gray-700 hover:text-gray-900 transition-colors"
                    :class="{ 'justify-center': sidebarCollapsed && !isMobile }"
                >
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                    </svg>
                    <span v-if="!sidebarCollapsed || isMobile" class="ml-3">Dashboard</span>
                </Link>

                <!-- Users -->
                <Link 
                    :href="route('admin.users.index')" 
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-lg hover:bg-gray-100 text-gray-700 hover:text-gray-900 transition-colors"
                    :class="{ 'justify-center': sidebarCollapsed && !isMobile }"
                >
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                    <span v-if="!sidebarCollapsed || isMobile" class="ml-3">Kullanıcılar</span>
                </Link>

                <!-- Settings -->
                <Link 
                    href="/admin/settings" 
                    class="flex items-center px-3 py-2 text-sm font-medium rounded-lg hover:bg-gray-100 text-gray-700 hover:text-gray-900 transition-colors"
                    :class="{ 'justify-center': sidebarCollapsed && !isMobile }"
                >
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                    </svg>
                    <span v-if="!sidebarCollapsed || isMobile" class="ml-3">Ayarlar</span>
                </Link>
            </nav>

            <!-- User Profile Section -->
            <div class="border-t border-gray-200 p-3">
                <div class="flex items-center" :class="{ 'justify-center': sidebarCollapsed && !isMobile }">
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                        <span class="text-xs font-medium text-white">{{ userInitials }}</span>
                    </div>
                    <div v-if="(!sidebarCollapsed || isMobile) && isReady && pageProps.auth.user" class="ml-3 flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ pageProps.auth.user.name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ pageProps.auth.user.email }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Navbar -->
            <header class="bg-white shadow-sm border-b border-gray-200 h-16 flex items-center justify-between px-4 lg:px-6">
                <!-- Left side -->
                <div class="flex items-center space-x-4">
                    <!-- Toggle Button -->
                    <button
                        @click="toggleSidebar"
                        class="p-2 rounded-lg hover:bg-gray-100 transition-colors"
                    >
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    
                    <!-- Page Title -->
                    <h1 class="text-lg font-semibold text-gray-900 hidden sm:block">{{ pageTitle }}</h1>
                </div>

                <!-- Right side -->
                <div class="flex items-center space-x-2 lg:space-x-4">
                    <!-- Notifications -->
                    <button class="p-2 rounded-lg hover:bg-gray-100 transition-colors relative">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-3.405-3.405A2.032 2.032 0 0118 12V6a6 6 0 10-12 0v6c0 .34-.1.66-.275.95L3 17h5m7 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute -top-1 -right-1 h-4 w-4 bg-red-500 rounded-full text-xs text-white flex items-center justify-center">3</span>
                    </button>

                    <!-- Site Link -->
                    <Link 
                        href="/" 
                        class="text-sm text-gray-600 hover:text-gray-900 transition-colors hidden sm:block"
                    >
                        Siteye Dön
                    </Link>

                    <!-- Logout -->
                    <Link 
                        :href="route('logout')" 
                        method="post" 
                        as="button"
                        class="text-sm text-red-600 hover:text-red-700 transition-colors"
                    >
                        <span class="hidden sm:inline">Çıkış</span>
                        <svg class="w-5 h-5 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </Link>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-auto">
                <div class="p-4 lg:p-6">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3'
import { ref, computed, onMounted, onUnmounted } from 'vue'

const props = defineProps({
    title: String,
    pageTitle: {
        type: String,
        default: 'Admin Panel'
    }
})

// Get page data
const { props: pageProps } = usePage()

// Check if page is ready
const isReady = computed(() => {
    return pageProps.value && pageProps.value.auth
})

// Sidebar state
const sidebarCollapsed = ref(false)
const isMobile = ref(false)

// Check if mobile
const checkMobile = () => {
    isMobile.value = window.innerWidth < 1024
    if (isMobile.value && !sidebarCollapsed.value) {
        sidebarCollapsed.value = true
    }
}

// Toggle sidebar
const toggleSidebar = () => {
    sidebarCollapsed.value = !sidebarCollapsed.value
}

// Close sidebar (for mobile overlay)
const closeSidebar = () => {
    if (isMobile.value) {
        sidebarCollapsed.value = true
    }
}

// User initials for avatar
const userInitials = computed(() => {
    if (!isReady.value) return 'A'
    
    const user = pageProps.value?.auth?.user
    if (!user?.name) return 'A'
    
    return user.name
        .split(' ')
        .map(word => word.charAt(0))
        .join('')
        .toUpperCase()
        .slice(0, 2)
})

// Lifecycle
onMounted(() => {
    checkMobile()
    window.addEventListener('resize', checkMobile)
})

onUnmounted(() => {
    window.removeEventListener('resize', checkMobile)
})
</script> 