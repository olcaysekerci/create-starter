<template>
    <teleport to="body">
        <div 
            v-if="show"
            class="fixed top-4 right-4 z-50 max-w-sm w-full"
        >
            <transition
                enter-active-class="transform ease-out duration-300 transition"
                enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
                leave-active-class="transition ease-in duration-100"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div 
                    :class="[
                        'bg-white rounded-lg shadow-lg border-l-4 p-4',
                        typeClasses[type]
                    ]"
                >
                    <div class="flex items-start">
                        <!-- Icon -->
                        <div class="flex-shrink-0">
                            <component :is="iconComponent" :class="iconClasses[type]" />
                        </div>
                        
                        <!-- Content -->
                        <div class="ml-3 w-0 flex-1">
                            <p v-if="title" :class="['text-sm font-medium', textClasses[type]]">
                                {{ title }}
                            </p>
                            <p :class="['text-sm', title ? 'mt-1 text-gray-500' : textClasses[type]]">
                                {{ message }}
                            </p>
                        </div>
                        
                        <!-- Close button -->
                        <div v-if="closable" class="ml-4 flex-shrink-0 flex">
                            <button
                                @click="close"
                                :class="['inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:text-gray-500 transition ease-in-out duration-150']"
                            >
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </teleport>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    type: {
        type: String,
        default: 'info',
        validator: (value) => ['success', 'error', 'warning', 'info'].includes(value)
    },
    title: {
        type: String,
        default: ''
    },
    message: {
        type: String,
        required: true
    },
    closable: {
        type: Boolean,
        default: true
    },
    duration: {
        type: Number,
        default: 5000 // 5 seconds
    }
})

const emit = defineEmits(['close'])

// Auto close timer
let timer = null

onMounted(() => {
    if (props.duration > 0) {
        timer = setTimeout(() => {
            close()
        }, props.duration)
    }
})

const close = () => {
    if (timer) {
        clearTimeout(timer)
    }
    emit('close')
}

// Type-based styling
const typeClasses = {
    success: 'border-green-400',
    error: 'border-red-400',
    warning: 'border-yellow-400',
    info: 'border-blue-400'
}

const iconClasses = {
    success: 'text-green-400',
    error: 'text-red-400',
    warning: 'text-yellow-400',
    info: 'text-blue-400'
}

const textClasses = {
    success: 'text-green-800',
    error: 'text-red-800',
    warning: 'text-yellow-800',
    info: 'text-blue-800'
}

// Icon components
const iconComponent = computed(() => {
    const icons = {
        success: 'CheckCircleIcon',
        error: 'XCircleIcon',
        warning: 'ExclamationTriangleIcon',
        info: 'InformationCircleIcon'
    }
    return icons[props.type]
})

// Icon SVGs as inline components
const CheckCircleIcon = {
    template: `
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
    `
}

const XCircleIcon = {
    template: `
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
    `
}

const ExclamationTriangleIcon = {
    template: `
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
        </svg>
    `
}

const InformationCircleIcon = {
    template: `
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
        </svg>
    `
}
</script> 