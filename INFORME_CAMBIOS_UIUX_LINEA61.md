# Informe de cambios UI/UX — Línea 61

**Fecha:** 27 de octubre de 2026  
**Alcance ejecutado:** Acceso y seguridad (cierre), Parametrización, Transacciones/operación y Reportes, más la protección de integridad de asignaciones ya incorporada en el commit `415b052`.

---

## 1. Resumen por fase

### Base visual compartida
- Se reescribieron los tokens de `resources/css/app.css` (azul `#0B3C78`, vino `#7B1E2B`, neutros, estados semánticos, radios, sombras y escala tipográfica con Inter).
- Se definieron componentes reutilizables: `page-header`, `page-title`, `page-subtitle`, `page-actions`, `section-title`, `card`, `panel-muted`, `table`, `table-actions`, `btn-brand`, `btn-wine`, `btn-outline-brand`, `badge-status`, `empty-state`, `form-section`, `meta-label`/`meta-value`, `search-field`.
- Se eliminaron los estilos globales agresivos de `.card` (gradientes y sombras fuertes); las tarjetas ahora son paneles neutros con borde sutil.
- `layouts/app.blade.php` quedó sin `style="…"` ni `<script>` ejecutable. El sidebar móvil se controla desde `resources/js/app.js` con `aria-expanded`, cierre por botón, backdrop y tecla Escape.
- Se eliminó el `id="sidebarToggle"` duplicado: el botón de apertura vive en la barra superior y el de cierre dentro del sidebar.
- `layouts/navigation.blade.php` reordena el menú a **Seguridad → Parametrización → Transacciones → Reportes**, con `aria-current` y `aria-controls`.
- Confirmaciones destructivas mediante `data-confirm` (sin `onclick`) e impresión mediante `data-print` (sin `window.print()` inline).

### Fase 1 — Acceso y seguridad
- `users/*`, `roles/*` (incluida la asignación usuario·rol) y `profile/*` migrados al sistema visual compartido.
- Formularios agrupados por secciones, errores junto al campo y acciones primaria/secundaria consistentes.
- `roles/edit` y `profile/edit` ahora extienden `layouts.app` (antes dependerían del slot `header` de `<x-app-layout>`, que el layout no renderiza).
- Las pantallas de autenticación y las páginas de error ya habían sido renovadas en el commit `415b052`; se conservaron.

### Fase 2 — Parametrización
- Conductores, propietarios, micros, internos, rutas, paradas, rutas-paradas y turnos: índices con buscador, tabla responsiva, acciones agrupadas, estados con texto e icono, paginación en pie de tarjeta.
- Formularios agrupados por flujo con `form-section`; vistas de detalle con `panel-muted` y pares etiqueta/valor.
- `rutas_paradas` migrado de `<x-app-layout>` a `@extends('layouts.app')`.
- JS inline retirado de `ruta/form`, `parada/form`, `parada/index` y `parada/show`, y extraído a `resources/js/ruta-form.js` y `resources/js/parada-map.js`.

### Fase 3 — Transacciones y operación
- Dashboard, monitoreo, control de paradas, control de recorrido y asignación de turnos renovados.
- JS inline externalizado a `resources/js/dashboard.js`, `monitoreo.js` y `control-recorrido.js`, con guardas de doble inicialización.
- El dashboard dejó de mostrar valores simulados: se eliminaron los reemplazos `0 → 24/18/11/3` de `routes/web.php`.
- Monitoreo distingue explícitamente «Sin reporte GPS» de una posición real y no dibuja marcador ni velocidad sin datos.
- Asignación de turnos usa acciones explícitas (Asignar, Ver, Editar, Cancelar) y confirmación en operaciones de impacto.

### Fase 4 — Reportes
- Índice y los tres reportes usan el mismo patrón: filtros, período visible, resumen, tabla y exportaciones existentes.
- `documento.blade.php` conserva un bloque `<style>` **por necesidad de DomPDF** (no resuelve Vite ni variables CSS).
- Se añadieron estilos de impresión en `reportes.css` y se restauró el botón «Imprimir» con `data-print`.
- La exportación «Excel» se mantiene porque el backend genera CSV; ver defectos abajo.

---

## 2. Archivos creados

**JavaScript**
- `resources/js/ruta-form.js`
- `resources/js/parada-map.js`
- `resources/js/dashboard.js`
- `resources/js/monitoreo.js`
- `resources/js/control-recorrido.js`

**Documentación**
- `INFORME_CAMBIOS_UIUX_LINEA61.md`

## 3. Archivos modificados (80)

- `resources/css/app.css`, `catalogos.css`, `control-recorrido.css`, `dashboard.css`, `monitoreo.css`, `reportes.css`
- `resources/js/app.js`
- `resources/views/layouts/app.blade.php`, `navigation.blade.php`
- `resources/views/users/{index,create,edit}.blade.php`
- `resources/views/roles/{index,create,edit,assign,assign-form}.blade.php`
- `resources/views/profile/edit.blade.php` y sus tres parciales
- `resources/views/conductor/*`, `propietario/*`, `micro/*`, `interno/*`, `ruta/*`, `parada/*`, `rutas_paradas/*`, `turno/*`
- `resources/views/dashboard.blade.php`
- `resources/views/asignacion_turno/*`
- `resources/views/monitoreo/index.blade.php`
- `resources/views/control_paradas/index.blade.php`
- `resources/views/control_recorrido/{index,show,historial,mapa}.blade.php`
- `resources/views/reportes/*` y sus parciales
- `routes/web.php` (eliminación de indicadores simulados)
- `vite.config.js` (entradas de los nuevos JS)

---

## 4. Decisiones de diseño

