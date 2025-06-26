<template>
    <section>
        <header v-if="title || description || $slots.header">
            <slot name="header">
                <h2 v-if="title" class="text-lg font-medium text-gray-900">
                    {{ title }}
                </h2>
                <p v-if="description" class="mt-1 text-sm text-gray-600">
                    {{ description }}
                </p>
            </slot>
        </header>

        <form v-if="!noForm" @submit.prevent="$emit('submit')" :class="[
            'space-y-6',
            title || description || $slots.header ? 'mt-6' : ''
        ]">
            <slot />
            
            <div v-if="$slots.actions || showActions" class="flex items-center gap-4">
                <slot name="actions">
                    <Button 
                        v-if="submitText"
                        type="submit"
                        :variant="submitVariant"
                        :disabled="isProcessing"
                        :class="{ 'opacity-25': isProcessing }"
                    >
                        {{ submitText }}
                    </Button>
                    
                    <Button
                        v-if="cancelText"
                        type="button"
                        variant="secondary"
                        @click="$emit('cancel')"
                    >
                        {{ cancelText }}
                    </Button>
                </slot>
                
                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="showSuccess" class="text-sm text-green-600">
                        {{ successMessage }}
                    </p>
                </Transition>
            </div>
        </form>
        
        <div v-else :class="[
            title || description || $slots.header ? 'mt-6' : ''
        ]">
            <slot />
        </div>
    </section>
</template>

<script setup>
import Button from './Button.vue'

defineProps({
    title: {
        type: String,
        default: null
    },
    description: {
        type: String,
        default: null
    },
    noForm: {
        type: Boolean,
        default: false
    },
    submitText: {
        type: String,
        default: null
    },
    submitVariant: {
        type: String,
        default: 'primary'
    },
    cancelText: {
        type: String,
        default: null
    },
    isProcessing: {
        type: Boolean,
        default: false
    },
    showSuccess: {
        type: Boolean,
        default: false
    },
    successMessage: {
        type: String,
        default: 'Başarıyla kaydedildi.'
    },
    showActions: {
        type: Boolean,
        default: false
    }
})

defineEmits(['submit', 'cancel'])
</script> 