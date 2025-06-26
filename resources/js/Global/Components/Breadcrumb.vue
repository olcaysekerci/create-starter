<template>
    <nav class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">        
        <!-- Breadcrumb Items -->
        <template v-for="(crumb, index) in breadcrumbItems" :key="index">
            <!-- Separator -->
            <svg v-if="index > 0" class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
            
            <!-- Breadcrumb Link or Text -->
            <Link 
                v-if="crumb.url" 
                :href="crumb.url" 
                class="hover:text-gray-900 dark:hover:text-gray-100 transition-colors"
            >
                {{ crumb.title }}
            </Link>
            <span 
                v-else 
                class="text-gray-900 dark:text-gray-100 font-medium"
            >
                {{ crumb.title }}
            </span>
        </template>
    </nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    items: {
        type: Array,
        default: () => [],
        validator: (items) => {
            // Her item'ın title property'si olmalı
            return items.every(item => item.title)
        }
    }
})

// Reactive breadcrumb items
const breadcrumbItems = computed(() => {
    return props.items
})
</script> 