# Mapa de Migracion PHP -> Nuxt

El codigo PHP legacy fue movido a la carpeta `legacy/`.

| Legacy PHP | Nuxt Frontend | Nuxt API |
|---|---|---|
| `index.php` | `/` | - |
| `ingre.php`, `login.php` | `/auth/login` | `POST /api/auth/login` |
| `regis.php`, `regis2.php` | `/auth/register` | `POST /api/auth/register` |
| `pageuser.php` | `/dashboard` | `GET /api/operations/recent` |
| `recpass.php`, `recpass2.php` | `/auth/change-password` | `POST /api/auth/change-password` |
| `search.php`, `search2.php`, `system.php`, `system2.php` | `/search` | `GET /api/lawyers/details` |
| `all.php`, `allmore.php` | `/operations/:cod` | `GET /api/operations/list` |
| `ingre_workers.php`, `login_workers.php` | `/workers` | `POST /api/workers/login` |
| `assist1.php` | `/workers` | `POST /api/workers/attendance/entry` |
| `assist2.php` | `/workers` | `POST /api/workers/attendance/exit` |
| `HomeFailed.php`, `OperationsStatusTwo.php` | `/admin` | `GET /api/admin/diagnostics` |
| `DeleteFailed.php` | `/admin` | `POST /api/admin/mark-solved` |
| `solv.php`, `solv2.php` | `/admin` | `POST /api/admin/solvency` |
| `AssistsView2.php` | `/admin` | `GET /api/admin/assists` |
| `soport.php`, `suport2.php` | `/support` | `POST /api/contact` |

## Pendientes opcionales
- Migrar pantallas legacy secundarias (SetCarnetNumFamily, DateScripts, SearchRange, etc.) como submodulos del area `/admin`.
- Incorporar paginacion servidor para listados largos en diagnosticos.
- Agregar tests E2E sobre flujos criticos (login, busqueda, asistencia, solvencia).
