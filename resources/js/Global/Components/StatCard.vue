<template>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div :class="[
                    'p-3 rounded-xl shadow-sm',
                    iconBgColor
                ]">
                    <component :is="icon" :class="['w-6 h-6', iconColor]" />
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">{{ title }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ formattedValue }}</p>
                </div>
            </div>
            
            <!-- Trend indicator -->
            <div v-if="change" class="text-right">
                <div :class="[
                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                    changeType === 'increase' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                ]">
                    <svg 
                        :class="[
                            'w-3 h-3 mr-1',
                            changeType === 'increase' ? 'text-green-500' : 'text-red-500'
                        ]" 
                        fill="currentColor" 
                        viewBox="0 0 20 20"
                    >
                        <path 
                            v-if="changeType === 'increase'"
                            fill-rule="evenodd" 
                            d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L11 5.414V17a1 1 0 11-2 0V5.414L4.707 9.707a1 1 0 01-1.414 0z" 
                            clip-rule="evenodd"
                        />
                        <path 
                            v-else
                            fill-rule="evenodd" 
                            d="M16.707 10.293a1 1 0 010 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 111.414-1.414L9 14.586V3a1 1 0 012 0v11.586l4.293-4.293a1 1 0 011.414 0z" 
                            clip-rule="evenodd"
                        />
                    </svg>
                    {{ change }}
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ changeLabel }}</p>
            </div>
        </div>
        
        <!-- Optional description -->
        <p v-if="description" class="text-sm text-gray-500 dark:text-gray-400 mt-3">{{ description }}</p>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    title: {
        type: String,
        required: true
    },
    value: {
        type: [String, Number],
        required: true
    },
    icon: {
        type: [String, Object],
        required: true
    },
    color: {
        type: String,
        default: 'blue',
        validator: (value) => ['blue', 'green', 'yellow', 'purple', 'red', 'gray', 'indigo', 'pink'].includes(value)
    },
    change: {
        type: String,
        default: null
    },
    changeType: {
        type: String,
        default: 'increase',
        validator: (value) => ['increase', 'decrease'].includes(value)
    },
    changeLabel: {
        type: String,
        default: 'Son 30 gün'
    },
    description: {
        type: String,
        default: null
    }
})

const iconBgColor = computed(() => {
    const colors = {
        blue: 'bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800',
        green: 'bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-800',
        yellow: 'bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-100 dark:border-yellow-800',
        purple: 'bg-purple-50 dark:bg-purple-900/20 border border-purple-100 dark:border-purple-800',
        red: 'bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800',
        gray: 'bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-600',
        indigo: 'bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800',
        pink: 'bg-pink-50 dark:bg-pink-900/20 border border-pink-100 dark:border-pink-800'
    }
    return colors[props.color]
})

const iconColor = computed(() => {
    const colors = {
        blue: 'text-blue-600',
        green: 'text-green-600',
        yellow: 'text-yellow-600',
        purple: 'text-purple-600',
        red: 'text-red-600',
        gray: 'text-gray-600',
        indigo: 'text-indigo-600',
        pink: 'text-pink-600'
    }
    return colors[props.color]
})

const formattedValue = computed(() => {
    if (typeof props.value === 'number') {
        return props.value.toLocaleString('tr-TR')
    }
    return props.value
})
</script> 