- **Paleta:** se conservaron los valores institucionales existentes. El azul se usa para estructura y acción primaria; el vino tinto para la marca (logo, iconos de título, acción operativa destacada). Los estados (éxito, advertencia, peligro, información) usan colores semánticos independientes.
- **Tipografía:** Inter con escala de 24–28 px para título de página, 17–19 px para sección, 14–16 px para cuerpo y 12–13 px para texto auxiliar.
- **Layout:** sidebar sólido azul oscuro, barra superior blanca, contenido sobre fondo neutro. Sin degradados decorativos; sombras leves.
- **Tablas:** se mantienen tabulares, con encabezados en versalitas, filas uniformes y acciones agrupadas. No se convirtieron en tarjetas.
- **Responsive:** sidebar fuera de pantalla por debajo de 992 px con apertura/cierre accesible; tablas dentro de `table-responsive`; `@media (prefers-reduced-motion: reduce)`.
- **Separación:** Blade solo estructura y datos; CSS en hojas externas; JS en módulos de Vite. Excepción documentada: `reportes/documento.blade.php` para DomPDF.

---

## 5. Comandos y pruebas ejecutados

| Comando | Resultado |
|---|---|
| `php artisan route:list --except-vendor` | 165 rutas, sin errores |
| `php artisan view:cache` / `view:clear` | Compilación Blade correcta |
| `node --check` (JS de fase 3) | Sin errores de sintaxis |
| `npm run build` | Correcto (Vite 8.0.3) |
| `vendor/bin/pint --dirty --format agent` | `pass` |
| `php artisan test --compact` | **109 aprobadas, 1 omitida, 375 aserciones** |
| `php artisan test tests/Feature/ControlRecorridoTest.php tests/Feature/ReportesTest.php` | 14 aprobadas |

> La verificación responsive es estructural (CSS y jerarquía); no se hizo captura visual en navegador a 360/768/1024/1440 px en este entorno. Queda como validación manual recomendada.

---

## 6. Limitaciones del entorno

1. **PHP CLI 8.3.30 vs. `composer.lock` con paquetes que exigen ≥ 8.4.1** (`symfony/yaml`, `symfony/type-info`). `composer install` normal falla. Se instaló con:
   `composer install --ignore-platform-reqs`
   **Recomendación:** validar en Laragon con PHP 8.4 y ejecutar `composer install` sin el flag.
2. `php` del PATH es 8.3; no hay binario 8.4 en `C:\laragon\bin\php`. Las pruebas se ejecutaron con 8.3 y pasaron.
3. Sin red externa, los mapas Leaflet/Nominatim y las fuentes/iconos por CDN no cargan; el resto de la interfaz sí.
4. No se probó la app autenticada en navegador real ni con datos de producción.

---

## 7. Correcciones funcionales realizadas (separadas de lo visual)

1. **Indicadores ficticios del dashboard:** `routes/web.php` sustituía conteos reales en cero por 24, 18, 11 y 3. Se eliminó ese reemplazo; ahora se muestra el valor real (incluido 0).
2. **Confirmaciones e impresión sin JS inline:** handler global `data-confirm` y `data-print` en `app.js`, para retirar `onclick` de las vistas.
3. **Escapado seguro de datos JS:** `data-paradas-*` en control de recorrido usa `json_encode` con `JSON_HEX_*` en lugar de `@json` dentro de comillas simples.
4. **Layouts de roles/perfil:** `roles/edit` y `profile/edit` ahora usan `layouts.app`, coherente con el resto de la aplicación.

---

## 8. Defectos detectados y NO corregidos

1. **`rutas_paradas/index`:** la paginación usa `{{ $rutasParadas->links() }}` sin `appends(['buscar' => $buscar])`; se pierde el filtro al paginar.
2. **`MonitoreoController::posicionesEnVivo()`:** cuando no hay GPS devuelve la primera parada o el centro de la ciudad y velocidad `0`. La vista ignora esos valores si el reporte es «Sin reporte», pero la API sigue exponiendo coordenadas potencialmente engañosas.
3. **`ReporteParadasOmitidas::filtrosAplicados()`:** usa `Carbon::parse()` sin validar `desde`/`hasta`; un valor malformado puede lanzar excepción.
4. **Exportación «Excel»:** el endpoint genera CSV, no `.xlsx`. La interfaz conserva el nombre existente para no cambiar el contrato.
5. **`welcome.blade.php`:** mantiene estilos y script inline; no formaba parte de los módulos del plan.
6. **Componentes Breeze sin uso** (`components/dropdown.blade.php`, `components/modal.blade.php`): conservan `style="display: none"`. No se renderizan en ninguna vista.
7. **Hora prevista en historial de recorrido:** se calcula sumando 5 minutos fijos por parada (lógica existente); no es una hora GPS real.
8. **Métrica «Micros fuera de servicio»:** cuenta `estado != activo`; conviene confirmar si esa es la definición de negocio esperada.

---

## 9. Validación local recomendada (Laragon)

1. Usar PHP 8.4 y ejecutar `composer install`.
2. `npm run build` (o `npm run dev` durante desarrollo).
3. `php artisan optimize:clear`.
4. Recorrer con sesión iniciada: Dashboard, cada catálogo, asignación de turnos, monitoreo, control de recorrido y los tres reportes.
5. Probar a 360, 768, 1024 y 1440 px: apertura/cierre del sidebar, tablas con scroll interno y ausencia de scroll horizontal global.
6. Verificar impresión de reportes (botón «Imprimir» y `Ctrl+P`) y exportaciones PDF/CSV.
7. Ejecutar `php artisan test` para confirmar la suite completa en el entorno final.
