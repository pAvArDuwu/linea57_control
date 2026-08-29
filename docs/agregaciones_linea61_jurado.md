# Agregaciones implementadas en la aplicación Linea61

## 1. Importante: los rombos en DBeaver no son agregaciones

En DBeaver, los rombos o símbolos de forma diamante que aparecen en un diagrama ER suelen representar relaciones o indicadores visuales del modelo entidad-relación, no consultas de agregación SQL.

En este proyecto, las agregaciones no están definidas como funciones o vistas de base de datos en un archivo aparte. Las métricas se calculan desde Laravel/Eloquent en los controladores y en el modelo de negocio.

Por lo tanto:

- Los rombos en DBeaver no significan que la base de datos tenga agregaciones implementadas.
- Lo que sí existe es lógica de negocio que calcula totales, conteos y métricas sobre las tablas.

## 2. Dónde están implementadas las agregaciones

### 2.1 Dashboard y métricas de asignaciones de turno
Archivo principal:
- [app/Http/Controllers/AsignacionTurnoController.php](../app/Http/Controllers/AsignacionTurnoController.php)

Qué hace:
- obtiene las asignaciones del día
- cuenta los turnos cubiertos
- calcula conductores ocupados
- calcula conductores libres
- calcula unidades en ruta
- calcula incidencias abiertas

Código relevante:
- `AsignacionTurno::where('fecha', $fecha)...->get();`
- `$asignacionesDia->count();`
- `$asignacionesDia->where('estado', 'en_curso')->count();`
- `$asignacionesDia->where('estado', 'retrasado')->count();`

Esto es una agregación por colección en PHP, y la idea es resumir el estado operativos del día.

### 2.2 Conteo de paradas por ruta
Archivo:
- [app/Http/Controllers/RutaController.php](../app/Http/Controllers/RutaController.php)

Qué hace:
- calcula cuántas paradas tiene cada ruta
- usa `withCount('paradas')`

Esto es una agregación Eloquent real en base de datos, porque Laravel genera el `COUNT` SQL.

### 2.3 Monitoreo en vivo y control de recorridos
Archivo:
- [app/Http/Controllers/MonitoreoController.php](../app/Http/Controllers/MonitoreoController.php)

Qué hace:
- cuenta unidades activas
- calcula total de paradas por ruta
- cuenta paradas cumplidas
- arma el JSON para la vista de monitoreo

Código relevante:
- `$paradasRuta->count()`
- `$controlesCumplidos->count()`
- `$unidades->count()`

### 2.4 Dashboard inicial del sistema
Archivo:
- [routes/web.php](../routes/web.php)

Qué hace:
- calcula micros activos
- cuenta conductores disponibles
- cuenta recorridos activos
- cuenta micros fuera de servicio

Se usa `count()` sobre consultas `where(...)`.

## 3. Estructura de la base de datos que soporta esas métricas

Las tablas que se agregan y relacionan están definidas en las migraciones:

- [database/migrations/2026_06_13_152645_create_asignacion_turnos_table.php](../database/migrations/2026_06_13_152645_create_asignacion_turnos_table.php)
- [database/migrations/2026_04_15_000005b_create_parada_ruta_table.php](../database/migrations/2026_04_15_000005b_create_parada_ruta_table.php)
- [database/migrations/2026_04_15_000005_create_ruta_table.php](../database/migrations/2026_04_15_000005_create_ruta_table.php)
- [database/migrations/2026_04_15_000005a_create_paradas_table.php](../database/migrations/2026_04_15_000005a_create_paradas_table.php)

Y sus modelos relevantes:

- [app/Models/AsignacionTurno.php](../app/Models/AsignacionTurno.php)
- [app/Models/Ruta.php](../app/Models/Ruta.php)
- [app/Models/Parada.php](../app/Models/Parada.php)

## 4. ¿Por qué algunas agregaciones se ven complicadas?

Porque no se implementaron como una sola función SQL general, sino como una serie de consultas de negocio con sentido operativo. Es decir:

- una agregación por día,
- otra por ruta,
- otra por estado,
- otra por monitoreo en vivo.

Esto es correcto para una aplicación de operación y trazabilidad, pero no sigue el patrón de un “solo query de agregación central”.

## 5. ¿Se pueden hacer desaparecer los rombos en DBeaver?

No como “eliminar agregaciones del sistema”, porque los rombos no son agregaciones. Son elementos visuales del diagrama de relaciones o cardinalidad del ERD.

Si el docente se refiere a que "las agregaciones son complicadas", la respuesta técnica correcta es:

- las agregaciones existen como conteos y totales del negocio,
- se implementaron en Laravel,
- no están como funciones en la base de datos,
- y los símbolos del diagrama ER de DBeaver no representan ese nivel de lógica.

## 6. Guion breve para defenderlo ante el jurado

> La base de datos del sistema no tiene una única vista o función de agregación central; las métricas se implementaron en la lógica de la aplicación en Laravel. El proyecto calcula totales operativos como turnos cubiertos, conductores libres, unidades en ruta e incidencias abiertas desde los controladores, especialmente en AsignacionTurnoController, RutaController y MonitoreoController. Además, el conteo de paradas por ruta se realiza con `withCount('paradas')`, una agregación Eloquent que genera `COUNT` en SQL. Los rombos que aparecen en DBeaver corresponden a la representación visual del diagrama entidad-relación y no representan agregaciones de base de datos. Por tanto, la lógica de agregación está en la capa de aplicación y en la relación entre tablas, no como un objeto exclusivo de la base de datos.

## 7. Conclusión

La solución implementada es válida y defendible:

- las tablas y relaciones sí existen,
- las métricas sí están en la aplicación,
- las agregaciones principales están en el código Laravel,
- y los rombos de DBeaver son un detalle visual del modelado, no un error de la solución.
