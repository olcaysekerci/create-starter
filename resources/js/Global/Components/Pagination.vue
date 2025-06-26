<template>
    <div v-if="totalPages > 1" class="flex items-center justify-between bg-white px-4 py-3 sm:px-6 border-t border-gray-200">
        <!-- Mobile view -->
        <div class="flex flex-1 justify-between sm:hidden">
            <button
                @click="goToPage(currentPage - 1)"
                :disabled="currentPage === 1"
                :class="[
                    'relative inline-flex items-center px-4 py-2 text-sm font-medium rounded-md border',
                    currentPage === 1 
                        ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-300' 
                        : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'
                ]"
            >
                Önceki
            </button>
            <button
                @click="goToPage(currentPage + 1)"
                :disabled="currentPage === totalPages"
                :class="[
                    'relative ml-3 inline-flex items-center px-4 py-2 text-sm font-medium rounded-md border',
                    currentPage === totalPages 
                        ? 'bg-gray-100 text-gray-400 cursor-not-allowed border-gray-300' 
                        : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'
                ]"
            >
                Sonraki
            </button>
        </div>

        <!-- Desktop view -->
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <!-- Info text -->
            <div>
                <p class="text-sm text-gray-700">
                    <span class="font-medium">{{ from }}</span>
                    -
                    <span class="font-medium">{{ to }}</span>
                    arası gösteriliyor, toplam
                    <span class="font-medium">{{ total }}</span>
                    kayıt
                </p>
            </div>

            <!-- Pagination buttons -->
            <div>
                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                    <!-- Previous button -->
                    <button
                        @click="goToPage(currentPage - 1)"
                        :disabled="currentPage === 1"
                        :class="[
                            'relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0',
                            currentPage === 1 ? 'cursor-not-allowed' : 'hover:text-gray-500'
                        ]"
                    >
                        <span class="sr-only">Önceki</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd"/>
                        </svg>
                    </button>

                    <!-- Page numbers -->
                    <button
                        v-for="page in visiblePages"
                        :key="page"
                        @click="goToPage(page)"
                        :class="[
                            'relative inline-flex items-center px-4 py-2 text-sm font-semibold ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0',
                            page === currentPage 
                                ? 'z-10 bg-blue-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600' 
                                : 'text-gray-900'
                        ]"
                    >
                        {{ page }}
                    </button>

                    <!-- Next button -->
                    <button
                        @click="goToPage(currentPage + 1)"
                        :disabled="currentPage === totalPages"
                        :class="[
                            'relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0',
                            currentPage === totalPages ? 'cursor-not-allowed' : 'hover:text-gray-500'
                        ]"
                    >
                        <span class="sr-only">Sonraki</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </nav>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    currentPage: {
        type: Number,
        required: true
    },
    totalPages: {
        type: Number,
        required: true
    },
    total: {
        type: Number,
        required: true
    },
    perPage: {
        type: Number,
        default: 10
    },
    maxVisiblePages: {
        type: Number,
        default: 7
    }
})

const emit = defineEmits(['page-changed'])

// Calculate from and to values
const from = computed(() => {
    return (props.currentPage - 1) * props.perPage + 1
})

const to = computed(() => {
    return Math.min(props.currentPage * props.perPage, props.total)
})

// Calculate visible page numbers
const visiblePages = computed(() => {
    const pages = []
    const maxPages = props.maxVisiblePages
    const totalPages = props.totalPages
    const currentPage = props.currentPage

    if (totalPages <= maxPages) {
        // Show all pages if total is less than max
        for (let i = 1; i <= totalPages; i++) {
            pages.push(i)
        }
    } else {
        // Calculate start and end pages
        const half = Math.floor(maxPages / 2)
        let start = Math.max(1, currentPage - half)
        let end = Math.min(totalPages, start + maxPages - 1)

        // Adjust start if end is at the limit
        if (end === totalPages) {
            start = Math.max(1, end - maxPages + 1)
        }

        for (let i = start; i <= end; i++) {
            pages.push(i)
        }
    }

    return pages
})

const goToPage = (page) => {
    if (page >= 1 && page <= props.totalPages && page !== props.currentPage) {
        emit('page-changed', page)
    }
}
</script> 