<template>
    <Modal :show="show" @close="handleClose" :closeable="!processing">
        <div class="p-6">
            <!-- Header -->
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0 w-10 h-10 mx-auto flex items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <div class="ml-4 text-left">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ title || 'Emin misiniz?' }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ message || 'Bu işlem geri alınamaz.' }}
                    </p>
                </div>
            </div>

            <!-- Content -->
            <div v-if="$slots.default" class="mb-6">
                <slot />
            </div>

            <!-- Actions -->
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3 space-y-3 space-y-reverse sm:space-y-0">
                <Button 
                    @click="handleClose"
                    variant="secondary" 
                    size="sm"
                    :disabled="processing"
                    class="w-full sm:w-auto"
                >
                    {{ cancelText || 'İptal' }}
                </Button>
                <Button 
                    @click="handleConfirm"
                    variant="danger" 
                    size="sm"
                    :disabled="processing"
                    class="w-full sm:w-auto"
                >
                    <svg v-if="processing" class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <svg v-else class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1H8a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    {{ processing ? 'Siliniyor...' : (confirmText || 'Sil') }}
                </Button>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import { ref } from 'vue'
import Modal from './Modal.vue'
import Button from './Button.vue'

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    title: {
        type: String,
        default: ''
    },
    message: {
        type: String,
        default: ''
    },
    confirmText: {
        type: String,
        default: ''
    },
    cancelText: {
        type: String,
        default: ''
    },
    processing: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['close', 'confirm'])

const handleClose = () => {
    if (!props.processing) {
        emit('close')
    }
}

const handleConfirm = () => {
    emit('confirm')
}
</script> 