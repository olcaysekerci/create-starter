<template>
    <AdminLayout 
        title="Aktivite Logları"
        page-title="Aktivite Logları"
        :breadcrumbs="[
            { title: 'Aktivite Logları' }
        ]"
    >
        <!-- Page Header -->
        <PageHeader 
            title="Aktivite Logları"
            description="Sistem ve kullanıcı aktivitelerini görüntüleyin, filtreleyin ve yönetin."
        >
        </PageHeader>

        <!-- İstatistikler -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-6">
            <StatItem
                title="Bugün"
                :value="stats.today"
                color="blue"
                :icon="RefreshIcon"
            />
            
            <StatItem
                title="Bu Hafta"
                :value="stats.this_week"
                color="emerald"
                :icon="CheckIcon"
            />
            
            <StatItem
                title="Bu Ay"
                :value="stats.this_month"
                color="orange"
                :icon="WarningIcon"
            />
            
            <StatItem
                title="Toplam"
                :value="stats.total"
                color="purple"
                :icon="ServerIcon"
            />
        </div>

        <!-- Filtreler -->
        <FilterCard>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <FormGroup label="Kullanıcı">
                    <Select
                        v-model="filters.user_id"
                        :options="userOptions"
                        placeholder="Kullanıcı seçin"
                        @change="applyFilters"
                    />
                </FormGroup>

                <FormGroup label="Olay Türü">
                    <Select
                        v-model="filters.event"
                        :options="eventOptions"
                        placeholder="Olay türü seçin"
                        @change="applyFilters"
                    />
                </FormGroup>

                <FormGroup label="Model Türü">
                    <Select
                        v-model="filters.model_type"
                        :options="modelOptions"
                        placeholder="Model türü seçin"
                        @change="applyFilters"
                    />
                </FormGroup>

                <FormGroup label="Başlangıç Tarihi">
                    <TextInput
                        v-model="filters.date_from"
                        type="date"
                        @change="applyFilters"
                    />
                </FormGroup>

                <FormGroup label="Bitiş Tarihi">
                    <TextInput
                        v-model="filters.date_to"
                        type="date"
                        @change="applyFilters"
                    />
                </FormGroup>

                <FormGroup label="Arama">
                    <SearchInput
                        v-model="filters.search"
                        placeholder="Açıklama veya kullanıcı ara..."
                        @search="applyFilters"
                    />
                </FormGroup>
            </div>

            <template #actions>
                <Button @click="clearFilters" variant="secondary" size="sm">
                    Filtreleri Temizle
                </Button>
            </template>
        </FilterCard>

        <!-- Flash Messages -->
        <FlashMessage />

        <!-- Aktivite Tablosu -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Aktivite Listesi</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                ID
                            </th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Kullanıcı
                            </th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                İşlem
                            </th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Kaynak
                            </th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                IP
                            </th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Tarih
                            </th>
                            <th scope="col" class="relative px-4 py-2">
                                <span class="sr-only">İşlemler</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="activity in activities.data" :key="activity.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-2 whitespace-nowrap text-sm">
                                <div class="text-gray-500 dark:text-gray-400 font-mono text-xs">
                                    #{{ activity.id }}
                                </div>
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm">
                                <div class="font-medium text-gray-900 dark:text-white">
                                    {{ activity.user_name }}
                                </div>
                            </td>
                            <td class="px-4 py-2 text-sm">
                                <div class="flex items-center space-x-2">
                                    <span v-if="activity.event" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium"
                                          :class="getEventBadgeClass(activity.event)">
                                        {{ activity.formatted_description }}
                                    </span>
                                </div>
                                <div class="text-gray-900 dark:text-white mt-1">
                                    {{ activity.description }}
                                </div>
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm">
                                <div v-if="activity.subject_type" class="text-gray-900 dark:text-white">
                                    {{ activity.model_name }}
                                </div>
                                <div v-if="activity.subject_id" class="text-xs text-gray-500 dark:text-gray-400">
                                    ID: {{ activity.subject_id }}
                                </div>
                                <div v-if="!activity.subject_type" class="text-gray-400 dark:text-gray-500 text-xs italic">
                                    Sistem
                                </div>
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm">
                                <div class="text-gray-600 dark:text-gray-400 font-mono text-xs">
                                    {{ activity.ip_address || '-' }}
                                </div>
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm">
                                <div class="text-gray-900 dark:text-white">
                                    {{ formatDate(activity.created_at) }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ formatTime(activity.created_at) }}
                                </div>
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-right text-sm">
                                <Button
                                    @click="viewActivity(activity)"
                                    variant="ghost"
                                    size="sm"
                                    class="p-1"
                                    title="Detayları Görüntüle"
                                >
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </Button>
                            </td>
                        </tr>
                        
                        <tr v-if="activities.data.length === 0">
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center">
                                    <svg class="w-8 h-8 text-gray-300 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="font-medium">Aktivite bulunamadı</p>
                                    <p class="text-sm">Filtreleri değiştirmeyi deneyin.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                <Pagination :data="activities" />
            </div>
        </div>

        <!-- Activity Detail Modal -->
        <Modal v-model="showDetailModal" title="Aktivite Detayları" size="lg">
            <div v-if="selectedActivity" class="space-y-4">
                <!-- Temel Bilgiler -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">ID</label>
                        <p class="text-sm text-gray-900 dark:text-white font-mono">#{{ selectedActivity.id }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tarih</label>
                        <p class="text-sm text-gray-900 dark:text-white">
                            {{ formatDate(selectedActivity.created_at) }} {{ formatTime(selectedActivity.created_at) }}
                        </p>
                    </div>
                </div>

                <!-- Kullanıcı ve İşlem -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kullanıcı</label>
                        <p class="text-sm text-gray-900 dark:text-white">{{ selectedActivity.user_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">İşlem</label>
                        <div class="flex items-center space-x-2">
                            <span v-if="selectedActivity.event" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium"
                                  :class="getEventBadgeClass(selectedActivity.event)">
                                {{ selectedActivity.formatted_description }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Açıklama -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Açıklama</label>
                    <p class="text-sm text-gray-900 dark:text-white">{{ selectedActivity.description }}</p>
                </div>

                <!-- Kaynak -->
                <div v-if="selectedActivity.subject_type" class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kaynak Türü</label>
                        <p class="text-sm text-gray-900 dark:text-white">{{ selectedActivity.model_name }}</p>
                    </div>
                    <div v-if="selectedActivity.subject_id">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kaynak ID</label>
                        <p class="text-sm text-gray-900 dark:text-white font-mono">{{ selectedActivity.subject_id }}</p>
                    </div>
                </div>

                <!-- Teknik Bilgiler -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">IP Adresi</label>
                        <p class="text-sm text-gray-900 dark:text-white font-mono">{{ selectedActivity.ip_address || '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">User Agent</label>
                        <p class="text-xs text-gray-600 dark:text-gray-400 break-all">{{ selectedActivity.user_agent || '-' }}</p>
                    </div>
                </div>

                <!-- Properties (Eğer varsa) -->
                <div v-if="selectedActivity.properties && Object.keys(selectedActivity.properties).length > 0">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ek Bilgiler</label>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                        <pre class="text-xs text-gray-900 dark:text-white whitespace-pre-wrap">{{ JSON.stringify(selectedActivity.properties, null, 2) }}</pre>
                    </div>
                </div>
            </div>
        </Modal>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Global/Layouts/AdminLayout.vue'
import PageHeader from '@/Global/Components/PageHeader.vue'
import StatItem from '@/Global/Components/StatItem.vue'
import FilterCard from '@/Global/Components/FilterCard.vue'
import FormGroup from '@/Global/Forms/FormGroup.vue'
import Select from '@/Global/Forms/Select.vue'
import TextInput from '@/Global/Forms/TextInput.vue'
import SearchInput from '@/Global/Components/SearchInput.vue'
import Button from '@/Global/Components/Button.vue'
import Modal from '@/Global/Components/Modal.vue'
import Pagination from '@/Global/Components/Pagination.vue'
import FlashMessage from '@/Global/Components/FlashMessage.vue'
import UserIcon from '@/Global/Icons/UserIcon.vue'
import RefreshIcon from '@/Global/Icons/RefreshIcon.vue'
import CheckIcon from '@/Global/Icons/CheckIcon.vue'
import WarningIcon from '@/Global/Icons/WarningIcon.vue'
import ServerIcon from '@/Global/Icons/ServerIcon.vue'

const props = defineProps({
    activities: Object,
    stats: Object,
    filters: Object,
    current_filters: Object,
})

const loading = ref(false)
const showDetailModal = ref(false)
const selectedActivity = ref(null)

const filters = ref({
    user_id: props.current_filters?.user_id || '',
    event: props.current_filters?.event || '',
    model_type: props.current_filters?.model_type || '',
    date_from: props.current_filters?.date_from || '',
    date_to: props.current_filters?.date_to || '',
    search: props.current_filters?.search || '',
})

const userOptions = computed(() => [
    { value: '', label: 'Tüm Kullanıcılar' },
    ...props.filters.users.map(user => ({
        value: user.id,
        label: user.name
    }))
])

const eventOptions = computed(() => [
    { value: '', label: 'Tüm Olaylar' },
    ...props.filters.event_types.map(event => ({
        value: event,
        label: event
    }))
])

const modelOptions = computed(() => [
    { value: '', label: 'Tüm Modeller' },
    ...props.filters.model_types.map(model => ({
        value: model.value,
        label: model.label
    }))
])

const applyFilters = () => {
    loading.value = true
    router.get(route('admin.logs.index'), filters.value, {
        preserveState: true,
        onFinish: () => loading.value = false
    })
}

const clearFilters = () => {
    filters.value = {
        user_id: '',
        event: '',
        model_type: '',
        date_from: '',
        date_to: '',
        search: '',
    }
    applyFilters()
}

const viewActivity = (activity) => {
    selectedActivity.value = activity
    showDetailModal.value = true
}

const getEventBadgeClass = (event) => {
    const classes = {
        'created': 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
        'updated': 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400',
        'deleted': 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400',
        'login': 'bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-400',
        'logout': 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
        'profile_updated': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400',
        'password_changed': 'bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-400'
    }
    return classes[event] || 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
}

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('tr-TR')
}

const formatTime = (dateString) => {
    return new Date(dateString).toLocaleTimeString('tr-TR', { 
        hour: '2-digit', 
        minute: '2-digit' 
    })
}
</script> 