import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css','resources/js/app.js'],
      refresh: true,
      env: {
        REVERB_APP_KEY: process.env.REVERB_APP_KEY,
        REVERB_HOST: process.env.REVERB_HOST,
        REVERB_PORT: process.env.REVERB_PORT,
        REVERB_SCHEME: process.env.REVERB_SCHEME
      }
    }),
  ],
});
