<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: String,
    active: Boolean,
});

const classes = computed(() => {
    return props.active
        ? 'items-center px-1 pt-1 border-r-4 h-16 border-[#70BBF6] text-sm font-medium leading-5 text-white focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out flex flex-row space-around  justify-center'
        : 'items-center px-1 pt-1 border-r-4 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 justify-center transition duration-150 ease-in-out flex flex-row space-around';
});

</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
