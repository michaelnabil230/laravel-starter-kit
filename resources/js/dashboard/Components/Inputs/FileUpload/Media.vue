<script setup lang="ts">
import { App } from '@/dashboard/types/app';
import MediaPreview from './MediaPreview.vue';

defineProps<{
    medias: App.Models.Media | App.Models.Media[] | null;
    removeFile?: (id: number) => void;
}>();
</script>

<template>
    <div class="mt-4 space-y-2 empty:mt-0">
        <template v-if="Array.isArray(medias)">
            <MediaPreview v-for="media in medias" :key="media.id" :media="media" :removeFile="removeFile" />
        </template>

        <MediaPreview v-else-if="medias" :media="medias" :removeFile="removeFile" />
    </div>
</template>
