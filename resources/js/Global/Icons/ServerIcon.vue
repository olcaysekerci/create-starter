<template>
    <svg 
        :class="[`w-${size} h-${size}`, color]" 
        fill="currentColor" 
        viewBox="0 0 20 20"
    >
        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
    </svg>
</template>

<script setup>
defineProps({
    size: {
        type: String,
        default: '6'
    },
    color: {
        type: String,
        default: 'text-current'
    }
})
</script> 