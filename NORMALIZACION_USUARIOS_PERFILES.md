# SDD.md
# Sistema Web para el Control de Turnos y Seguimiento de Rutas
# Línea 61

> Especificación técnica para desarrollo asistido por agentes.
> Este documento define arquitectura, dominio, reglas de implementación,
> relaciones, flujo de datos, API, UI/UX técnica y restricciones.
>
> Este documento NO es documentación académica y NO reemplaza:
> - casos de uso;
> - requisitos funcionales;
> - modelo entidad-relación académico;
> - tabla de requerimientos;
> - documentación de presentación.

---

# 1. PROPÓSITO

Este documento define las reglas que debe seguir el agente de desarrollo
al analizar, modificar o implementar funcionalidades del sistema de control
de turnos y seguimiento de recorridos de la Línea 61.

El objetivo principal es evitar modificaciones arbitrarias, duplicación de
lógica, inconsistencias entre la base de datos y el código y cambios que
rompan funcionalidades existentes.

Antes de implementar una modificación, el agente debe comprender:

- arquitectura existente;
- modelo de datos;
- relaciones Eloquent;
- flujo de negocio;
- Services involucrados;
- APIs afectadas;
- Views afectadas;
- aplicación móvil afectada;
- pruebas existentes.

---

# 2. REGLA PRINCIPAL

El código existente debe inspeccionarse antes de realizar modificaciones.

El agente NO debe asumir que una estructura descrita en este documento
ya existe exactamente de la misma forma en el repositorio.

Antes de modificar una funcionalidad:

1. localizar los modelos involucrados;
2. localizar las migraciones;
3. revisar las relaciones Eloquent;
4. revisar Controllers;
5. revisar Form Requests;
6. revisar Services;
7. revisar rutas web;
8. revisar rutas API;
9. revisar Views;
10. revisar componentes Flutter relacionados;
11. revisar pruebas existentes;
12. identificar dependencias e impactos.

Si existe una contradicción entre el código y esta especificación:

- identificar la contradicción;
- indicar los archivos afectados;
- explicar el impacto;
- no eliminar datos, relaciones o funcionalidades automáticamente;
- proponer la modificación antes de ejecutarla cuando implique
  cambios estructurales.

---

# 3. STACK TECNOLÓGICO

## Backend

- Laravel
- PHP
- Eloquent ORM
- MySQL
- Laravel Sanctum
- Laravel Reverb/WebSockets cuando corresponda

## Aplicación web

- Laravel Blade
- HTML
- CSS
- JavaScript cuando corresponda

## Aplicación móvil

- Flutter
- API REST Laravel
- GPS del dispositivo

## Arquitectura

- MVC
- Services para lógica de negocio
- Form Requests para validación
- Eloquent para persistencia y relaciones
- API REST para comunicación con Flutter

---

# 4. ARQUITECTURA GENERAL

La arquitectura principal sigue:

    Route
       ↓
    Controller
       ↓
    Form Request
       ↓
    Service
       ↓
    Model / Eloquent
       ↓
    Database

Para la aplicación móvil:

    Flutter
       ↓
    HTTP Request
       ↓
    API Route
       ↓
    Middleware
       ↓
    API Controller
       ↓
    Form Request
       ↓
    Service
       ↓
    Model / Eloquent
       ↓
    Database

La aplicación Flutter NUNCA debe conectarse directamente a MySQL.

---

# 5. RESPONSABILIDADES MVC

## 5.1 Model

Los Models representan entidades y relaciones Eloquent.

Responsabilidades:

- representar tablas;
- definir fillable/guarded;
- definir casts;
- definir relaciones;
- scopes cuando correspondan;
- comportamiento relacionado directamente con el modelo.

No colocar en el Model procesos de negocio complejos que deban pertenecer
a un Service.

---

## 5.2 Controller

El Controller coordina la solicitud HTTP.

Responsabilidades:

- recibir la petición;
- obtener el usuario autenticado;
- utilizar Form Requests;
- autorizar cuando corresponda;
- llamar al Service;
- devolver View o respuesta JSON.

No colocar lógica de negocio compleja en Controllers.

Evitar:

- consultas complejas;
- cálculos de distancia;
- reglas de disponibilidad;
- lógica de recorrido;
- procesamiento complejo de estados.

Estas responsabilidades deben delegarse al Service correspondiente.

---

## 5.3 Form Request

Los Form Requests validan la estructura y formato de los datos recibidos.

Ejemplos:

- required;
- exists;
- date;
- numeric;
- boolean;
- rangos;
- formatos;
- valores permitidos.

La validación de autorización y reglas de negocio complejas no debe
duplicarse innecesariamente en el Request y en el Service.

El Service sigue siendo responsable de las reglas de negocio.

---

## 5.4 Service

Los Services contienen la lógica de negocio.

Responsabilidades:

- validaciones de negocio;
- disponibilidad;
- transiciones de estado;
- creación y actualización de operaciones;
- procesamiento GPS;
- control del recorrido;
- coordinación de modelos;
- transacciones ACID;
- coordinación de eventos cuando corresponda.

