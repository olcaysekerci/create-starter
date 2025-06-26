<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: String,
    active: Boolean,
});

const classes = computed(() =>
    props.active
        ? 'inline-flex items-center border-b-2 border-indigo-400 px-1 pt-1 text-sm font-medium text-gray-900 focus:border-indigo-700 focus:outline-none'
        : 'inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium text-gray-500 transition duration-150 ease-in-out hover:border-gray-300 hover:text-gray-700 focus:border-gray-300 focus:text-gray-700 focus:outline-none'
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template> 