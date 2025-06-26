<template>
    <div class="relative">
        <input 
            :type="type"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            :placeholder="placeholder"
            :class="[
                'pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors',
                size === 'sm' ? 'text-sm w-48' : size === 'lg' ? 'text-base w-80' : 'text-sm w-64'
            ]"
        >
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <component :is="icon" class="h-5 w-5 text-gray-400" />
        </div>
        <div v-if="clearable && modelValue" 
             @click="$emit('update:modelValue', '')"
             class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer">
            <svg class="h-4 w-4 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    modelValue: {
        type: [String, Number],
        default: ''
    },
    placeholder: {
        type: String,
        default: 'Ara...'
    },
    type: {
        type: String,
        default: 'text'
    },
    size: {
        type: String,
        default: 'md',
        validator: (value) => ['sm', 'md', 'lg'].includes(value)
    },
    icon: {
        type: [String, Object],
        default: () => ({
            template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>`
        })
    },
    clearable: {
        type: Boolean,
        default: false
    }
})

defineEmits(['update:modelValue'])
</script> 