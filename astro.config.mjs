import { defineConfig } from 'astro/config';

export default defineConfig({
  site: 'https://madanimontessori.com',
  output: 'static',
  trailingSlash: 'always',
  devToolbar: { enabled: false },
});