Services principales:

- AsignacionTurnoService
- SeguimientoGpsService
- ControlRecorridoService

---

## 5.5 View

Las Views se encargan exclusivamente de presentación.

No deben contener:

- consultas directas a la BD;
- reglas de negocio;
- cálculos de dominio;
- procesamiento GPS;
- reglas de autorización complejas.

La View recibe los datos preparados por Controller/Service.

---

# 6. PRINCIPIOS DE BASE DE DATOS

La base de datos es una fuente de verdad para las relaciones
implementadas.

Antes de modificar una FK:

1. revisar la migración;
2. revisar las migraciones posteriores;
3. revisar el Model;
4. revisar las relaciones Eloquent;
5. revisar Services;
6. revisar Controllers;
7. revisar consultas;
8. revisar APIs;
9. revisar Views;
10. revisar Flutter si corresponde.

No eliminar una FK solamente porque parezca redundante.

No agregar una FK solamente para facilitar una consulta.

Toda modificación estructural debe tener una justificación de dominio.

---

# 7. ENTIDADES PRINCIPALES

El sistema utiliza principalmente:

- User
- Propietario
- Conductor
- Micro
- Interno
- Ruta
- Parada
- RutaParada
- Turno
- AsignacionTurno
- ControlRecorrido
- SeguimientoGps

---

# 8. PARAMETRIZACIÓN

Las entidades de parametrización representan información relativamente
estable que posteriormente será utilizada por las operaciones.

Principalmente:

- Turno
- Ruta
- Parada
- RutaParada
- Micro
- Interno

Estas entidades no representan por sí mismas la ejecución de un recorrido.

---

# 9. TURNO

Turno representa el periodo operativo.

Valores actuales:

- mañana
- tarde
- noche

Turno NO representa el estado de ejecución de una asignación.

El estado de ejecución pertenece a AsignacionTurno.

---

# 10. RUTA

Ruta representa el recorrido lógico.

Ruta no debe duplicarse solamente para representar el sentido de circulación.

El sentido operativo de las paradas se maneja mediante RutaParada.

---

# 11. RUTAPARADA

RutaParada representa una parada dentro de una ruta y define el recorrido
esperado.

Campos conceptuales:

- id
- ruta_id
- parada_id
- sentido
- orden
- estado

Valores de sentido:

- Ida
- Vuelta

Conceptualmente:

    RutaParada = recorrido esperado

Una ruta puede tener diferentes secuencias de RutaParada según el sentido.

No crear una segunda copia de las entidades Parada solamente por utilizar
el sentido Ida/Vuelta.

RutaParada es parametrización.

No debe modificarse cada vez que un micro realiza un recorrido.

---

# 12. ASIGNACION DE TURNO

AsignacionTurno representa una operación concreta de servicio.

Representa:

    fecha
    +
    turno
    +
    ruta
    +
    micro
    +
    conductor

Campos principales:

- id
- fecha
- turno_id
- ruta_id
- micro_id
- conductor_id
- hora_salida
- hora_llegada
- estado
- observaciones
- timestamps

Estados posibles actualmente:

- pendiente
- en_curso
- completado
- retrasado
- cancelado

La implementación debe respetar los estados realmente existentes en
el código y migraciones antes de modificar valores.

---

# 13. CREACIÓN DE ASIGNACION

La creación de una asignación debe ejecutarse desde:

    AsignacionTurnoService

La operación debe validar, cuando corresponda:

1. turno existente;
2. turno activo;
3. ruta existente;
4. ruta activa;
5. micro existente;
6. micro activo;
7. conductor existente;
8. conductor activo;
9. disponibilidad;
10. ausencia de una asignación equivalente activa.

Al crear una asignación:

    estado = pendiente

No solicitar manualmente al usuario:

- estado;
- hora_salida;
- hora_llegada.

Estas propiedades son determinadas por el flujo de ejecución.

---

# 14. INICIO DE ASIGNACION

El inicio del turno es una operación sobre AsignacionTurno.

No crear una tabla independiente para InicioTurno mientras
AsignacionTurno pueda representar el estado y la hora de inicio.

Flujo:

    pendiente
        ↓
    en_curso

Al iniciar:

    hora_salida = hora actual del servidor

La operación debe comprobar:

- usuario autenticado;
- usuario autorizado;
- asignación correspondiente al conductor;
- estado válido para iniciar.

---

# 15. CONTROL DE RECORRIDO

ControlRecorrido representa la sesión o viaje de control del recorrido
asociado a una asignación de turno.

Su función es agrupar la ejecución operativa del micro en ruta:

- las posiciones geográficas continuas registradas (`SeguimientoGps`);
- el cumplimiento progresivo de las paradas programadas (`DetalleControlRecorrido`).

Relaciones principales:

    AsignacionTurno
          1
          |
          N
    ControlRecorrido
          1
          |
          N
    SeguimientoGps

    ControlRecorrido
          1
          |
          N
    DetalleControlRecorrido (vinculado a RutaParada)

---

# 16. MODELO DE CONTROL DE RECORRIDO

