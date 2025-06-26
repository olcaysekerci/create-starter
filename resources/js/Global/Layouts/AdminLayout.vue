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
                sidebarCollapsed ? (isMobile ? '-translate-x-full lg:translate-x-0 lg:w-16' : 'w-16') : 'w-56',
                isMobile ? 'fixed h-full' : 'relative'
            ]"
        >
            <!-- Logo Area -->
            <div class="flex items-center justify-center h-16 border-b border-gray-200">
                <div v-if="!sidebarCollapsed || isMobile" class="text-lg font-bold text-gray-800">
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
                    class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg hover:bg-gray-50 text-gray-700 hover:text-gray-900 transition-colors group"
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
                    class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg hover:bg-gray-50 text-gray-700 hover:text-gray-900 transition-colors group"
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
                    class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg hover:bg-gray-50 text-gray-700 hover:text-gray-900 transition-colors group"
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
                <div class="flex items-center space-x-3">
                    <!-- Quick Actions -->
                    <div class="relative" @click.stop>
                        <button 
                            @click="showNotifications = false; showUserMenu = false; showQuickActions = !showQuickActions"
                            class="p-1.5 rounded-md hover:bg-gray-100 transition-colors border border-gray-200"
                        >
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </button>
                        
                        <!-- Quick Actions Dropdown -->
                        <div v-if="showQuickActions" class="absolute right-0 mt-2 w-60 bg-white rounded-xl shadow-lg border border-gray-200 z-50">
                            <div class="p-3 border-b border-gray-200">
                                <h3 class="text-sm font-semibold text-gray-900">Hızlı İşlemler</h3>
                            </div>
                            <div class="p-3">
                                <div class="grid grid-cols-2 gap-2">
                                    <Link 
                                        :href="route('admin.users.index')"
                                        class="flex flex-col items-center p-3 rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-colors group"
                                    >
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 mb-1">
                                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </div>
                                        <span class="text-xs font-medium text-gray-900">Kullanıcılar</span>
                                    </Link>
                                    
                                    <button class="flex flex-col items-center p-3 rounded-lg border border-gray-200 hover:border-green-300 hover:bg-green-50 transition-colors group">
                                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 mb-1">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                            </svg>
                                        </div>
                                        <span class="text-xs font-medium text-gray-900">Yeni Ekle</span>
                                    </button>
                                    
                                    <button class="flex flex-col items-center p-3 rounded-lg border border-gray-200 hover:border-yellow-300 hover:bg-yellow-50 transition-colors group">
                                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center group-hover:bg-yellow-200 mb-1">
                                            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                        </div>
                                        <span class="text-xs font-medium text-gray-900">Raporlar</span>
                                    </button>
                                    
                                    <button class="flex flex-col items-center p-3 rounded-lg border border-gray-200 hover:border-purple-300 hover:bg-purple-50 transition-colors group">
                                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 mb-1">
                                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </div>
                                        <span class="text-xs font-medium text-gray-900">Ayarlar</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notifications -->
                    <div class="relative" @click.stop>
                        <button 
                            @click="showQuickActions = false; showUserMenu = false; showNotifications = !showNotifications"
                            class="relative p-1.5 rounded-md hover:bg-gray-100 transition-colors border border-gray-200"
                        >
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
                            </svg>
                            <span class="absolute -top-0.5 -right-0.5 h-3 w-3 bg-red-500 rounded-full text-xs text-white flex items-center justify-center">3</span>
                        </button>
                        
                        <!-- Notifications Dropdown -->
                        <div v-if="showNotifications" class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-gray-200 z-50">
                            <div class="p-4 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-sm font-semibold text-gray-900">Bildirimler</h3>
                                    <span class="text-xs bg-red-100 text-red-600 px-2 py-1 rounded-full">3 yeni</span>
                                </div>
                            </div>
                            <div class="max-h-64 overflow-y-auto">
                                <div class="p-4 hover:bg-gray-50 border-b border-gray-100 flex items-start space-x-3">
                                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">Yeni kullanıcı kaydı</p>
                                        <p class="text-xs text-gray-500">ahmet@example.com - 5 dakika önce</p>
                                    </div>
                                </div>
                                <div class="p-4 hover:bg-gray-50 border-b border-gray-100 flex items-start space-x-3">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">Sistem güncellemesi</p>
                                        <p class="text-xs text-gray-500">v2.1.0 başarıyla yüklendi - 1 saat önce</p>
                                    </div>
                                </div>
                                <div class="p-4 hover:bg-gray-50 flex items-start space-x-3">
                                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">Güvenlik uyarısı</p>
                                        <p class="text-xs text-gray-500">Şüpheli giriş denemesi - 2 saat önce</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 border-t border-gray-200">
                                <button class="text-sm text-blue-600 hover:text-blue-700 font-medium w-full text-center">
                                    Tümünü görüntüle →
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- User Dropdown -->
                    <div class="relative" @click.stop>
                        <button 
                            @click="showQuickActions = false; showNotifications = false; showUserMenu = !showUserMenu"
                            class="flex items-center space-x-2 p-1.5 rounded-md hover:bg-gray-100 transition-colors border border-gray-200"
                        >
                            <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center">
                                <span class="text-xs font-medium text-gray-600">{{ userInitials }}</span>
                            </div>
                            <div v-if="isReady" class="hidden sm:block text-left">
                                <p class="text-xs font-medium text-gray-900">{{ pageProps.auth.user.name }}</p>
                                <p class="text-xs text-gray-500">Admin</p>
                            </div>
                            <svg class="w-3 h-3 text-gray-400 transition-transform" :class="{ 'rotate-180': showUserMenu }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        
                        <!-- User Dropdown Menu -->
                        <div v-if="showUserMenu" class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-lg border border-gray-200 z-50">
                            <div v-if="isReady" class="p-4 border-b border-gray-100">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                        <span class="text-xs font-medium text-gray-600">{{ userInitials }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ pageProps.auth.user.name }}</p>
                                        <p class="text-xs text-gray-500">{{ pageProps.auth.user.email }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="py-2">
                                <Link href="/profile" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    Profilim
                                </Link>
                                <Link href="/admin/settings" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    Ayarlar
                                </Link>
                                <Link href="/" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </div>
                                    Siteye Git
                                </Link>
                                <hr class="my-2 border-gray-100">
                                <Link 
                                    :href="route('logout')" 
                                    method="post" 
                                    as="button"
                                    class="flex items-center w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors"
                                >
                                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                    </div>
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
const showQuickActions = ref(false)

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

// Close all dropdowns when clicking outside
const closeDropdowns = () => {
    showNotifications.value = false
    showUserMenu.value = false
    showQuickActions.value = false
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