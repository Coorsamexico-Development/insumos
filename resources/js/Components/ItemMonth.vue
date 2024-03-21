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
        class="text-xs px-2 py-1 rounded-lg hover:cursor-pointer text-center"
        :class="{ 'bg-aqua-500 text-white': active }"
    >
        <slot />
    </div>
</template>
<style scoped></style>
