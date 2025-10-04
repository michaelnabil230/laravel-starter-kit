<script setup lang="ts">
defineProps<{
    country: string;
    countryName?: string;
}>();
</script>

<template>
    <div class="picker-flag" :class="country.toLowerCase()" />
</template>
