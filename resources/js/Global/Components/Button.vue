<template>
    <button
        :type="type"
        :class="[
            'inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150',
            variant === 'primary' ? 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500' : '',
            variant === 'secondary' ? 'bg-gray-600 hover:bg-gray-700 focus:ring-gray-500' : '',
            variant === 'danger' ? 'bg-red-600 hover:bg-red-700 focus:ring-red-500' : '',
            {
                'opacity-25': disabled
            }
        ]"
        :disabled="disabled"
    >
        <slot />
    </button>
</template>

<script setup>
defineProps({
    type: {
        type: String,
        default: 'button'
    },
    variant: {
        type: String,
        default: 'primary'
    },
    disabled: {
        type: Boolean,
        default: false
    }
})
</script> 