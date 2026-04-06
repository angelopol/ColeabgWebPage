# Gestor De Soporte - Documentacion Tecnica Integral

## 1. Resumen Ejecutivo

El Gestor De Soporte es un modulo interno de la aplicacion Nuxt que permite:

1. Registrar actividades de soporte.
2. Gestionar estados (pendiente/finalizada).
3. Filtrar actividades por estado, fecha y keyword.
4. Generar reportes de actividades finalizadas por rango de fechas.
5. Exportar el reporte en DOC y PDF.
6. Compartir PDF por canales del dispositivo (WhatsApp, correo, etc.) cuando el navegador lo soporte.

El modulo usa SQL Server para persistencia, Nitro API routes para backend, y un esquema de acceso por cookie firmada con HMAC.

## 2. Alcance Funcional

Incluye:

1. Control de acceso por clave de soporte.
2. CRUD parcial de actividades:
	- Crear actividad.
	- Actualizar actividad.
	- Cambiar estado pendiente/finalizada.
3. Listado filtrado.
4. Reportes por fecha de finalizacion.
5. Exportacion/comparticion de documento.

No incluye:

1. Usuarios por rol dentro del modulo de soporte (solo acceso por clave global).
2. Auditoria historica de cambios por usuario.
3. Versionado de reportes.

## 3. Infraestructura

### 3.1 Stack y Runtime

1. Frontend + Backend: Nuxt 3 (Nitro).
2. UI: Vue 3 + Tailwind.
3. DB: SQL Server (driver mssql).
4. Validaciones: zod.
5. Sesion de acceso soporte: cookie httpOnly firmada.

Dependencias relevantes:

1. nuxt
2. @nuxtjs/tailwindcss
3. mssql
4. zod

### 3.2 Variables De Entorno Relevantes

1. NUXT_SESSION_SECRET: secreto de firmado de cookies.
2. SOPORTE_PSSWD: clave de acceso al modulo de soporte.
3. DB_SERVER, DB_PORT, DB_NAME, DB_USER, DB_PASS: conexion a SQL Server.
4. DB_ENCRYPT, DB_TRUST_SERVER_CERTIFICATE: parametros TLS/driver.

### 3.3 Conectividad DB

La app usa un pool global de conexiones con estas caracteristicas:

1. Reutilizacion de pool en runtime.
2. max=20, min=0, idleTimeoutMillis=30000.
3. Conexion lazy (se crea al primer uso).

## 4. Arquitectura

## 4.1 Vista Por Capas

1. Capa Presentacion:
	- pages/support.vue
