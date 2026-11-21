import { defineConfig } from 'astro/config';

export default defineConfig({
  site: 'https://madanimontessori.com',
  output: 'static',
  trailingSlash: 'ignore',
  devToolbar: { enabled: false },
});
