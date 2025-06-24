<template>
    <div>
        <label v-if="label" :for="id" class="block text-sm font-medium text-gray-700">
            {{ label }}
        </label>
        <input
            :id="id"
            :type="type"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            :class="[
                'mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500',
                error ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : ''
            ]"
            :placeholder="placeholder"
        />
        <p v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</p>
    </div>
</template>

<script setup>
defineProps({
    id: String,
    type: {
        type: String,
        default: 'text'
    },
    modelValue: [String, Number],
    label: String,
    placeholder: String,
    error: String
})

defineEmits(['update:modelValue'])
</script> 