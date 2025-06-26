<template>
    <Transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="opacity-0 translate-y-2 sm:translate-y-0 sm:scale-95"
        enter-to-class="opacity-100 translate-y-0 sm:scale-100"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100 translate-y-0 sm:scale-100"
        leave-to-class="opacity-0 translate-y-2 sm:translate-y-0 sm:scale-95"
    >
        <div v-if="show" :class="[
            'p-4 rounded-md border-l-4',
            variant === 'success' ? 'bg-green-50 border-green-400' :
            variant === 'error' ? 'bg-red-50 border-red-400' :
            variant === 'warning' ? 'bg-yellow-50 border-yellow-400' :
            variant === 'info' ? 'bg-blue-50 border-blue-400' : 'bg-gray-50 border-gray-400'
        ]">
            <div class="flex">
                <div class="flex-shrink-0">
                    <component :is="alertIcon" :class="[
                        'h-5 w-5',
                        variant === 'success' ? 'text-green-400' :
                        variant === 'error' ? 'text-red-400' :
                        variant === 'warning' ? 'text-yellow-400' :
                        variant === 'info' ? 'text-blue-400' : 'text-gray-400'
                    ]" />
                </div>
                <div class="ml-3 flex-1">
                    <h3 v-if="title" :class="[
                        'text-sm font-medium',
                        variant === 'success' ? 'text-green-800' :
                        variant === 'error' ? 'text-red-800' :
                        variant === 'warning' ? 'text-yellow-800' :
                        variant === 'info' ? 'text-blue-800' : 'text-gray-800'
                    ]">
                        {{ title }}
                    </h3>
                    <div :class="[
                        'text-sm',
                        variant === 'success' ? 'text-green-700' :
                        variant === 'error' ? 'text-red-700' :
                        variant === 'warning' ? 'text-yellow-700' :
                        variant === 'info' ? 'text-blue-700' : 'text-gray-700',
                        title ? 'mt-1' : ''
                    ]">
                        <slot>{{ message }}</slot>
                    </div>
                </div>
                <div v-if="dismissible" class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button @click="dismiss" :class="[
                            'inline-flex rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2',
                            variant === 'success' ? 'bg-green-50 text-green-500 hover:bg-green-100 focus:ring-offset-green-50 focus:ring-green-600' :
                            variant === 'error' ? 'bg-red-50 text-red-500 hover:bg-red-100 focus:ring-offset-red-50 focus:ring-red-600' :
                            variant === 'warning' ? 'bg-yellow-50 text-yellow-500 hover:bg-yellow-100 focus:ring-offset-yellow-50 focus:ring-yellow-600' :
                            variant === 'info' ? 'bg-blue-50 text-blue-500 hover:bg-blue-100 focus:ring-offset-blue-50 focus:ring-blue-600' :
                            'bg-gray-50 text-gray-500 hover:bg-gray-100 focus:ring-offset-gray-50 focus:ring-gray-600'
                        ]">
                            <span class="sr-only">Kapat</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const props = defineProps({
    variant: {
        type: String,
        default: 'info',
        validator: (value) => ['success', 'error', 'warning', 'info'].includes(value)
    },
    title: {
        type: String,
        default: null
    },
    message: {
        type: String,
        default: ''
    },
    dismissible: {
        type: Boolean,
        default: true
    },
    autoClose: {
        type: [Boolean, Number],
        default: false
    }
})

const emit = defineEmits(['close'])

const show = ref(true)

const alertIcon = computed(() => {
    const icons = {
        success: {
            template: `<svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>`
        },
        error: {
            template: `<svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>`
        },
        warning: {
            template: `<svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>`
        },
        info: {
            template: `<svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>`
        }
    }
    return icons[props.variant]
})

const dismiss = () => {
    show.value = false
    emit('close')
}

onMounted(() => {
    if (props.autoClose && typeof props.autoClose === 'number') {
        setTimeout(() => {
            dismiss()
        }, props.autoClose * 1000)
    } else if (props.autoClose === true) {
        setTimeout(() => {
            dismiss()
        }, 5000)
    }
})
</script> 