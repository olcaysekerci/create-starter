<template>
    <div v-if="show" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Filtreler</h3>
                    <span v-if="activeFilterCount > 0" class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-blue-100 bg-blue-600 rounded-full">
                        {{ activeFilterCount }}
                    </span>
                </div>
                <button 
                    @click="$emit('close')"
                    class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <div class="overflow-hidden">
            <div class="px-6 py-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- User Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kullanıcı</label>
                        <select 
                            :value="filters.user_id" 
                            @input="updateFilter('user_id', $event.target.value)"
                            class="w-full border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md shadow-sm focus:outline-none focus:border-blue-500 text-sm"
                        >
                            <option value="">Tüm Kullanıcılar</option>
                            <option v-for="user in userOptions" :key="user.value" :value="user.value">
                                {{ user.label }}
                            </option>
                        </select>
                    </div>

                    <!-- Event Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Olay Türü</label>
                        <select 
                            :value="filters.event" 
                            @input="updateFilter('event', $event.target.value)"
                            class="w-full border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md shadow-sm focus:outline-none focus:border-blue-500 text-sm"
                        >
                            <option value="">Tüm Olaylar</option>
                            <option v-for="event in eventOptions" :key="event.value" :value="event.value">
                                {{ event.label }}
                            </option>
                        </select>
                    </div>

                    <!-- Model Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Model</label>
                        <select 
                            :value="filters.model_type" 
                            @input="updateFilter('model_type', $event.target.value)"
                            class="w-full border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md shadow-sm focus:outline-none focus:border-blue-500 text-sm"
                        >
                            <option value="">Tüm Modeller</option>
                            <option v-for="model in modelOptions" :key="model.value" :value="model.value">
                                {{ model.label }}
                            </option>
                        </select>
                    </div>

                    <!-- Date From Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Başlangıç Tarihi</label>
                        <input
                            type="date"
                            :value="filters.date_from"
                            @input="updateFilter('date_from', $event.target.value)"
                            class="w-full border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md shadow-sm focus:outline-none focus:border-blue-500 text-sm"
                        >
                    </div>

                    <!-- Date To Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bitiş Tarihi</label>
                        <input
                            type="date"
                            :value="filters.date_to"
                            @input="updateFilter('date_to', $event.target.value)"
                            class="w-full border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md shadow-sm focus:outline-none focus:border-blue-500 text-sm"
                        >
                    </div>

                    <!-- Admin Action Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">İşlem Türü</label>
                        <select 
                            :value="filters.admin_action" 
                            @input="updateFilter('admin_action', $event.target.value)"
                            class="w-full border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md shadow-sm focus:outline-none focus:border-blue-500 text-sm"
                        >
                            <option value="">Tüm İşlemler</option>
                            <option value="1">Admin İşlemleri</option>
                            <option value="0">Kullanıcı İşlemleri</option>
                        </select>
                    </div>

                    <!-- IP Address Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">IP Adresi</label>
                        <input
                            type="text"
                            :value="filters.ip_address"
                            @input="updateFilter('ip_address', $event.target.value)"
                            class="w-full border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md shadow-sm focus:outline-none focus:border-blue-500 text-sm"
                            placeholder="Örn: 192.168.1.1"
                        >
                    </div>

                    <!-- Has Changes Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Değişiklik Durumu</label>
                        <select 
                            :value="filters.has_changes" 
                            @input="updateFilter('has_changes', $event.target.value)"
                            class="w-full border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-md shadow-sm focus:outline-none focus:border-blue-500 text-sm"
                        >
                            <option value="">Tümü</option>
                            <option value="1">Değişiklik Olan</option>
                            <option value="0">Değişiklik Olmayan</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <Button variant="secondary" @click="$emit('clear-filters')" size="sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Temizle
                    </Button>
                    <Button variant="primary" @click="$emit('apply-filters')" size="sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"/>
                        </svg>
                        Filtrele
                    </Button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'
import Button from './Button.vue'

const props = defineProps({
    filters: {
        type: Object,
        required: true
    },
    show: {
        type: Boolean,
        default: false
    },
    userOptions: {
        type: Array,
        default: () => []
    },
    eventOptions: {
        type: Array,
        default: () => []
    },
    modelOptions: {
        type: Array,
        default: () => []
    }
})

const emit = defineEmits(['update-filter', 'apply-filters', 'clear-filters', 'close'])

const activeFilterCount = computed(() => {
    let count = 0
    if (props.filters.user_id) count++
    if (props.filters.event) count++
    if (props.filters.model_type) count++
    if (props.filters.date_from) count++
    if (props.filters.date_to) count++
    if (props.filters.admin_action) count++
    if (props.filters.ip_address) count++
    if (props.filters.has_changes) count++
    return count
})

const updateFilter = (key, value) => {
    emit('update-filter', key, value)
}
</script> 