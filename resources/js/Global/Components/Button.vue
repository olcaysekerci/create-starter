<template>
    <button
        :type="type"
        :class="[
            'inline-flex items-center justify-center border border-transparent rounded-md font-semibold text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150',
            size === 'sm' ? 'px-3 py-1.5 text-xs' : 'px-4 py-2 text-xs',
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
    size: {
        type: String,
        default: 'md'
    },
    disabled: {
        type: Boolean,
        default: false
    }
})
</script> 