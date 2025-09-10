# WEB PAGE FOR Colegio de Abogados del Estado Carabobo
## Old Project
- Maded with a simple PHP and Microsoft SQL SERVER Database
- Simple emails system
- Functionality to manage the employees assists
- Module to see data to the control of the DB
- College members login, register and dashboard
# ColeabgWebPage (Refactor)

Refactor inicial aplicado para mejorar mantenibilidad, seguridad básica y limpieza del código.

## Cambios Clave
- Capa de acceso a datos (`src/Repositories.php`).
- Bootstrap común (`src/bootstrap.php`) con sesión, conexión PDO, helpers y loader `.env` sencillo.
- Separación de layout (`src/layout.php`, `src/nav.php`).
- Uso de consultas preparadas (mitiga inyección SQL). 
- Escape sistemático de salida con helper `h()`.
- Validación de años (`valid_year_or_null`).
- Archivos críticos refactorizados: `index.php`, `search.php`, `search2.php`, `system.php`, `system2.php`, `all.php`, `allmore.php`.
- Credenciales movibles a entorno: ver `.env.example`.
- Inicio de transición a contraseñas con hash bcrypt (nuevos registros usan `password_hash`; login acepta legacy plano o hash).
- Auto-upgrade transparente: al iniciar sesión con contraseña legacy en texto plano se vuelve a guardar automáticamente con `bcrypt`.
- Variables SMTP externas (si no se configuran se usarán valores placeholder y puede fallar el envío).

## Configuración
1. Copia `.env.example` a `.env` y ajusta valores reales:
```
DB_DSN=odbc:mssql_odbc
DB_USER=usuario
DB_PASS=clave
SMTP_HOST=smtp.gmail.com
SMTP_USER=tu_correo
SMTP_PASS=tu_clave_app
```
2. Asegura que el DSN ODBC exista en el servidor.
3. Evita commitear `.env` con credenciales reales.

## Estructura Principal
```
conn.php            # Fabrica de conexión (lee variables de entorno)
src/bootstrap.php   # Sesión, helpers, carga .env, conexión compartida
src/Repositories.php# Repositorios (LawyerRepository, OperationsRepository)
src/layout.php      # Cabecera/pie reutilizables
src/nav.php         # Barra de navegación
```

## Desarrollo Futuro
- Refactor restante de páginas no tocadas (Set*, Update*, etc.).
- Implementar autoload PSR-4 (Composer) para eliminar requires manuales.
- Añadir pruebas unitarias (PHPUnit) para repositorios.
- Centralizar manejo de errores (try/catch global con página de error).
- Logging (access / error) y auditoría de consultas sensibles.
- Hardening adicional (CSP headers, SameSite cookies, regeneración de sesión tras login).
- Revisión final para garantizar que ya no quedan contraseñas legacy (la auto-actualización acelerará la migración). Script opcional para forzar hashing pendiente de usuarios inactivos.

## Seguridad
- Sustituir la clave por defecto en `conn.php` lo antes posible.
- Revisar permisos de la carpeta `uploads/`.
- Validar inputs restantes en formularios no refactorizados.
- Flujo de cambio de email y eliminación de usuario ahora usan repositorio + verificación híbrida + notificación opcional vía SMTP.

## Soporte
Cualquier nueva página debe incluir únicamente lógica mínima y delegar en repositorios + helpers.
