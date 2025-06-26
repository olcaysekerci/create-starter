<template>
    <div :class="[
        'py-12', 
        noPadding ? '' : 'py-12'
    ]">
        <div :class="[
            'mx-auto sm:px-6 lg:px-8',
            maxWidth === 'sm' ? 'max-w-3xl' : 
            maxWidth === 'md' ? 'max-w-5xl' : 
            maxWidth === 'lg' ? 'max-w-7xl' : 
            maxWidth === 'xl' ? 'max-w-8xl' : 
            maxWidth === 'full' ? 'max-w-none' : 'max-w-7xl'
        ]">
            <div v-if="card" :class="[
                'bg-white overflow-hidden shadow-xl sm:rounded-lg',
                cardPadding ? 'p-6' : ''
            ]">
                <slot />
            </div>
            <div v-else>
                <slot />
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    maxWidth: {
        type: String,
        default: '7xl',
        validator: (value) => ['sm', 'md', 'lg', 'xl', 'full', '7xl'].includes(value)
    },
    card: {
        type: Boolean,
        default: true
    },
    cardPadding: {
        type: Boolean,
        default: true
    },
    noPadding: {
        type: Boolean,
        default: false
    }
})
</script> 