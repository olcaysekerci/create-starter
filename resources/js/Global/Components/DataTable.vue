<template>
    <!-- Desktop Table -->
    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th v-for="column in columns" 
                        :key="column.key"
                        scope="col" 
                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        {{ column.title }}
                    </th>
                    <th v-if="hasActions" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                        İşlemler
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-if="!data || data.length === 0">
                    <td :colspan="columns.length + (hasActions ? 1 : 0)" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                        {{ emptyMessage }}
                    </td>
                </tr>
                <tr v-for="(item, index) in data" :key="getRowKey(item, index)" :class="rowClass">
                    <td v-for="column in columns" 
                        :key="column.key"
                        class="px-6 py-4 whitespace-nowrap text-sm"
                        :class="column.cellClass || (column.type === 'primary' ? 'font-medium text-gray-900 dark:text-gray-100' : 'text-gray-500 dark:text-gray-400')">
                        
                        <!-- Custom slot content -->
                        <slot v-if="$slots[`cell-${column.key}`]" 
                              :name="`cell-${column.key}`" 
                              :item="item" 
                              :value="getNestedProperty(item, column.key)" 
                              :index="index" />
                        
                        <!-- Date formatting -->
                        <span v-else-if="column.type === 'date'">
                            {{ formatDate(getNestedProperty(item, column.key)) }}
                        </span>
                        
                        <!-- Badge/Status -->
                        <span v-else-if="column.type === 'badge'" 
                              :class="getBadgeClass(getNestedProperty(item, column.key), column.badgeColors)">
                            {{ getNestedProperty(item, column.key) }}
                        </span>
                        
                        <!-- Default text -->
                        <span v-else>
                            {{ getNestedProperty(item, column.key) }}
                        </span>
                    </td>
                    
                    <!-- Actions column -->
                    <td v-if="hasActions" class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <slot name="actions" :item="item" :index="index">
                            <button v-for="action in actions" 
                                    :key="action.key"
                                    @click="handleAction(action, item, index)"
                                    :class="[
                                        'mr-4 hover:underline',
                                        getActionClass(action.variant)
                                    ]">
                                {{ action.label }}
                            </button>
                        </slot>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards -->
    <div class="md:hidden space-y-4">
        <div v-if="!data || data.length === 0" class="text-center py-8 text-sm text-gray-500 dark:text-gray-400">
            {{ emptyMessage }}
        </div>
        
        <div v-for="(item, index) in data" 
             :key="getRowKey(item, index)"
             class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            
            <!-- Primary content -->
            <div class="flex items-start justify-between mb-3">
                <div class="flex-1 min-w-0">
                    <div v-for="column in primaryColumns" 
                         :key="column.key"
                         class="mb-2 last:mb-0">
                        
                        <div class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                            {{ column.title }}
                        </div>
                        
                        <div class="text-sm"
                             :class="column.cellClass || (column.type === 'primary' ? 'font-medium text-gray-900 dark:text-gray-100' : 'text-gray-500 dark:text-gray-400')">
                            
                            <!-- Custom slot content -->
                            <slot v-if="$slots[`cell-${column.key}`]" 
                                  :name="`cell-${column.key}`" 
                                  :item="item" 
                                  :value="getNestedProperty(item, column.key)" 
                                  :index="index" />
                            
                            <!-- Date formatting -->
                            <span v-else-if="column.type === 'date'">
                                {{ formatDate(getNestedProperty(item, column.key)) }}
                            </span>
                            
                            <!-- Badge/Status -->
                            <span v-else-if="column.type === 'badge'" 
                                  :class="getBadgeClass(getNestedProperty(item, column.key), column.badgeColors)">
                                {{ getNestedProperty(item, column.key) }}
                            </span>
                            
                            <!-- Default text -->
                            <span v-else class="truncate block">
                                {{ getNestedProperty(item, column.key) }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Actions for mobile -->
                <div v-if="hasActions" class="ml-4 flex-shrink-0">
                    <slot name="actions" :item="item" :index="index">
                        <div class="flex flex-col space-y-1">
                            <button v-for="action in actions" 
                                    :key="action.key"
                                    @click="handleAction(action, item, index)"
                                    :class="[
                                        'text-xs px-2 py-1 rounded border',
                                        getMobileActionClass(action.variant)
                                    ]">
                                {{ action.label }}
                            </button>
                        </div>
                    </slot>
                </div>
            </div>
            
            <!-- Secondary content (collapsible) -->
            <div v-if="secondaryColumns.length > 0" class="border-t border-gray-100 dark:border-gray-700 pt-3">
                <button @click="toggleCard(index)" 
                        class="flex items-center justify-between w-full text-xs text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                    <span>Detaylar</span>
                    <svg :class="['w-4 h-4 transition-transform', expandedCards.includes(index) ? 'rotate-180' : '']" 
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                
                <div v-if="expandedCards.includes(index)" class="mt-3 space-y-2">
                    <div v-for="column in secondaryColumns" 
                         :key="column.key"
                         class="flex justify-between items-center">
                        
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                            {{ column.title }}
                        </span>
                        
                        <div class="text-sm text-gray-900 dark:text-gray-100 text-right"
                             :class="column.cellClass">
                            
                            <!-- Custom slot content -->
                            <slot v-if="$slots[`cell-${column.key}`]" 
                                  :name="`cell-${column.key}`" 
                                  :item="item" 
                                  :value="getNestedProperty(item, column.key)" 
                                  :index="index" />
                            
                            <!-- Date formatting -->
                            <span v-else-if="column.type === 'date'">
                                {{ formatDate(getNestedProperty(item, column.key)) }}
                            </span>
                            
                            <!-- Badge/Status -->
                            <span v-else-if="column.type === 'badge'" 
                                  :class="getBadgeClass(getNestedProperty(item, column.key), column.badgeColors)">
                                {{ getNestedProperty(item, column.key) }}
                            </span>
                            
                            <!-- Default text -->
                            <span v-else class="truncate max-w-[120px] block">
                                {{ getNestedProperty(item, column.key) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, ref } from 'vue'

const props = defineProps({
    data: {
        type: Array,
        default: () => []
    },
    columns: {
        type: Array,
        required: true
    },
    actions: {
        type: Array,
        default: () => []
    },
    rowKey: {
        type: [String, Function],
        default: 'id'
    },
    rowClass: {
        type: String,
        default: ''
    },
    emptyMessage: {
        type: String,
        default: 'Veri bulunamadı'
    },
    mobilePrimaryColumns: {
        type: Array,
        default: () => []
    }
})

const emit = defineEmits(['action'])

// Mobile card expansion state
const expandedCards = ref([])

const hasActions = computed(() => {
    return props.actions.length > 0 || !!useSlots()['actions']
})

// Split columns for mobile view
const primaryColumns = computed(() => {
    if (props.mobilePrimaryColumns.length > 0) {
        return props.columns.filter(col => props.mobilePrimaryColumns.includes(col.key))
    }
    // Default: first 2 columns as primary
    return props.columns.slice(0, 2)
})

const secondaryColumns = computed(() => {
    if (props.mobilePrimaryColumns.length > 0) {
        return props.columns.filter(col => !props.mobilePrimaryColumns.includes(col.key))
    }
    // Default: remaining columns as secondary
    return props.columns.slice(2)
})

const getRowKey = (item, index) => {
    if (typeof props.rowKey === 'function') {
        return props.rowKey(item, index)
    }
    return getNestedProperty(item, props.rowKey) || index
}

const getNestedProperty = (obj, path) => {
    return path.split('.').reduce((current, key) => current?.[key], obj)
}

const formatDate = (dateString) => {
    if (!dateString) return '-'
    return new Date(dateString).toLocaleDateString('tr-TR')
}

const getBadgeClass = (value, badgeColors = {}) => {
    const baseClass = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium'
    const colorClass = badgeColors[value] || 'bg-gray-100 text-gray-800'
    return `${baseClass} ${colorClass}`
}

const getActionClass = (variant = 'primary') => {
    const variants = {
        primary: 'text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300',
        danger: 'text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300',
        warning: 'text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-300',
        success: 'text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300'
    }
    return variants[variant] || variants.primary
}

const getMobileActionClass = (variant = 'primary') => {
    const variants = {
        primary: 'text-indigo-600 dark:text-indigo-400 border-indigo-200 dark:border-indigo-700 hover:bg-indigo-50 dark:hover:bg-indigo-900/20',
        danger: 'text-red-600 dark:text-red-400 border-red-200 dark:border-red-700 hover:bg-red-50 dark:hover:bg-red-900/20',
        warning: 'text-yellow-600 dark:text-yellow-400 border-yellow-200 dark:border-yellow-700 hover:bg-yellow-50 dark:hover:bg-yellow-900/20',
        success: 'text-green-600 dark:text-green-400 border-green-200 dark:border-green-700 hover:bg-green-50 dark:hover:bg-green-900/20'
    }
    return variants[variant] || variants.primary
}

const handleAction = (action, item, index) => {
    if (action.handler) {
        action.handler(item, index)
    } else {
        emit('action', action.key, item, index)
    }
}

const toggleCard = (index) => {
    const cardIndex = expandedCards.value.indexOf(index)
    if (cardIndex > -1) {
        expandedCards.value.splice(cardIndex, 1)
    } else {
        expandedCards.value.push(index)
    }
}

// Import useSlots for checking if actions slot exists
import { useSlots } from 'vue'
</script> 