La relación arquitectónica establecida para el sistema es:

    AsignacionTurno
          ↓ 1:N
    ControlRecorrido
          ↓ 1:N
    SeguimientoGps

Y los cumplimientos de paradas se registran como detalles del recorrido:

    ControlRecorrido ─────── 1:N ───────> DetalleControlRecorrido
                                                 |
                                                 | N:1
                                                 ↓
                                             RutaParada

Al iniciar el turno, se genera el registro cabecera de `ControlRecorrido`.
Todos los puntos de `SeguimientoGps` capturados durante el viaje se asocian
a ese `ControlRecorrido` mediante `control_recorrido_id`.
Cuando un punto GPS cumple la proximidad a una parada dentro de la tolerancia (80m),
se registra un `DetalleControlRecorrido` asociado a la parada cumplida.

Por lo tanto:

    AsignacionTurno
        hasMany ControlRecorrido (FK: asignacion_turno_id)
        hasOne controlRecorrido (activo o más reciente)
        hasManyThrough controlesRecorrido (a DetalleControlRecorrido)

    ControlRecorrido
        belongsTo AsignacionTurno (FK: asignacion_turno_id)
        hasMany SeguimientoGps (FK: control_recorrido_id)
        hasMany detalles (DetalleControlRecorrido)

    SeguimientoGps
        belongsTo ControlRecorrido (FK: control_recorrido_id)
        belongsTo AsignacionTurno (FK: asignacion_turno_id)
        hasOne detalleControlRecorrido (si cumplió parada)

    DetalleControlRecorrido
        belongsTo ControlRecorrido (FK: control_recorrido_id)
        belongsTo RutaParada (FK: ruta_parada_id)
        belongsTo SeguimientoGps (FK: seguimiento_gps_id)

---

# 17. ESTRUCTURA DE TABLAS

    control_recorrido (Cabecera de la sesión de recorrido)
    -----------------
    id
    asignacion_turno_id  (FK -> asignacion_turnos.id)
    fecha_hora_inicio    (datetime)
    fecha_hora_fin       (datetime, nullable)
    fecha_hora           (datetime)
    estado               (enum: en_curso, completado, cancelado)
    observacion          (text, nullable)
    created_at
    updated_at

    seguimiento_gps (Ubicaciones continuas del micro)
    -----------------
    id
    control_recorrido_id (FK -> control_recorrido.id)
    asignacion_turno_id  (FK -> asignacion_turnos.id, nullable)
    latitud              (decimal 10,8)
    longitud             (decimal 11,8)
    velocidad            (decimal 8,2)
    fecha_hora_gps       (datetime)
    fecha_hora_sincronizacion (datetime, nullable)
    created_at
    updated_at

    detalle_control_recorrido (Paradas cumplidas / evaluadas)
    -----------------
    id
    control_recorrido_id (FK -> control_recorrido.id)
    ruta_parada_id       (FK -> parada_ruta.id, nullable)
    seguimiento_gps_id   (FK -> seguimiento_gps.id, nullable)
    fecha_hora           (datetime)
    estado               (enum: pendiente, cumplido, omitido, fuera_ruta)
    distancia_metros     (decimal 8,2, nullable)
    observacion          (text, nullable)
    created_at
    updated_at

---

# 18. RELACIONES ELOQUENT

## ControlRecorrido

Implementación en [`app/Models/ControlRecorrido.php`]:

    public function asignacionTurno(): BelongsTo
    {
        return $this->belongsTo(AsignacionTurno::class, 'asignacion_turno_id');
    }

    public function seguimientosGps(): HasMany
    {
        return $this->hasMany(SeguimientoGps::class, 'control_recorrido_id');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleControlRecorrido::class, 'control_recorrido_id');
    }

---

## SeguimientoGps

Implementación en [`app/Models/SeguimientoGps.php`]:

    public function controlRecorrido(): BelongsTo
    {
        return $this->belongsTo(ControlRecorrido::class, 'control_recorrido_id');
    }

    public function asignacionTurno(): BelongsTo
    {
        return $this->belongsTo(AsignacionTurno::class, 'asignacion_turno_id');
    }

---

## AsignacionTurno

Implementación en [`app/Models/AsignacionTurno.php`]:

    public function controlesRecorridos(): HasMany
    {
        return $this->hasMany(ControlRecorrido::class, 'asignacion_turno_id');
    }

    public function controlRecorrido(): HasOne
    {
        return $this->hasOne(ControlRecorrido::class, 'asignacion_turno_id')->latestOfMany();
    }

    public function seguimientosGps(): HasMany
    {
        return $this->hasMany(SeguimientoGps::class, 'asignacion_turno_id');
    }

    public function controlesRecorrido(): HasManyThrough
    {
        return $this->hasManyThrough(
            DetalleControlRecorrido::class,
            ControlRecorrido::class,
            'asignacion_turno_id',
            'control_recorrido_id',
            'id',
            'id'
        );
    }

---

# 19. SEGUIMIENTO GPS

SeguimientoGps representa una posición real obtenida durante la ejecución
de un ControlRecorrido.

