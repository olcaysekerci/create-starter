<template>
    <AdminLayout title="Aktivite Logları">
        <PageHeader title="Aktivite Logları" :breadcrumb="breadcrumb">
            <template #actions>
                <Button 
                    @click="showCleanupModal = true"
                    variant="danger"
                    size="sm"
                >
                    <TrashIcon class="w-4 h-4 mr-2" />
                    Eski Logları Temizle
                </Button>
            </template>
        </PageHeader>

        <!-- İstatistikler -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <StatCard 
                title="Bugün" 
                :value="stats.today" 
                icon="today"
                color="blue"
            />
            <StatCard 
                title="Bu Hafta" 
                :value="stats.this_week" 
                icon="week"
                color="green"
            />
            <StatCard 
                title="Bu Ay" 
                :value="stats.this_month" 
                icon="month"
                color="yellow"
            />
            <StatCard 
                title="Toplam" 
                :value="stats.total" 
                icon="total"
                color="purple"
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

        <!-- Aktivite Tablosu -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <DataTable
                :columns="columns"
                :data="activities.data"
                :loading="loading"
            >
                <template #user="{ row }">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center mr-3">
                            <UserIcon class="w-4 h-4 text-gray-600 dark:text-gray-300" />
                        </div>
                        <div>
                            <div class="font-medium text-gray-900 dark:text-white">
                                {{ row.user_name }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ row.ip_address }}
                            </div>
                        </div>
                    </div>
                </template>

                <template #description="{ row }">
                    <div>
                        <div class="font-medium text-gray-900 dark:text-white">
                            {{ row.description }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ row.model_name }}
                        </div>
                    </div>
                </template>

                <template #event="{ row }">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                          :class="getEventBadgeClass(row.event)">
                        {{ row.formatted_description }}
                    </span>
                </template>

                <template #created_at="{ row }">
                    <div class="text-sm">
                        <div class="text-gray-900 dark:text-white">
                            {{ formatDate(row.created_at) }}
                        </div>
                        <div class="text-gray-500">
                            {{ formatTime(row.created_at) }}
                        </div>
                    </div>
                </template>

                <template #actions="{ row }">
                    <Button
                        @click="viewActivity(row.id)"
                        variant="ghost"
                        size="sm"
                    >
                        <EditIcon class="w-4 h-4" />
                    </Button>
                </template>
            </DataTable>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                <Pagination :data="activities" />
            </div>
        </div>

        <!-- Cleanup Modal -->
        <Modal v-model="showCleanupModal" title="Eski Logları Temizle">
            <form @submit.prevent="cleanupLogs">
                <div class="mb-4">
                    <FormGroup label="Kaç günden eski loglar silinsin?">
                        <TextInput
                            v-model="cleanupForm.days"
                            type="number"
                            min="1"
                            max="365"
                            required
                            placeholder="30"
                        />
                        <p class="text-sm text-gray-500 mt-1">
                            Bu işlem geri alınamaz. Dikkatli olun.
                        </p>
                    </FormGroup>
                </div>

                <div class="flex justify-end space-x-3">
                    <Button @click="showCleanupModal = false" variant="secondary">
                        İptal
                    </Button>
                    <Button type="submit" variant="danger" :loading="cleanupForm.processing">
                        Temizle
                    </Button>
                </div>
            </form>
        </Modal>
    </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { router, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Global/Layouts/AdminLayout.vue'
import PageHeader from '@/Global/Components/PageHeader.vue'
import StatCard from '@/Global/Components/StatCard.vue'
import FilterCard from '@/Global/Components/FilterCard.vue'
import DataTable from '@/Global/Components/DataTable.vue'
import Pagination from '@/Global/Components/Pagination.vue'
import Modal from '@/Global/Components/Modal.vue'
import Button from '@/Global/Components/Button.vue'
import FormGroup from '@/Global/Forms/FormGroup.vue'
import TextInput from '@/Global/Forms/TextInput.vue'
import Select from '@/Global/Forms/Select.vue'
import SearchInput from '@/Global/Components/SearchInput.vue'
import UserIcon from '@/Global/Icons/UserIcon.vue'
import EditIcon from '@/Global/Icons/EditIcon.vue'
import TrashIcon from '@/Global/Icons/TrashIcon.vue'

const props = defineProps({
    activities: Object,
    stats: Object,
    filters: Object,
    current_filters: Object,
})

const loading = ref(false)
const showCleanupModal = ref(false)

const filters = ref({
    user_id: props.current_filters?.user_id || '',
    event: props.current_filters?.event || '',
    model_type: props.current_filters?.model_type || '',
    date_from: props.current_filters?.date_from || '',
    date_to: props.current_filters?.date_to || '',
    search: props.current_filters?.search || '',
})

const cleanupForm = useForm({
    days: 30
})

const breadcrumb = [
    { name: 'Admin', href: route('admin.dashboard') },
    { name: 'Loglar', href: route('admin.logs.index') }
]

const columns = [
    { key: 'user', label: 'Kullanıcı', sortable: false },
    { key: 'description', label: 'Açıklama', sortable: false },
    { key: 'event', label: 'Olay', sortable: false },
    { key: 'created_at', label: 'Tarih', sortable: true },
    { key: 'actions', label: '', sortable: false, width: '80px' },
]

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

const viewActivity = (id) => {
    router.get(route('admin.logs.show', id))
}

const cleanupLogs = () => {
    cleanupForm.post(route('admin.logs.cleanup'), {
        onSuccess: () => {
            showCleanupModal.value = false
            cleanupForm.reset()
        }
    })
}

const getEventBadgeClass = (event) => {
    const classes = {
        'created': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        'updated': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
        'deleted': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
        'login': 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
        'logout': 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
    }
    return classes[event] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300'
}

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('tr-TR')
}

const formatTime = (date) => {
    return new Date(date).toLocaleTimeString('tr-TR')
}
</script> 