# Coleabg Nuxt App (Migracion Full-Stack)

Aplicacion Nuxt 3 que reemplaza el sitio PHP legacy con:
- Backend API en `server/api/**`
- Frontend Vue 3 + Tailwind CSS
- Conexion directa a SQL Server
- Sesion segura por cookie firmada

## Estado de la migracion

### Flujo publico
- Inicio institucional: `pages/index.vue`
- Busqueda de operaciones por cedula/inpre: `pages/search/index.vue`
- Listado completo de operaciones SAFACT / SAACXC: `pages/operations/[cod].vue`
- Soporte: `pages/support.vue`

### Flujo de afiliados
- Login: `pages/auth/login.vue`
- Registro: `pages/auth/register.vue`
- Dashboard de operaciones recientes: `pages/dashboard.vue`
- Cambio de contrasena: `pages/auth/change-password.vue`

### Flujo de trabajadores
- Ingreso workers y asistencia: `pages/workers/index.vue`
- Geolocalizacion con reglas de campus y excepciones heredadas

### Flujo administrativo
- Resumen de incidencias y duplicados: `pages/admin/index.vue`
- Marcar NumeroD como resuelto
- Registrar solvencia
- Consultar asistencias por rango de fecha

## Endpoints principales

### Auth
- `POST /api/auth/login`
- `POST /api/auth/register`
- `POST /api/auth/logout`
- `GET /api/auth/me`
- `POST /api/auth/change-password`

### Operaciones y abogados
- `GET /api/lawyers/search`
- `GET /api/lawyers/details`
- `GET /api/operations/recent`
- `GET /api/operations/list`

### Workers
- `POST /api/workers/login`
- `POST /api/workers/attendance/entry`
- `POST /api/workers/attendance/exit`

### Admin
- `GET /api/admin/overview`
- `GET /api/admin/diagnostics`
- `POST /api/admin/mark-solved`
- `POST /api/admin/solvency`
- `GET /api/admin/assists`

### Soporte
- `POST /api/contact`

## Variables de entorno

Copiar `.env.example` y completar valores reales:
- `NUXT_SESSION_SECRET`
- `DB_SERVER`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`
- `DB_ENCRYPT`, `DB_TRUST_SERVER_CERTIFICATE`
- `SMTP_HOST`, `SMTP_PORT`, `SMTP_SECURE`, `SMTP_USER`, `SMTP_PASS`
- `SUPPORT_EMAIL`

## Arranque local

> En el entorno actual no hay Node/NPM instalados. Cuando tengas Node disponible:

```bash
npm install
npm run dev
```

## Notas tecnicas

- SQL Server se maneja desde `server/utils/db.ts` y `server/utils/repositories.ts`.
- Las contrasenas legacy en texto plano se auto-migran a hash bcrypt al iniciar sesion.
- La autenticacion usa cookie HTTP-only firmada (`server/utils/session.ts`).
- El diseno mantiene Tailwind pero con un sistema visual propio (sin plantilla generica).