Conceptualmente:

    SeguimientoGps = recorrido observado

Campos principales:

- id
- control_recorrido_id
- latitud
- longitud
- velocidad
- fecha_hora_gps
- fecha_hora_sincronizacion
- timestamps

La posición es obtenida mediante el GPS del dispositivo móvil.

---

# 20. FLUJO GPS

El flujo esperado es:

    GPS del teléfono
          ↓
       Flutter
          ↓
      HTTP API
          ↓
       Laravel
          ↓
    Form Request
          ↓
    SeguimientoGpsService
          ↓
    SeguimientoGps
          ↓
       Database

La aplicación móvil no escribe directamente en MySQL.

---

# 21. FECHA Y SINCRONIZACIÓN GPS

Diferenciar:

    fecha_hora_gps

de:

    fecha_hora_sincronizacion

`fecha_hora_gps` representa el momento en que ocurrió la posición
en el dispositivo.

`fecha_hora_sincronizacion` representa el momento en que el servidor
recibió la información.

No reemplazar `fecha_hora_gps` por la hora de sincronización.

Esto es especialmente importante cuando el dispositivo funciona
temporalmente sin Internet.

---

# 22. FUNCIONAMIENTO OFFLINE

Cuando el dispositivo no tenga Internet:

1. obtener la posición;
2. almacenarla localmente;
3. marcarla como pendiente de sincronización;
4. conservar la fecha_hora_gps original;
5. detectar recuperación de conexión;
6. sincronizar con la API;
7. confirmar la recepción;
8. marcar el registro local como sincronizado.

El mecanismo de almacenamiento local debe evitar duplicar posiciones
durante reintentos.

---

# 23. CONTROL AUTOMÁTICO DEL RECORRIDO

ControlRecorrido debe utilizar:

- la asignación activa;
- RutaParada;
- posiciones GPS;
- sentido;
- orden de las paradas;
- tolerancia configurada.

Conceptualmente:

    RutaParada
        =
    recorrido esperado

    SeguimientoGps
        =
    recorrido observado

    ControlRecorrido
        =
    evaluación del recorrido observado
    contra el recorrido esperado

---

# 24. LÓGICA DE CONTROL

ControlRecorridoService debe ser responsable de:

1. obtener la asignación;
2. obtener la ruta correspondiente;
3. obtener RutaParada;
4. respetar el sentido Ida/Vuelta;
5. respetar el orden de las paradas;
6. identificar la siguiente parada esperada;
7. recibir la posición GPS;
8. calcular la distancia;
9. comparar la posición con la parada;
10. determinar el resultado;
11. registrar el control;
12. evitar duplicar controles;
13. detectar condiciones de finalización;
14. actualizar la asignación cuando corresponda.

La lógica no debe implementarse en Blade.

---

# 25. TOLERANCIA GPS

La tolerancia utilizada para determinar proximidad a una parada debe
estar centralizada en el Service o configuración correspondiente.

No duplicar el valor en:

- Views;
- Controllers;
- JavaScript;
- Flutter;
- consultas SQL.

El valor actualmente documentado en versiones anteriores del SDD es
80 metros.

Antes de cambiarlo:

1. verificar el valor real en el código;
2. justificar el cambio;
3. actualizar la especificación;
4. ajustar las pruebas.

---

# 26. ESTADOS DE CONTROL

Los estados conceptuales de ControlRecorrido son:

- pendiente
- cumplido
- omitido
- fuera_ruta

Estos estados representan resultados del negocio.

No deben confundirse con errores técnicos de la aplicación.

Ejemplo:

    fuera_ruta

es un resultado operativo.

No significa:

    Exception
    HTTP 500
    rollback

---

# 27. FINALIZACIÓN DE ASIGNACIÓN

La asignación puede finalizar cuando se cumplan las condiciones
de negocio establecidas para el recorrido.

Cuando corresponda:

    en_curso
        ↓
    completado

Y:

    hora_llegada = hora actual del servidor

La detección de finalización debe pertenecer al Service correspondiente.

No permitir que una View determine por sí misma que el turno terminó.

---

# 28. TRANSACCIONES DE BASE DE DATOS

Diferenciar dos conceptos:

## Transacción de negocio

Una operación significativa del sistema.

Ejemplos:

- registrar una asignación;
- registrar una posición GPS;
- controlar un recorrido.

## Transacción ACID de base de datos

Mecanismo técnico para ejecutar varias operaciones de BD como una unidad.

En Laravel:

    DB::transaction()

No utilizar:

    "hace INSERT = automáticamente es una transacción de negocio"

como regla absoluta.

El significado de la operación dentro del dominio también debe considerarse.

---

# 29. USO DE DB::TRANSACTION

Cuando una operación requiera atomicidad, la transacción de BD debe
estar en el Service.

Ejemplo conceptual:

    DB::transaction(function () {

        // persistir información necesaria

        // actualizar información relacionada

        // ejecutar reglas necesarias

    });

No colocar `DB::transaction()` en:

- Blade;
- View;
- JavaScript de interfaz;
- Flutter;
- Controller cuando la lógica corresponde al Service.

