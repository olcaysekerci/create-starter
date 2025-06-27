<template>
    <teleport to="body">
        <transition
            enter-active-class="ease-out duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="ease-in duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-show="show" class="fixed inset-0 overflow-y-auto z-50" scroll-region>
                <!-- Background overlay -->
                <div v-if="closeable" class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75" @click="$emit('close')"></div>
                <div v-else class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
                
                <!-- Modal content -->
                <transition
                    enter-active-class="ease-out duration-300"
                    enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-active-class="ease-in duration-200"
                    leave-from-class="opacity-100 translate-y-0 sm:scale-100"
                    leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                >
                    <div v-show="show" class="relative min-h-screen flex items-center justify-center p-4 sm:p-6">
                        <div class="relative bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all w-full max-w-lg mx-auto max-h-[90vh] overflow-y-auto">
                            <!-- Close button for mobile -->
                            <button 
                                v-if="closeable"
                                @click="$emit('close')"
                                class="absolute top-4 right-4 z-10 p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors sm:hidden"
                            >
                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                            
                            <slot />
                        </div>
                    </div>
                </transition>
            </div>
        </transition>
    </teleport>
</template>

<script setup>
defineProps({
    show: {
        type: Boolean,
        default: false
    },
    closeable: {
        type: Boolean,
        default: true
    }
})

defineEmits(['close'])
</script> 