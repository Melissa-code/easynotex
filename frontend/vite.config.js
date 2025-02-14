import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vite.dev/config/
export default defineConfig({
  plugins: [vue()],
  server: {
    watch: {
      usePolling: true,
    },
    host: '0.0.0.0',  // Permet d'accéder à Vite depuis un autre appareil
    strictPort: true,
    port: 5173,
  }
})