---

# 30. SEPARACIÓN DE RESPONSABILIDADES

Las responsabilidades conceptuales son:

    AsignacionTurno
        =
    operación diaria que se ejecutará

    SeguimientoGps
        =
    posiciones reales registradas

    RutaParada
        =
    recorrido esperado

    ControlRecorrido
        =
    evaluación/control del recorrido

    Monitoreo
        =
    visualización de información existente

---

# 31. MONITOREO

El monitoreo es una funcionalidad de consulta y visualización.

No debe crear una segunda fuente de verdad para las posiciones.

Puede mostrar:

- posición actual;
- última posición;
- recorrido;
- micro;
- conductor;
- asignación;
- estado;
- paradas;
- incidencias.

El monitoreo consume información generada por las operaciones.

No debe registrar manualmente posiciones GPS.

---

# 32. SEGUIMIENTO GPS EN WEB

La plataforma web puede visualizar el seguimiento GPS.

Esto NO significa que la web obtenga manualmente las coordenadas.

El flujo es:

    Flutter
       ↓
    GPS
       ↓
    API Laravel
       ↓
    SeguimientoGps
       ↓
    Web
       ↓
    Mapa / historial / monitoreo

La web es principalmente una interfaz de supervisión.

---

# 33. WEBSOCKETS / REVERB

Si se utiliza WebSockets/Reverb:

Su función es comunicar cambios de posición al dashboard.

Conceptualmente:

    SeguimientoGps
          ↓
    Evento
          ↓
    WebSocket / Reverb
          ↓
    Dashboard
          ↓
    Actualización del mapa

WebSocket no reemplaza la persistencia de `seguimiento_gps`.

El sistema debe conservar los datos en la base de datos.

Si falla la notificación WebSocket después de guardar correctamente
el dato, no debe revertirse automáticamente la persistencia.

---

# 34. API REST

Flutter se comunica con Laravel mediante API REST.

La aplicación móvil no debe acceder directamente a:

    MySQL

La API debe:

- autenticar;
- autorizar;
- validar;
- procesar;
- persistir;
- responder.

---

# 35. AUTENTICACIÓN

La API protegida utiliza:

    Laravel Sanctum

Las rutas protegidas deben utilizar el mecanismo de autenticación
configurado por Laravel/Sanctum.

El token identifica al usuario autenticado.

---

# 36. AUTORIZACIÓN

Autenticación y autorización son conceptos diferentes.

Autenticación:

    ¿Quién es el usuario?

Autorización:

    ¿Puede este usuario realizar esta operación?

Ejemplo:

Un conductor autenticado no debe poder operar una asignación
que pertenece a otro conductor.

El backend debe comprobar la pertenencia de la asignación.

No confiar únicamente en un ID enviado desde Flutter.

---

# 37. VALIDACIÓN DE API

Los datos enviados desde Flutter deben validarse.

Ejemplo de información GPS:

- control_recorrido_id o contexto de asignación permitido;
- latitud;
- longitud;
- velocidad;
- fecha_hora_gps.

La API debe rechazar datos:

- malformados;
- fuera de rango;
- incompletos;
- no autorizados;
- pertenecientes a otra operación.

---

# 38. RUTAS API

No inventar endpoints nuevos si ya existe una ruta equivalente.

Antes de crear o modificar un endpoint:

1. revisar `routes/api.php`;
2. revisar Controller API;
3. revisar Request;
4. revisar Service;
5. revisar Flutter;
6. verificar consumidores existentes.

Los nombres exactos de endpoints deben tomarse del repositorio actual.

No modificar contratos de API sin analizar el impacto en Flutter.

---

# 39. FLUTTER

Flutter funciona como cliente móvil de la API.

Responsabilidades:

- autenticación;
- consulta de asignaciones;
- inicio de turno;
- obtención GPS;
- almacenamiento offline;
- sincronización;
- finalización;
- presentación de estado al conductor.

Flutter no debe contener reglas de negocio que deban ser confiables
para el servidor.

El servidor Laravel es la autoridad final para:

- autorización;
- estados;
- validaciones críticas;
- persistencia;
- control del recorrido.

---

# 40. UI/UX DE ASIGNACION

La interfaz de asignación debe solicitar únicamente datos que el usuario
debe decidir.

Datos principales:

- fecha;
- turno;
- ruta;
- micro;
- conductor;
- observaciones.

No solicitar manualmente:

- estado inicial;
- hora_salida;
- hora_llegada.

El sistema debe determinar automáticamente esos valores según el flujo.

---

# 41. UI/UX DE SEGUIMIENTO GPS

El seguimiento GPS no debe utilizar un formulario donde el usuario
escriba manualmente:

- latitud;
- longitud;
- velocidad.

La aplicación móvil obtiene esos datos mediante el GPS.

La web puede mostrar:

- mapa;
- posición actual;
- última posición;
- recorrido;
- fecha/hora;
- velocidad;
- estado;
- historial.

---

# 42. UI/UX DE CONTROL DE RECORRIDO

No crear como flujo principal un formulario:

    "Registrar Recorrido"

