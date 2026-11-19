import astro from 'eslint-plugin-astro';
import tseslint from 'typescript-eslint';
import { defineConfig } from 'eslint/config';

export default defineConfig(
  tseslint.configs.recommended,
  astro.configs['flat/recommended'],
);
