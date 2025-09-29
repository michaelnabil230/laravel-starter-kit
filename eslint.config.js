import { defineConfigWithVueTs, vueTsConfigs } from '@vue/eslint-config-typescript';
import prettier from 'eslint-config-prettier';
import vue from 'eslint-plugin-vue';

export default defineConfigWithVueTs(
    vue.configs['flat/essential'],
    vueTsConfigs.recommended,
    {
        ignores: ['vendor', 'node_modules', 'public', 'socket-real-time/*'],
    },
    {
        rules: {
            'vue/multi-word-component-names': 'off',
            '@typescript-eslint/no-explicit-any': 'off',
            '@typescript-eslint/no-namespace': 'off',
        },
    },
    prettier,
);
