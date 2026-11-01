# AGENTS.md

## Project

Sistema web de control de turnos, recorridos y seguimiento GPS para la **Línea 61** (Santa Cruz, Bolivia).

- Laravel 13 + Blade + Bootstrap 5 (CDN) + Tailwind/Vite. No Livewire, Inertia ni SPA.
- PHP objetivo: **8.4.1+** (aunque `composer.json` declare `^8.3`).
- Base local: MySQL; tests: SQLite en memoria.
- `README.md` es el README genérico de Laravel: no describe este proyecto.
- Contexto de dominio: `NORMALIZACION_USUARIOS_PERFILES.md` (SDD), `REPORTES.md` e `INFORME_CAMBIOS_UIUX_LINEA61.md`.

## Comandos

```bash
composer run setup          # instala, crea .env, migra, compila frontend
composer run dev            # serve + queue:listen + vite
php artisan test --compact  # suite completa
php artisan test --compact tests/Feature/ControlRecorridoTest.php
php artisan test --compact tests/Feature/ControlRecorridoTest.php --filter=test_show_screen_can_be_rendered
vendor/bin/pint --dirty --format agent   # obligatorio tras tocar PHP
npm run build               # verifica el manifest de Vite
php artisan db:seed         # catálogos Línea 61 + usuarios demo
php artisan route:list --path=control-recorrido
```

- **Gotcha PHP:** `composer.lock` resuelve paquetes Symfony que exigen PHP >= 8.4.1. Con el PHP 8.3.30 de este entorno, `composer install` falla la comprobación de plataforma; usar PHP 8.4.1+ o, solo en local, `composer install --ignore-platform-reqs`.
- No hay CI ni pre-commit hooks: ejecutar tests, Pint y build localmente.

## Arquitectura

- `routes/web.php`: UI administrativa con `auth`, `verified`, `ensure.role.assigned` y `role:...`.
- `routes/api.php`: API Sanctum para la app móvil. Usa `/api/mis/...` y valida que el usuario autenticado tenga un `Conductor` asociado y sea el dueño de la asignación.
- Roles Spatie: `admin`, `fiscalizador`, `propietario`, `conductor`. Un usuario sin rol es redirigido a `pending.role`.
- Catálogos con eliminación lógica (`estado = activo|inactivo`) mediante `TieneEstadoLogico`. No hacer borrados físicos.
- `IntegridadAsignacionesService` impide desactivar conductor, micro, ruta o turno si tienen asignaciones `pendiente`, `en_curso` o `retrasado`.
- `AsignacionTurnoService` concentra las reglas de creación, inicio, fin y cancelación (incluida la no duplicidad conductor/micro por fecha + turno).
- Reportes: clases en `app/Reportes`; `ReporteController` resuelve `show`, `pdf` (DomPDF) y `excel` (CSV). Ver `REPORTES.md`.
- Frontend: cada módulo es una entrada separada de Vite. Al crear CSS/JS nuevo hay que agregarlo a `input` en `vite.config.js` y cargarlo con `@vite` en la vista.
- JS propio en `resources/js/*.js`; el sidebar, confirmaciones (`data-confirm`) e impresión (`data-print`) viven en `app.js`.

## Control de Recorrido y GPS: invariantes

- `ControlRecorridoService` es la única fuente de verdad para `planificar()`, `evaluarPunto()` y `resumen()`.
- `AsignacionTurnoService::iniciar()` crea el itinerario planificado con todas las paradas activas de la ruta.
- Radio de tolerancia: **80 m**. Si el GPS detecta una parada posterior, las paradas intermedias se marcan `omitido`.
- `control_recorrido.fecha_hora` es nullable: las paradas pendientes se muestran con `—`. No inventar horarios previstos.
- `Control de Recorrido` solo muestra la secuencia de paradas, contadores y hora real de paso. **No** tiene mapa ni pantallas de historial/mapa ampliado; fueron eliminadas a propósito.
- `Seguimiento GPS` es el único dueño del mapa en vivo: `MonitoreoController` + `SeguimientoGpsService::flotaEnVivo()` + `resources/js/monitoreo.js`.
- Ingesta GPS: `POST /api/mis/asignaciones/{id}/ubicaciones` y sincronización en lote (máx. 300 puntos). Límite de 120 req/min; la hora GPS no puede superar 2 minutos en el futuro.
- El estado `en_proceso` de una parada es derivado en la UI para la siguiente parada pendiente; no es un estado persistido nuevo.

## Tests y convenciones

- PHPUnit, no Pest. La suite usa SQLite `:memory:` y `RefreshDatabase`; no requiere MySQL.
- Solo existen factories para `User`, `Ruta` y `Parada`; el resto de modelos se crean explícitamente en los tests.
- `tests/Feature/ControlRecorridoTest.php` cubre planificación, omisiones, contadores y vistas de Control de Recorrido.
- No crear scripts de verificación ni usar tinker si un test ya cubre el comportamiento.
- UI, mensajes y rutas están en español. Rutas de recursos en singular (`conductor`, `micro`, `ruta`, `parada`, `turno`) y `rutas-paradas`.
- Usar rutas nombradas (`route()`) y componentes/estilos existentes antes de crear otros.
- No cambiar dependencias ni crear carpetas base nuevas sin aprobación.
- Solo crear documentación si el usuario la pide explícitamente.

## Usuarios demo

`php artisan db:seed` crea:

- `admin@example.com` / `password` (admin)
- `conductor@linea61.test` / `password` (conductor)
- Una asignación de turno para el día actual y catálogos con paradas reales de la Línea 61.
