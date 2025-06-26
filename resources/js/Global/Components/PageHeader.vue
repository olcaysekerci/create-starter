<template>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl font-bold text-gray-900">{{ title }}</h1>
                <p v-if="description" class="text-sm text-gray-500 mt-1">{{ description }}</p>
            </div>
            <div v-if="$slots.actions" class="flex items-center space-x-3">
                <slot name="actions" />
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    title: {
        type: String,
        required: true
    },
    description: {
        type: String,
        default: ''
    }
})
</script> 