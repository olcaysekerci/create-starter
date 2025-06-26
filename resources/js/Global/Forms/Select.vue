<template>
    <div class="relative">
        <select
            :id="id"
            :value="modelValue"
            :disabled="disabled"
            :required="required"
            :class="[
                'block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white',
                disabled ? 'bg-gray-100 cursor-not-allowed' : '',
                error ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : '',
                size === 'sm' ? 'px-2 py-1 text-sm' : size === 'lg' ? 'px-4 py-3 text-lg' : ''
            ]"
            @change="$emit('update:modelValue', $event.target.value)"
            @blur="$emit('blur', $event)"
            @focus="$emit('focus', $event)"
        >
            <option v-if="placeholder" value="" disabled>{{ placeholder }}</option>
            <option
                v-for="option in normalizedOptions"
                :key="option.value"
                :value="option.value"
                :disabled="option.disabled"
            >
                {{ option.label }}
            </option>
        </select>
        
        <!-- Custom arrow -->
        <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    modelValue: {
        type: [String, Number, Boolean],
        default: ''
    },
    options: {
        type: Array,
        required: true
    },
    placeholder: {
        type: String,
        default: ''
    },
    disabled: {
        type: Boolean,
        default: false
    },
    required: {
        type: Boolean,
        default: false
    },
    error: {
        type: Boolean,
        default: false
    },
    size: {
        type: String,
        default: 'md',
        validator: (value) => ['sm', 'md', 'lg'].includes(value)
    },
    id: {
        type: String,
        default: () => `select-${Math.random().toString(36).substr(2, 9)}`
    }
})

defineEmits(['update:modelValue', 'blur', 'focus'])

// Normalize options to always have { value, label, disabled } format
const normalizedOptions = computed(() => {
    return props.options.map(option => {
        if (typeof option === 'string' || typeof option === 'number') {
            return { value: option, label: option, disabled: false }
        }
        return {
            value: option.value,
            label: option.label || option.value,
            disabled: option.disabled || false
        }
    })
})
</script> 