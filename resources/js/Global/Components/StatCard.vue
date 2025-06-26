<template>
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="flex items-center">
            <div :class="[
                'p-3 rounded-lg',
                iconBgColor
            ]">
                <component :is="icon" :class="['w-6 h-6', iconColor]" />
            </div>
            <div class="ml-4 flex-1">
                <p class="text-sm font-medium text-gray-600">{{ title }}</p>
                <p class="text-2xl font-bold text-gray-900">{{ value }}</p>
                <p v-if="change" :class="[
                    'text-xs mt-1',
                    changeType === 'increase' ? 'text-green-500' : 'text-red-500'
                ]">
                    {{ changeType === 'increase' ? '↗' : '↘' }} {{ change }}
                </p>
            </div>
        </div>
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
        validator: (value) => ['blue', 'green', 'yellow', 'purple', 'red', 'gray'].includes(value)
    },
    change: {
        type: String,
        default: null
    },
    changeType: {
        type: String,
        default: 'increase',
        validator: (value) => ['increase', 'decrease'].includes(value)
    }
})

const iconBgColor = computed(() => {
    const colors = {
        blue: 'bg-blue-100',
        green: 'bg-green-100',
        yellow: 'bg-yellow-100',
        purple: 'bg-purple-100',
        red: 'bg-red-100',
        gray: 'bg-gray-100'
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
        gray: 'text-gray-600'
    }
    return colors[props.color]
})
</script> 