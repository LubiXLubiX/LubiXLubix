import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import path from 'node:path'

export default defineConfig({
  plugins: [react()],
  root: path.resolve(__dirname, 'resources/app'),
  base: '/',
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'resources/app/src')
    },
    extensions: ['.js', '.jsx', '.json', '.lubix.jsx']
  },
  server: {
    port: 5173,
    strictPort: true,
    host: '127.0.0.1',
    origin: 'http://localhost:3000',
    hmr: {
      protocol: 'ws',
      host: 'localhost',
      port: 3000,
    },
    cors: true
  }
})
