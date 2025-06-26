<template>
    <!-- Success Message -->
    <Transition
        enter-active-class="transition-all duration-300 ease-out"
        enter-from-class="opacity-0 translate-y-2"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition-all duration-300 ease-in"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 translate-y-2"
    >
        <div v-if="showSuccess && successMessage" class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium text-green-800 dark:text-green-400">{{ successMessage }}</span>
                </div>
                <button @click="hideSuccess" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </Transition>

    <!-- Error Message -->
    <Transition
        enter-active-class="transition-all duration-300 ease-out"
        enter-from-class="opacity-0 translate-y-2"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition-all duration-300 ease-in"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 translate-y-2"
    >
        <div v-if="showError && errorMessage" class="mb-6 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium text-red-800 dark:text-red-400">{{ errorMessage }}</span>
                </div>
                <button @click="hideError" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </Transition>

    <!-- Warning Message -->
    <Transition
        enter-active-class="transition-all duration-300 ease-out"
        enter-from-class="opacity-0 translate-y-2"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition-all duration-300 ease-in"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 translate-y-2"
    >
        <div v-if="showWarning && warningMessage" class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 rounded-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium text-yellow-800 dark:text-yellow-400">{{ warningMessage }}</span>
                </div>
                <button @click="hideWarning" class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-800 dark:hover:text-yellow-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </Transition>

    <!-- Info Message -->
    <Transition
        enter-active-class="transition-all duration-300 ease-out"
        enter-from-class="opacity-0 translate-y-2"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition-all duration-300 ease-in"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 translate-y-2"
    >
        <div v-if="showInfo && infoMessage" class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium text-blue-800 dark:text-blue-400">{{ infoMessage }}</span>
                </div>
                <button @click="hideInfo" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </Transition>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'

const props = defineProps({
    autoHide: {
        type: Boolean,
        default: true
    },
    successDuration: {
        type: Number,
        default: 5000
    },
    errorDuration: {
        type: Number,
        default: 8000
    },
    warningDuration: {
        type: Number,
        default: 6000
    },
    infoDuration: {
        type: Number,
        default: 5000
    }
})

// Get page props for flash messages
const { props: pageProps } = usePage()

// Show/hide states
const showSuccess = ref(true)
const showError = ref(true)
const showWarning = ref(true)
const showInfo = ref(true)

// Computed properties for messages
const successMessage = computed(() => pageProps.value?.flash?.success)
const errorMessage = computed(() => pageProps.value?.flash?.error)
const warningMessage = computed(() => pageProps.value?.flash?.warning)
const infoMessage = computed(() => pageProps.value?.flash?.info)

// Manual hide methods
const hideSuccess = () => { showSuccess.value = false }
const hideError = () => { showError.value = false }
const hideWarning = () => { showWarning.value = false }
const hideInfo = () => { showInfo.value = false }

// Auto-hide function
const setupAutoHide = (type, duration) => {
    if (!props.autoHide) return
    
    setTimeout(() => {
        switch (type) {
            case 'success':
                showSuccess.value = false
                break
            case 'error':
                showError.value = false
                break
            case 'warning':
                showWarning.value = false
                break
            case 'info':
                showInfo.value = false
                break
        }
    }, duration)
}

// Initial auto-hide setup
onMounted(() => {
    if (successMessage.value) {
        setupAutoHide('success', props.successDuration)
    }
    if (errorMessage.value) {
        setupAutoHide('error', props.errorDuration)
    }
    if (warningMessage.value) {
        setupAutoHide('warning', props.warningDuration)
    }
    if (infoMessage.value) {
        setupAutoHide('info', props.infoDuration)
    }
})

// Watch for new flash messages
watch(() => pageProps.value?.flash, (newFlash) => {
    if (newFlash?.success) {
        showSuccess.value = true
        setupAutoHide('success', props.successDuration)
    }
    if (newFlash?.error) {
        showError.value = true
        setupAutoHide('error', props.errorDuration)
    }
    if (newFlash?.warning) {
        showWarning.value = true
        setupAutoHide('warning', props.warningDuration)
    }
    if (newFlash?.info) {
        showInfo.value = true
        setupAutoHide('info', props.infoDuration)
    }
}, { deep: true })
</script> 