si el control se genera automáticamente a partir del GPS.

La interfaz debe permitir:

- consultar;
- supervisar;
- visualizar;
- revisar historial;
- revisar estado de paradas;
- revisar incidencias;
- visualizar ubicación.

Ejemplo conceptual:

    CONTROL DE RECORRIDO

    Ruta: R-01
    Micro: M-014
    Conductor: X
    Estado: EN_RUTA

    [ MAPA ]

    PARADAS

    1. Terminal       CUMPLIDO
    2. Av. Principal   CUMPLIDO
    3. Mercado         OMITIDO
    4. Hospital        CUMPLIDO
    5. Universidad     PENDIENTE

Las Views no deben permitir editar arbitrariamente un resultado
generado automáticamente si no existe una regla de negocio que
permita dicha modificación.

---

# 43. MODELO DE USUARIOS

Relaciones conceptuales actuales:

    User
      1
      |
      +---- 0..1 Conductor

    User
      1
      |
      +---- 0..1 Propietario

Un mismo usuario puede tener más de un rol mediante Spatie Permission.

Los datos comunes pertenecen a User cuando corresponda.

Los datos específicos pertenecen al perfil correspondiente.

No duplicar información personal innecesariamente.

---

# 44. ROLES Y PERMISOS

La autorización debe utilizar el mecanismo existente del proyecto.

No crear un segundo sistema de roles paralelo.

Cuando una funcionalidad requiera permisos:

1. identificar el usuario autenticado;
2. comprobar el permiso/rol correspondiente;
3. comprobar además la pertenencia de los recursos cuando aplique.

---

# 45. ESTRUCTURA DE ARCHIVOS

La estructura debe respetar la existente en el repositorio.

Conceptualmente:

    app/
    ├── Models/
    ├── Services/
    ├── Events/
    └── Http/
        ├── Controllers/
        │   └── Api/
        └── Requests/

No crear carpetas alternativas para una misma responsabilidad.

Antes de crear un archivo:

    buscar si ya existe una implementación equivalente.

---

# 46. SERVICES PRINCIPALES

## AsignacionTurnoService

Responsable de:

- crear asignaciones;
- validar disponibilidad;
- iniciar;
- finalizar;
- cancelar cuando corresponda;
- controlar transiciones de estado.

---

## SeguimientoGpsService

Responsable de:

- registrar posiciones;
- registrar lotes;
- validar datos GPS;
- asociar posiciones al ControlRecorrido correcto;
- gestionar sincronización cuando corresponda;
- coordinar el procesamiento posterior.

---

## ControlRecorridoService

Responsable de:

- obtener recorrido esperado;
- procesar RutaParada;
- respetar sentido;
- respetar orden;
- calcular distancia;
- evaluar posición;
- determinar estado;
- evitar duplicados;
- detectar finalización;
- actualizar la asignación cuando corresponda.

---

# 47. REGLAS DE MODIFICACIÓN DEL MODELO

Antes de agregar una tabla nueva, verificar si la funcionalidad puede
representarse mediante:

- AsignacionTurno;
- ControlRecorrido;
- SeguimientoGps;
- RutaParada;
- entidades existentes.

Antes de agregar una FK:

1. identificar la cardinalidad;
2. determinar cuál entidad contiene la relación N;
3. verificar si la FK ya existe indirectamente;
4. analizar integridad referencial;
5. revisar impacto en API;
6. revisar impacto en Flutter;
7. revisar impacto en Views;
8. revisar migraciones.

---

# 48. REGLAS DE CARDINALIDAD

Cuando una entidad tenga muchos registros de otra entidad:

    Padre
       1
       |
       N
       ↓
    Hijos

La FK debe estar normalmente en la entidad del lado N.

Para la asignación, control de recorrido y seguimiento GPS:

    AsignacionTurno
          1
          |
          N
          ↓
    ControlRecorrido
          1
          |
          N
          ↓
    SeguimientoGps

Por lo tanto:

- `control_recorrido.asignacion_turno_id` es la FK que vincula la sesión de viaje con la asignación.
- `seguimiento_gps.control_recorrido_id` es la FK que vincula cada posición física con el recorrido activo.
- `detalle_control_recorrido.control_recorrido_id` registra las paradas evaluadas y cumplidas.

---

# 49. INTEGRIDAD DEL RECORRIDO

Al registrar un SeguimientoGps se debe garantizar que el
ControlRecorrido utilizado corresponda a una operación válida.

Debe evitarse asociar:

    SeguimientoGPS de Asignación A

con:

    ControlRecorrido de Asignación B

La implementación debe validar la coherencia entre las relaciones
antes de persistir datos.

---

# 50. ERRORES TÉCNICOS VS EVENTOS DE NEGOCIO

Diferenciar:

## Error técnico

Ejemplos:

- BD no disponible;
- excepción no controlada;
- fallo de conexión;
- error de programación.

Debe registrarse según las reglas de logging del proyecto.

## Evento de negocio

Ejemplos:

- fuera_ruta;
- omitido;
- retrasado;
- parada pendiente.

Estos son resultados operativos normales.

