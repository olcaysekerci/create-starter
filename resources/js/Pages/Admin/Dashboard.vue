<template>
    <AdminLayout 
        title="Dashboard" 
        page-title="Dashboard"
        :breadcrumbs="[
            { title: 'Dashboard' }
        ]"
    >
        <!-- Welcome Section -->
        <div class="mb-4">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Hoş Geldiniz! 👋</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Admin panel ana sayfasına hoş geldiniz. İşte sistemin genel durumu:</p>
        </div>

        <!-- System Alert -->
        <div class="mb-4">
            <Alert 
                variant="success" 
                title="Sistem Güncellendi"
                message="v2.1.0 başarıyla yüklendi ve tüm özellikler aktif."
                :auto-close="10"
            />
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <StatCard
                title="Toplam Kullanıcı"
                :value="1234"
                color="blue"
                change="+12%"
                change-type="increase"
                :icon="UserIcon"
            />
            
            <StatCard
                title="Aktif Oturumlar"
                :value="348"
                color="green"
                change="+8%"
                change-type="increase"
                :icon="RefreshIcon"
            />
            
            <StatCard
                title="Bekleyen İşlemler"
                :value="23"
                color="yellow"
                change="+3%"
                change-type="increase"
                :icon="WarningIcon"
            />
            
            <StatCard
                title="Sistem Durumu"
                value="Çevrimiçi"
                color="purple"
                change="Son kontrol: 2dk önce"
                change-label="Durum"
                :icon="ServerIcon"
            />
        </div>

        <!-- Quick Actions & Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Hızlı İşlemler</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Sık kullanılan işlemler</p>
                </div>
                <div class="p-6 space-y-4">
                    <Link 
                        :href="route('admin.users.index')"
                        class="flex items-center p-4 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors group"
                    >
                        <div class="p-2 bg-blue-100 rounded-lg group-hover:bg-blue-200">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="font-medium text-gray-900 dark:text-gray-100">Kullanıcı Yönetimi</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Kullanıcıları görüntüle ve düzenle</p>
                        </div>
                    </Link>

                    <button class="flex items-center p-4 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-green-300 dark:hover:border-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors group w-full text-left">
                        <div class="p-2 bg-green-100 rounded-lg group-hover:bg-green-200">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="font-medium text-gray-900 dark:text-gray-100">Yeni İçerik</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">İçerik oluştur ve yayınla</p>
                        </div>
                    </button>

                    <button class="flex items-center p-4 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-purple-300 dark:hover:border-purple-600 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-colors group w-full text-left">
                        <div class="p-2 bg-purple-100 rounded-lg group-hover:bg-purple-200">
                            <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="font-medium text-gray-900 dark:text-gray-100">Sistem Ayarları</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Genel sistem yapılandırması</p>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Son Aktiviteler</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Yakın zamandaki sistem olayları</p>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Yeni kullanıcı kaydı</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">ahmet@example.com - 5 dakika önce</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Sistem güncellemesi</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">v2.1.0 başarıyla yüklendi - 1 saat önce</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Güvenlik uyarısı</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Şüpheli giriş denemesi engellendi - 2 saat önce</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Yedekleme tamamlandı</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Günlük veri yedeği alındı - 3 saat önce</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <button class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-medium">
                            Tüm aktiviteleri gör →
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import AdminLayout from '@/Global/Layouts/AdminLayout.vue'
import StatCard from '@/Global/Components/StatCard.vue'
import Alert from '@/Global/Components/Alert.vue'
import UserIcon from '@/Global/Icons/UserIcon.vue'
import RefreshIcon from '@/Global/Icons/RefreshIcon.vue'
import WarningIcon from '@/Global/Icons/WarningIcon.vue'
import ServerIcon from '@/Global/Icons/ServerIcon.vue'
import { Link } from '@inertiajs/vue3'

// Icon components are imported above
</script> 