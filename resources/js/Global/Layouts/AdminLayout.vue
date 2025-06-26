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
                'bg-white shadow-lg transition-all duration-300 ease-in-out flex flex-col relative z-50 border-r border-gray-200',
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
            <nav class="flex-1 px-3 py-6 space-y-1">
                <!-- Dashboard -->
                <Link 
                    :href="route('admin.dashboard')" 
                    class="flex items-center px-3 py-3 text-sm font-medium rounded-xl hover:bg-gray-50 text-gray-700 hover:text-gray-900 transition-colors group"
                    :class="{ 'justify-center': sidebarCollapsed && !isMobile }"
                >
                    <svg class="w-5 h-5 text-gray-500 group-hover:text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                    </svg>
                    <span v-if="!sidebarCollapsed || isMobile" class="ml-3">Dashboard</span>
                </Link>

                <!-- Users -->
                <Link 
                    :href="route('admin.users.index')" 
                    class="flex items-center px-3 py-3 text-sm font-medium rounded-xl hover:bg-gray-50 text-gray-700 hover:text-gray-900 transition-colors group"
                    :class="{ 'justify-center': sidebarCollapsed && !isMobile }"
                >
                    <svg class="w-5 h-5 text-gray-500 group-hover:text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                    <span v-if="!sidebarCollapsed || isMobile" class="ml-3">Kullanıcılar</span>
                </Link>

                <!-- Settings -->
                <Link 
                    href="/admin/settings" 
                    class="flex items-center px-3 py-3 text-sm font-medium rounded-xl hover:bg-gray-50 text-gray-700 hover:text-gray-900 transition-colors group"
                    :class="{ 'justify-center': sidebarCollapsed && !isMobile }"
                >
                    <svg class="w-5 h-5 text-gray-500 group-hover:text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                    </svg>
                    <span v-if="!sidebarCollapsed || isMobile" class="ml-3">Ayarlar</span>
                </Link>
            </nav>
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
                    
                    <!-- Breadcrumb -->
                    <Breadcrumb :items="breadcrumbs" />
                </div>

                <!-- Right side -->
                <div class="flex items-center space-x-2">
                    <!-- Notifications Dropdown -->
                    <div class="relative" @click.stop>
                        <button 
                            @click="showNotifications = !showNotifications"
                            class="p-2 rounded-lg hover:bg-gray-100 transition-colors relative"
                        >
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-3.405-3.405A2.032 2.032 0 0118 12V6a6 6 0 10-12 0v6c0 .34-.1.66-.275.95L3 17h5m7 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span class="absolute -top-1 -right-1 h-4 w-4 bg-red-500 rounded-full text-xs text-white flex items-center justify-center">3</span>
                        </button>
                        
                        <!-- Notifications Dropdown -->
                        <div v-if="showNotifications" class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                            <div class="p-4 border-b border-gray-200">
                                <h3 class="text-sm font-semibold text-gray-900">Bildirimler</h3>
                            </div>
                            <div class="max-h-64 overflow-y-auto">
                                <div class="p-3 hover:bg-gray-50 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-900">Yeni kullanıcı kaydı</p>
                                    <p class="text-xs text-gray-500">ahmet@example.com - 5 dakika önce</p>
                                </div>
                                <div class="p-3 hover:bg-gray-50 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-900">Sistem güncellemesi</p>
                                    <p class="text-xs text-gray-500">v2.1.0 başarıyla yüklendi - 1 saat önce</p>
                                </div>
                                <div class="p-3 hover:bg-gray-50">
                                    <p class="text-sm font-medium text-gray-900">Güvenlik uyarısı</p>
                                    <p class="text-xs text-gray-500">Şüpheli giriş denemesi - 2 saat önce</p>
                                </div>
                            </div>
                            <div class="p-3 border-t border-gray-200">
                                <button class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                    Tümünü gör
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- User Dropdown -->
                    <div class="relative" @click.stop>
                        <button 
                            @click="showUserMenu = !showUserMenu"
                            class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 transition-colors"
                        >
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                <span class="text-xs font-medium text-white">{{ userInitials }}</span>
                            </div>
                            <div v-if="isReady" class="hidden sm:block text-left">
                                <p class="text-sm font-medium text-gray-900">{{ pageProps.auth.user.name }}</p>
                                <p class="text-xs text-gray-500">{{ pageProps.auth.user.email }}</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        
                        <!-- User Dropdown Menu -->
                        <div v-if="showUserMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                            <div class="py-1">
                                <Link href="/profile" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Profilim
                                </Link>
                                <Link href="/admin/settings" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    Ayarlar
                                </Link>
                                <Link href="/" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                    Siteye Git
                                </Link>
                                <hr class="my-1">
                                <Link 
                                    :href="route('logout')" 
                                    method="post" 
                                    as="button"
                                    class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50"
                                >
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Çıkış Yap
                                </Link>
                            </div>
                        </div>
                    </div>
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
import Breadcrumb from '@/Global/Components/Breadcrumb.vue'

const props = defineProps({
    title: String,
    pageTitle: {
        type: String,
        default: 'Admin Panel'
    },
    breadcrumbs: {
        type: Array,
        default: () => []
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

// Dropdown states
const showNotifications = ref(false)
const showUserMenu = ref(false)

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

// Close dropdowns when clicking outside
const closeDropdowns = () => {
    showNotifications.value = false
    showUserMenu.value = false
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
    document.addEventListener('click', closeDropdowns)
})

onUnmounted(() => {
    window.removeEventListener('resize', checkMobile)
    document.removeEventListener('click', closeDropdowns)
})
</script> 