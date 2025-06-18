import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  server: {
    host: '0.0.0.0',    // **ważne** — bindowanie na wszystkie interfejsy
    port: 5173,
    hmr: {
      host: 'localhost', // aby HMR wsadzał się do właściwego adresu
    },
  },
  plugins: [
    laravel({
      input: ['resources/css/app.css','resources/js/app.js'],
      refresh: true,
    }),
  ],
});