No deben tratarse automáticamente como excepciones técnicas.

---

# 51. MANEJO DE ERRORES API

Mantener respuestas HTTP coherentes con la implementación existente.

Conceptualmente:

    400
    payload malformado

    401
    usuario no autenticado

    403
    usuario autenticado pero no autorizado

    404
    recurso inexistente

    409
    conflicto de operación

    422
    validación/regla de negocio

    429
    exceso de solicitudes

    5xx
    error interno no controlado

Nunca exponer stack traces al cliente móvil.

---

# 52. IDEMPOTENCIA Y DUPLICADOS GPS

El registro GPS debe considerar reintentos y sincronización.

Un mismo punto no debe registrarse múltiples veces únicamente porque
la aplicación móvil realizó un reintento HTTP.

La estrategia exacta de deduplicación debe utilizar los campos y
mecanismos existentes en el proyecto.

No inventar una clave de deduplicación sin revisar primero la estructura
actual.

---

# 53. PRUEBAS MÍNIMAS

Toda modificación importante debe considerar pruebas.

## Asignación

- crear asignación válida;
- turno inactivo;
- ruta inactiva;
- micro inactivo;
- conductor inactivo;
- conductor ocupado;
- micro ocupado;
- asignación duplicada;
- estado inicial correcto;
- cancelación sin eliminación física cuando corresponda.

## Inicio

- conductor autorizado;
- conductor no autorizado;
- asignación inexistente;
- estado inválido;
- hora de salida automática.

## GPS

- posición válida;
- posición inválida;
- asignación/control inexistente;
- asociación correcta;
- reintento;
- sincronización offline;
- conservación de fecha_hora_gps.

## Control

- parada dentro de tolerancia;
- parada fuera de tolerancia;
- orden de paradas;
- sentido Ida;
- sentido Vuelta;
- duplicación de control;
- última parada;
- finalización cuando corresponda.

---

# 54. REGLAS PARA TESTING DESPUÉS DE CAMBIOS

Después de modificar:

- Model;
- Migration;
- Service;
- API;
- Controller;
- Flutter;
- relaciones;

el agente debe verificar las funcionalidades dependientes.

No asumir que una modificación aislada solamente afecta al archivo editado.

---

# 55. REGLAS DE MIGRACIONES

No modificar una migración histórica si el proyecto ya utiliza migraciones
posteriores para evolucionar el esquema.

Preferir una nueva migración cuando corresponda.

Antes de eliminar una columna:

1. buscar referencias en todo el proyecto;
2. buscar consultas;
3. buscar relaciones;
4. buscar API;
5. buscar Flutter;
6. buscar Views;
7. revisar datos existentes;
8. evaluar rollback.

---

# 56. REGLAS DE ELOQUENT

Las relaciones deben expresar correctamente la cardinalidad.

Ejemplo:

    ControlRecorrido
        hasMany(SeguimientoGps::class)

    SeguimientoGps
        belongsTo(ControlRecorrido::class)

Utilizar nombres de relaciones claros.

No crear relaciones duplicadas que representen el mismo vínculo.

No asumir relaciones solamente por el nombre de una columna.

---

# 57. CONSULTAS

Las consultas complejas relacionadas con el dominio deben permanecer
en Services o en mecanismos apropiados de acceso a datos.

No colocar consultas complejas directamente en Blade.

Evitar consultas repetitivas que provoquen N+1.

Utilizar eager loading cuando corresponda.

Ejemplo conceptual:

    ControlRecorrido::with([
        'asignacionTurno',
        'rutaParada',
        'seguimientosGps'
    ])

La consulta real debe adaptarse al código existente.

---

# 58. REGLA PARA NUEVAS FUNCIONALIDADES

Cuando se solicite una nueva funcionalidad, el agente debe analizar:

    1. Dominio
    2. Base de datos
    3. Model
    4. Service
    5. Controller
    6. Request
    7. API
    8. Flutter
    9. View
    10. Tests

Antes de programar debe indicar qué componentes serán afectados
cuando la modificación tenga impacto transversal.

---

# 59. NO DUPLICAR FUNCIONALIDADES

Antes de crear:

- Controller;
- Service;
- Request;
- Model;
- endpoint;
- View;
- método;

buscar si ya existe uno equivalente.

No crear:

    SeguimientoGpsService2

    ControlRecorridoServiceV2

    NuevaAsignacionController

solamente para evitar modificar el existente.

Si la implementación actual está incorrecta, corregirla de forma
controlada.

---

# 60. REGLA SOBRE EL AGENTE

El agente debe preferir:

    comprender → planificar → modificar → verificar

en lugar de:

    generar código inmediatamente.

Cuando el cambio sea estructural:

1. explicar la modificación;
2. identificar archivos;
3. identificar tablas;
4. identificar relaciones;
5. identificar API;
6. identificar UI;
7. implementar;
8. ejecutar/verificar pruebas.

---

# 61. REGLA SOBRE DOCUMENTACIÓN

Este SDD debe contener únicamente información útil para la implementación.

No agregar aquí:

