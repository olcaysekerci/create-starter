<template>
    <nav class="flex items-center space-x-2 text-sm text-gray-600">
        <!-- Home Icon -->
        <Link href="/admin" class="hover:text-gray-900 transition-colors" title="Ana Sayfa">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
            </svg>
        </Link>
        

        
        <!-- Breadcrumb Items -->
        <template v-for="(crumb, index) in breadcrumbItems" :key="index">
            <!-- Separator -->
            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
            </svg>
            
            <!-- Breadcrumb Link or Text -->
            <Link 
                v-if="crumb.url" 
                :href="crumb.url" 
                class="hover:text-gray-900 transition-colors"
            >
                {{ crumb.title }}
            </Link>
            <span 
                v-else 
                class="text-gray-900 font-medium"
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