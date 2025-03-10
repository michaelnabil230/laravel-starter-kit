<script setup lang="ts">
import { defineProps } from 'vue';

defineProps<{
    country: string;
    countryName?: string;
}>();
</script>

<template>
    <div class="picker-flag" :class="country.toLowerCase()" />
</template>
