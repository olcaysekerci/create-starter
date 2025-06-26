<template>
    <div :class="['form-group', { 'mb-6': !noMargin, 'mb-4': compact }]">
        <!-- Label -->
        <label 
            v-if="label" 
            :for="inputId" 
            :class="[
                'block text-sm font-medium mb-2',
                error ? 'text-red-700 dark:text-red-400' : 'text-gray-700 dark:text-gray-300',
                required ? 'after:content-[\'*\'] after:text-red-500 after:ml-1' : ''
            ]"
        >
            {{ label }}
        </label>
        
        <!-- Help text (before input) -->
        <p v-if="helpText && helpPosition === 'before'" class="text-sm text-gray-600 dark:text-gray-400 mb-2">
            {{ helpText }}
        </p>
        
        <!-- Input slot -->
        <div class="relative">
            <slot :input-id="inputId" :error="error" />
        </div>
        
        <!-- Error message -->
        <p v-if="error" class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
            <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            {{ error }}
        </p>
        
        <!-- Help text (after input) -->
        <p v-if="helpText && helpPosition === 'after'" class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            {{ helpText }}
        </p>
        
        <!-- Success message -->
        <p v-if="success" class="mt-2 text-sm text-green-600 dark:text-green-400 flex items-center">
            <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ success }}
        </p>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    label: {
        type: String,
        default: ''
    },
    error: {
        type: String,
        default: ''
    },
    success: {
        type: String,
        default: ''
    },
    helpText: {
        type: String,
        default: ''
    },
    helpPosition: {
        type: String,
        default: 'after',
        validator: (value) => ['before', 'after'].includes(value)
    },
    required: {
        type: Boolean,
        default: false
    },
    noMargin: {
        type: Boolean,
        default: false
    },
    compact: {
        type: Boolean,
        default: false
    },
    inputId: {
        type: String,
        default: () => `input-${Math.random().toString(36).substr(2, 9)}`
    }
})
</script> 