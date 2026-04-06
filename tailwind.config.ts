import type { Config } from 'tailwindcss'

export default <Partial<Config>>{
  content: [
    './app.vue',
    './components/**/*.{vue,js,ts}',
    './layouts/**/*.vue',
    './pages/**/*.vue',
    './composables/**/*.{js,ts}',
    './plugins/**/*.{js,ts}'
  ],
  theme: {
    extend: {
      fontFamily: {
        display: ['"DM Serif Display"', 'serif'],
        sans: ['"Sora"', 'ui-sans-serif', 'system-ui']
      },
      colors: {
        ink: '#0f172a',
        sea: {
          50: '#eef6f7',
          100: '#dbecef',
          200: '#bad8df',
          300: '#90c0cc',
          400: '#5fa2b2',
          500: '#3f8798',
          600: '#336d7c',
          700: '#2d5a67',
          800: '#294b55',
          900: '#264049'
        },
        sand: {
          50: '#fef8ec',
          100: '#fbefcf',
          200: '#f7df9e',
          300: '#f1c966',
          400: '#ebaf3a',
          500: '#e19420',
          600: '#c7721a',
          700: '#a5501a',
          800: '#86401d',
          900: '#6f351b'
        }
      },
      boxShadow: {
        panel: '0 20px 45px -18px rgba(15, 23, 42, 0.35)'
      },
      backgroundImage: {
        halo: 'radial-gradient(circle at top right, rgba(241, 201, 102, 0.25), transparent 45%), radial-gradient(circle at 20% 20%, rgba(95, 162, 178, 0.2), transparent 35%)'
      }
    }
  }
}