2. Capa API (Nitro routes):
	- server/api/support/*
3. Capa Dominio/Utilidades:
	- server/utils/support-access.ts
	- server/utils/support-tasks.ts
4. Capa Datos:
	- SQL Server, tabla dbo.SupportTasks

## 4.2 Diagrama Conceptual

```text
Usuario
  -> /support (Vue)
  -> /api/support/status|access|tasks|report (Nitro)
  -> support-access (cookie/HMAC) + support-tasks (SQL)
  -> dbo.SupportTasks (SQL Server)
```

## 5. Componentes Del Backend

### 5.1 Seguridad De Acceso

Archivo principal: server/utils/support-access.ts

Responsabilidades:

1. Verificar clave de soporte.
2. Emitir cookie de acceso firmada (cab_support_access).
3. Validar cookie en cada endpoint protegido.
4. Expirar acceso a los 90 dias.

Detalles tecnicos:

1. Firma: HMAC SHA-256.
2. Comparacion segura: timingSafeEqual.
3. Cookie:
	- httpOnly: true
	- sameSite: lax
	- secure: en produccion
	- maxAge: 90 dias

### 5.2 Motor De Actividades

Archivo principal: server/utils/support-tasks.ts

Responsabilidades:

1. Auto-crear tabla SupportTasks si no existe.
2. Listar actividades con filtros.
3. Crear actividad (pendiente o finalizada).
4. Actualizar campos y transiciones de estado.
5. Consultar finalizadas para reporte.

### 5.3 Endpoints

1. GET /api/support/status
	- Devuelve granted: boolean.

2. POST /api/support/access
	- Body: password.
	- Si valida, genera cookie de acceso.

3. GET /api/support/tasks
	- Requiere acceso.
	- Query opcional: status, fromDate, toDate, keyword.

4. POST /api/support/tasks
	- Requiere acceso.
	- Registra actividad.
	- Keywords se generan automaticamente desde title.
	- status inicial puede ser pendiente o finalizada.

5. PATCH /api/support/tasks/:id
	- Requiere acceso.
	- Actualiza campos y/o estado.

6. POST /api/support/report
	- Requiere acceso.
	- Body: fromDate, toDate, deParte, para.
	- Retorna actividades finalizadas en el rango.

## 6. Modelo De Datos

Tabla: dbo.SupportTasks

Columnas:

1. id INT IDENTITY PK
2. title NVARCHAR(220) NOT NULL
3. description NVARCHAR(MAX) NULL
4. keywords NVARCHAR(400) NULL
5. module NVARCHAR(120) NULL
6. taskDate DATE NULL
7. status NVARCHAR(20) NOT NULL (pendiente/finalizada)
8. createdAt DATETIME2 NOT NULL (default SYSDATETIME)
9. updatedAt DATETIME2 NOT NULL (default SYSDATETIME)
10. completedAt DATETIME2 NULL

Indices:

1. IX_SupportTasks_status(status)
2. IX_SupportTasks_taskDate(taskDate)
3. IX_SupportTasks_completedAt(completedAt)

## 7. Algoritmos Clave

### 7.1 Generacion Automatica De Keywords

Ubicacion: server/api/support/tasks.post.ts

Reglas:

1. Tokenizar el titulo por espacios.
2. Normalizar a minusculas.
3. Quitar acentos y caracteres no alfanumericos.
4. Quitar stopwords comunes.
5. Quitar tokens de menos de 3 caracteres.
6. Eliminar duplicados.
7. Limitar a maximo 10 keywords.
8. Guardar en formato CSV en la columna keywords.

Pseudocodigo:

```text
tokens = split(title)
tokens = normalize(tokens)
tokens = filter(len >= 3 && !stopword)
tokens = unique(tokens)
tokens = take(10)
keywords = join(tokens, ", ")
```

### 7.2 Transicion De Estado

Reglas:

1. Si status pasa a finalizada y existe taskDate, completedAt = taskDate (00:00:00).
2. Si status pasa a finalizada y no existe taskDate, completedAt = SYSDATETIME().
3. Si status pasa a pendiente, completedAt = NULL.

Esto aplica en:

1. Creacion (POST /tasks) cuando status inicial es finalizada.
2. Actualizacion (PATCH /tasks/:id).

### 7.3 Filtro De Listado

El listado aplica filtros combinados:

1. status exacto (si se envia).
2. Rango de fecha sobre COALESCE(taskDate, createdAt).
3. keyword sobre title, description, keywords y module.

### 7.4 Reporte De Actividades

Consulta de reporte:

1. Solo status=finalizada.
2. completedAt IS NOT NULL.
3. Filtro por rango de completedAt (DATE).
4. Orden por completedAt asc, id asc.

## 8. Flujos Operativos

### 8.1 Flujo De Acceso

1. Front consulta /api/support/status.
2. Si granted=false, muestra formulario de clave.
3. Usuario envia clave a /api/support/access.
4. Backend valida y firma cookie.
5. Front recarga datos de actividades.

### 8.2 Flujo De Registro De Actividad

1. Usuario abre modal Nueva tarea.
2. Ingresa titulo (obligatorio), modulo (opcional), descripcion (opcional), fecha DD/MM/YEAR, checkbox finalizada.
3. Front envia POST /api/support/tasks.
4. Backend genera keywords desde el titulo.
5. Si status inicial es finalizada y hay taskDate, completedAt toma esa misma fecha.
6. Front refresca listado.

### 8.3 Migracion De Fechas Finalizadas

Objetivo:

1. Sincronizar completedAt con la fecha de taskDate para actividades finalizadas ya existentes.
2. Si taskDate es NULL, usar CAST(createdAt AS DATE) como respaldo.

Script preparado:

1. server/migrations/20260406_supporttasks_sync_completedAt_with_taskDate.sql

Ejecucion recomendada:

1. Revisar la previsualizacion del script.
2. Ejecutar en ventana de mantenimiento.
3. Confirmar updatedRows al finalizar.

### 8.4 Flujo De Edicion

1. Usuario abre modal Editar tarea.
2. Ajusta campos o estatus.
3. Front envia PATCH /api/support/tasks/:id.
4. Backend actualiza registro y timestamps.
5. Front refresca listado.

### 8.5 Flujo De Reporte

1. Usuario abre modal Generar reporte.
2. Indica fromDate, toDate, deParte, para.
3. Front envia POST /api/support/report.
4. Front muestra vista previa.
5. Puede:
	- Imprimir HTML
	- Descargar DOC
	- Exportar/Compartir PDF

### 8.6 Flujo Exportar/Compartir PDF

1. Front arma PDF basico en cliente (sin libreria externa).
2. Si el navegador soporta Web Share con archivos:
	- Abre selector nativo de apps (WhatsApp/correo/etc).
3. Si no lo soporta:
	- Descarga el PDF para compartir manualmente.

## 9. Contratos API (Resumen)

### 9.1 POST /api/support/tasks

Body:

```json
{
  "title": "Ajuste de acceso movil",
  "description": "",
  "module": "Soporte",
  "taskDate": "2026-04-06",
  "status": "finalizada"
}
```

Response:

```json
{
  "ok": true,
  "task": {
	 "id": 10,
	 "title": "Ajuste de acceso movil",
	 "description": null,
	 "keywords": "ajuste, acceso, movil",
	 "module": "Soporte",
	 "taskDate": "2026-04-06",
	 "status": "finalizada",
	 "createdAt": "...",
	 "updatedAt": "...",
	 "completedAt": "..."
  }
}
```

### 9.2 POST /api/support/report

Body:

```json
{
  "fromDate": "2026-04-01",
  "toDate": "2026-04-06",
  "deParte": "Equipo de Soporte e Informatica",
  "para": "Junta Directiva"
}
```

Response:

```json
{
  "report": {
	 "generatedAt": "...",
	 "fromDate": "2026-04-01",
	 "toDate": "2026-04-06",
	 "deParte": "Equipo de Soporte e Informatica",
	 "para": "Junta Directiva",
	 "tasks": []
  }
}
```

## 10. Seguridad

Controles implementados:

1. Cookie httpOnly firmada para acceso al modulo.
2. Validacion fuerte de payloads con zod.
3. Comparacion de secretos con timingSafeEqual.
4. Queries parametrizadas (sin concatenacion de input de usuario en SQL).
5. Mensajes de error generales para no exponer detalles sensibles de infraestructura.

Riesgos actuales a considerar:

1. Clave unica global para todo el equipo (sin trazabilidad por usuario).
2. Comparticion PDF depende del soporte del navegador/dispositivo.
3. PDF cliente actual es funcional, pero basico en tipografia y maquetacion.

## 11. Observabilidad Y Operacion

Indicadores recomendados:

1. Cantidad de actividades creadas por dia.
2. Tiempo promedio de cierre (createdAt -> completedAt).
3. Tasa de reapertura (finalizada -> pendiente).
4. Errores 401/400/500 en endpoints de soporte.

Checklist operativo:

1. Verificar SOPORTE_PSSWD en entorno.
2. Verificar NUXT_SESSION_SECRET estable y fuerte.
3. Verificar conectividad SQL Server.
4. Confirmar permisos de la cuenta DB para CRUD en SupportTasks.
5. Probar reporte con rango sin resultados y con resultados.

## 12. Pruebas Recomendadas

### 12.1 Funcionales

1. Acceso correcto e incorrecto.
2. Creacion pendiente y finalizada.
3. Generacion automatica de keywords.
4. Filtro por estado y fechas.
5. Actualizacion de estado y campos.
6. Reporte con rango vacio y no vacio.

### 12.2 UX/Responsive

1. Modal de nueva actividad en 320px/375px/768px.
2. Tab pendientes/finalizadas en movil.
3. Compartir PDF en Android/iOS (si browser soporta Web Share).

### 12.3 Seguridad

1. Endpoint protegido sin cookie debe responder 401.
2. Cookie alterada debe ser rechazada.
3. Inputs invalidos deben responder 400.

## 13. Mejoras Futuras Sugeridas

1. Reemplazar clave global por usuarios de soporte con roles.
2. Agregar auditoria: usuario, accion, fecha, valor anterior/nuevo.
3. Exportar PDF con libreria especializada (layout mas robusto).
4. Plantillas de reporte por tipo de actividad.
5. Dashboard con metricas de SLA.

## 14. Mapa De Archivos Del Modulo

Frontend:

1. pages/support.vue

API:

1. server/api/support/status.get.ts
2. server/api/support/access.post.ts
3. server/api/support/tasks.get.ts
4. server/api/support/tasks.post.ts
5. server/api/support/tasks/[id].patch.ts
6. server/api/support/report.post.ts
7. server/api/support/tasks/import.post.ts

Utilidades:

1. server/utils/support-access.ts
2. server/utils/support-tasks.ts
3. server/utils/support-keywords.ts
4. server/utils/db.ts

Migraciones:

1. server/migrations/20260406_supporttasks_sync_completedAt_with_taskDate.sql

