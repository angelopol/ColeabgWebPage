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
        vino: {
          50: '#f8f1f3',
          100: '#f2e4e8',
          200: '#e6c9d2',
          300: '#d5a3b3',
          400: '#bd748f',
          500: '#a34d72',
          600: '#8a375c',
          700: '#74294b',
          800: '#5f213e',
          900: '#4d1a33'
        },
        verde: {
          50: '#eefaf2',
          100: '#d7f2df',
          200: '#b3e4c1',
          300: '#82cf98',
          400: '#54b373',
          500: '#369759',
          600: '#277c47',
          700: '#20633a',
          800: '#1d4f31',
          900: '#193f28'
        },
        sea: {
          50: '#eefaf2',
          100: '#d7f2df',
          200: '#b3e4c1',
          300: '#82cf98',
          400: '#54b373',
          500: '#369759',
          600: '#277c47',
          700: '#20633a',
          800: '#1d4f31',
          900: '#193f28'
        },
        sand: {
          50: '#f8f1f3',
          100: '#f2e4e8',
          200: '#e6c9d2',
          300: '#d5a3b3',
          400: '#bd748f',
          500: '#a34d72',
          600: '#8a375c',
          700: '#74294b',
          800: '#5f213e',
          900: '#4d1a33'
        }
      },
      boxShadow: {
        panel: '0 20px 45px -18px rgba(15, 23, 42, 0.35)'
      },
      backgroundImage: {
        halo: 'radial-gradient(circle at top right, rgba(163, 77, 114, 0.3), transparent 45%), radial-gradient(circle at 20% 20%, rgba(54, 151, 89, 0.26), transparent 35%)'
      }
    }
  }
}
