<script setup>
import { computed } from "@vue/runtime-core";

const props = defineProps({
    month: {
        type: Number,
    },
    currentMonth: {
        type: Number,
    },
});

const active = computed(() => {
    return props.month === props.currentMonth;
});
</script>

<template>
    <div
        class="px-2 py-1 text-xs text-center rounded-lg hover:cursor-pointer"
        :class="{ 'bg-aqua-500 text-gray-400': active }"
    >
        <slot />
    </div>
</template>
<style scoped></style>
