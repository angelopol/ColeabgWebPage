import { defineNuxtConfig } from 'nuxt/config'

export default defineNuxtConfig({
  compatibilityDate: '2025-01-15',
  modules: ['@nuxtjs/tailwindcss'],
  css: ['~/assets/css/main.postcss'],
  devtools: { enabled: true },
  app: {
    head: {
      title: 'Colegio de Abogados del Estado Carabobo',
      meta: [
        {
          name: 'description',
          content:
            'Plataforma oficial para consultas de operaciones, solvencias y asistencia de trabajadores.'
        },
        {
          name: 'theme-color',
          content: '#5f213e'
        }
      ],
      link: [
        {
          rel: 'icon',
          type: 'image/png',
          href: '/favicon.png'
        },
        {
          rel: 'apple-touch-icon',
          href: '/logo.png'
        }
      ]
    }
  },
  runtimeConfig: {
    sessionSecret: process.env.NUXT_SESSION_SECRET || 'change-this-secret',
    smtpHost: process.env.SMTP_HOST || '',
    smtpPort: Number(process.env.SMTP_PORT || 465),
    smtpUser: process.env.SMTP_USER || '',
    smtpPass: process.env.SMTP_PASS || '',
    smtpSecure: (process.env.SMTP_SECURE || 'true') === 'true',
    supportEmail: process.env.SUPPORT_EMAIL || process.env.SMTP_USER || '',
    dbUser: process.env.DB_USER || '',
    dbPass: process.env.DB_PASS || '',
    dbServer: process.env.DB_SERVER || '',
    dbPort: Number(process.env.DB_PORT || 1433),
    dbName: process.env.DB_NAME || '',
    solvenciaPassword: process.env.SOLVENCIA_PSSWD || '',
    soportePassword: process.env.SOPORTE_PSSWD || '',
    dbEncrypt: (process.env.DB_ENCRYPT || 'false') === 'true',
    dbTrustServerCertificate:
      (process.env.DB_TRUST_SERVER_CERTIFICATE || 'true') === 'true',
    public: {
      appName: 'Colegio de Abogados del Estado Carabobo'
    }
  },
  nitro: {
    routeRules: {
      '/api/**': { cors: true }
    }
  },
  typescript: {
    strict: true
  }
})
