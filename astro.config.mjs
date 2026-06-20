import { defineConfig } from 'astro/config';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  base: '/landing',
  outDir: './public/landing',
  publicDir: './resources/astro/public',
  srcDir: './resources/astro/src',
  trailingSlash: 'never',
  vite: {
    plugins: [tailwindcss()],
  },
});
