<template>
    <div class="overflow-x-auto">
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
</template>

<script setup>
import { computed } from 'vue'

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
    }
})

const emit = defineEmits(['action'])

const hasActions = computed(() => {
    return props.actions.length > 0 || !!useSlots()['actions']
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

const handleAction = (action, item, index) => {
    if (action.handler) {
        action.handler(item, index)
    } else {
        emit('action', action.key, item, index)
    }
}

// Import useSlots for checking if actions slot exists
import { useSlots } from 'vue'
</script> 