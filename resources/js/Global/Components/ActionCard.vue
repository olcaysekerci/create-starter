<template>
    <component 
        :is="href ? 'a' : to ? resolveRouteComponent() : 'button'"
        v-bind="linkProps"
        :class="[
            'flex items-center p-4 rounded-lg border border-gray-200 transition-colors group w-full text-left',
            `hover:border-${color}-300 hover:bg-${color}-50`,
            disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'
        ]"
        @click="handleClick"
    >
        <div :class="[
            'p-2 rounded-lg transition-colors',
            `bg-${color}-100 group-hover:bg-${color}-200`
        ]">
            <component :is="icon" :class="[
                'w-5 h-5',
                `text-${color}-600`
            ]" />
        </div>
        <div class="ml-4 flex-1">
            <p class="font-medium text-gray-900">{{ title }}</p>
            <p v-if="description" class="text-sm text-gray-600">{{ description }}</p>
        </div>
        <div v-if="badge" class="ml-4">
            <span :class="[
                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                badgeColor === 'red' ? 'bg-red-100 text-red-800' :
                badgeColor === 'yellow' ? 'bg-yellow-100 text-yellow-800' :
                badgeColor === 'green' ? 'bg-green-100 text-green-800' :
                badgeColor === 'blue' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'
            ]">
                {{ badge }}
            </span>
        </div>
        <div v-if="arrow" class="ml-4">
            <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </div>
    </component>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    title: {
        type: String,
        required: true
    },
    description: {
        type: String,
        default: null
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
    href: {
        type: String,
        default: null
    },
    to: {
        type: String, 
        default: null
    },
    badge: {
        type: [String, Number],
        default: null
    },
    badgeColor: {
        type: String,
        default: 'gray',
        validator: (value) => ['red', 'yellow', 'green', 'blue', 'gray'].includes(value)
    },
    arrow: {
        type: Boolean,
        default: false
    },
    disabled: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['click'])

const resolveRouteComponent = () => {
    return Link
}

const linkProps = computed(() => {
    if (props.href) {
        return {
            href: props.href,
            target: props.href.startsWith('http') ? '_blank' : undefined,
            rel: props.href.startsWith('http') ? 'noopener noreferrer' : undefined
        }
    }
    
    if (props.to) {
        return {
            href: props.to
        }
    }
    
    return {
        type: 'button',
        disabled: props.disabled
    }
})

const handleClick = (event) => {
    if (props.disabled) {
        event.preventDefault()
        return
    }
    
    if (!props.href && !props.to) {
        emit('click', event)
    }
}
</script> 