- introducción académica;
- objetivos académicos;
- situación problemática;
- justificación;
- alcance académico;
- tabla de requerimientos;
- casos de uso completos;
- diagramas UML;
- conclusiones de feria;
- notas de exposición.

Esos elementos pertenecen a la documentación académica del proyecto.

---

# 62. REGLA SOBRE DECISIONES

Cuando una decisión arquitectónica cambie:

1. actualizar este SDD;
2. revisar migraciones;
3. revisar Models;
4. revisar Services;
5. revisar APIs;
6. revisar Flutter;
7. revisar Views;
8. revisar pruebas.

No mantener dos decisiones contradictorias sobre la misma relación.

Este documento debe representar la especificación vigente.

---

# 63. DECISIONES ACTUALES DEL DOMINIO

Las siguientes decisiones deben considerarse vigentes:

### A. Sentido

El sentido operativo se representa en:

    RutaParada

Valores:

    Ida
    Vuelta

No crear rutas duplicadas solamente para representar el sentido.

### B. ControlRecorrido

ControlRecorrido pertenece al contexto de una AsignacionTurno.

### C. ControlRecorrido y SeguimientoGps

ControlRecorrido representa la sesión o viaje del micro en la asignación:

    FK: asignacion_turno_id en control_recorrido

Cada punto de SeguimientoGps pertenece a ese ControlRecorrido:

    FK: control_recorrido_id en seguimiento_gps

Y el cumplimiento de las paradas programadas se registra en DetalleControlRecorrido:

    FK: control_recorrido_id en detalle_control_recorrido
    FK: ruta_parada_id en detalle_control_recorrido
    FK: seguimiento_gps_id en detalle_control_recorrido (evidencia GPS puntual)

La relación es:
- `AsignacionTurno` (1) ── (N) `ControlRecorrido`
- `ControlRecorrido` (1) ── (N) `SeguimientoGps`
- `ControlRecorrido` (1) ── (N) `DetalleControlRecorrido`
- `AsignacionTurno` accede a las paradas cumplidas vía `hasManyThrough`.

### D. Monitoreo

Monitoreo es una función de consulta/visualización.

No es una fuente independiente de datos.

### E. Inicio de turno

No crear una tabla independiente mientras AsignacionTurno pueda
representar correctamente el estado y hora de inicio.

### F. Control manual

No crear un CRUD manual para ControlRecorrido si el resultado debe
ser generado automáticamente por el procesamiento GPS.

---

# 64. FLUJO COMPLETO

El flujo general esperado es:

    PARAMETRIZACIÓN
          ↓
    ASIGNACION_TURNO
          ↓
    INICIO DEL TURNO
          ↓
    CONTROL_RECORRIDO
          ↓
    SEGUIMIENTO_GPS
          ↓
    EVALUACIÓN DEL RECORRIDO
          ↓
    ACTUALIZACIÓN DEL CONTROL
          ↓
    FINALIZACIÓN
          ↓
    MONITOREO / HISTORIAL

El orden exacto de persistencia puede depender de la implementación.

La regla fundamental es mantener la coherencia entre:

    asignación
    control
    GPS
    ruta esperada

---

# 65. TRAZABILIDAD TÉCNICA

## Asignación

    AsignacionTurno
        ↓
    AsignacionTurnoService
        ↓
    asignacion_turnos
        ↓
    UI web
        ↓
    API cuando corresponda

## Control de recorrido

    ControlRecorrido
        ↓
    ControlRecorridoService
        ↓
    control_recorrido
        ↓
    RutaParada
        ↓
    UI de control

## Seguimiento GPS

    GPS dispositivo
        ↓
    Flutter
        ↓
    API
        ↓
    SeguimientoGpsService
        ↓
    seguimiento_gps
        ↓
    ControlRecorrido
        ↓
    Web / mapa / historial

---

# 66. REGLA DE ORO

Ante cualquier solicitud nueva, comprobar primero que encaje
conceptualmente en:

    PARAMETRIZACIÓN
          ↓
    ASIGNACION_TURNO
          ↓
    CONTROL_RECORRIDO
          ↓
    SEGUIMIENTO_GPS
          ↓
    MONITOREO / REPORTES

Si una nueva solicitud no encaja:

1. no implementarla inmediatamente;
2. identificar qué parte del dominio cambia;
3. identificar tablas afectadas;
4. identificar relaciones afectadas;
5. identificar Services afectados;
6. identificar APIs afectadas;
7. identificar Views afectadas;
8. identificar Flutter afectado;
9. actualizar esta especificación;
10. recién después modificar código.

---

# 67. PRINCIPIO FINAL

El agente debe priorizar:

1. integridad de datos;
2. coherencia del dominio;
3. arquitectura existente;
4. seguridad;
5. mantenibilidad;
6. reutilización;
7. pruebas;
8. simplicidad.

No implementar una solución solamente porque sea más rápida de generar.

No modificar la arquitectura existente sin justificarlo.

No introducir una nueva tabla, FK, endpoint o Service si la funcionalidad
puede resolverse correctamente utilizando los componentes existentes.

FIN DEL SDD