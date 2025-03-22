import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  plugins: [vue()],
  test: {
    globals: true, 
    environment: 'jsdom', 
    outputSnapshotDir: 'test-output', 
    silent: false,
    setupFiles: './setupTests.js',
  },
});
