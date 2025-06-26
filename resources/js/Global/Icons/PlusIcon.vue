<template>
    <svg 
        :class="[
            'inline-block',
            size === 'sm' ? 'w-4 h-4' : size === 'lg' ? 'w-6 h-6' : 'w-5 h-5'
        ]"
        :style="{ color }"
        fill="none" 
        stroke="currentColor" 
        viewBox="0 0 24 24"
    >
        <path 
            stroke-linecap="round" 
            stroke-linejoin="round" 
            stroke-width="2" 
            d="M12 6v6m0 0v6m0-6h6m-6 0H6"
        />
    </svg>
</template>

<script setup>
defineProps({
    size: {
        type: String,
        default: 'md',
        validator: (value) => ['sm', 'md', 'lg'].includes(value)
    },
    color: {
        type: String,
        default: 'currentColor'
    }
})
</script> 