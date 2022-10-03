import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
  resolve: {
    alias: {
      // '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
      // '~bootstrap-icons': path.resolve(__dirname, 'node_modules/bootstrap-icons'),
      // '~line-awesome': path.resolve(__dirname, 'node_modules/line-awesome')
    }
  },
  plugins: [
    laravel({
      input: [
        'resources/sass/fonts.scss',
        'resources/sass/app.scss',

        'resources/js/app.js',
      ],
      refresh: true,
    }),
  ],
});
