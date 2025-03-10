<template>
    <div class="flex flex-row gap-1.5 items-center justify-center">
        <div
            class="inline-block animate-spin size-4 rounded-full border-2 border-current border-t-transparent text-blue-700 dark:text-blue-500"
        />
        <span class="text-sm text-gray-800 dark:text-neutral-200"> {{ __('global.loading') }}... </span>
    </div>
</template>
