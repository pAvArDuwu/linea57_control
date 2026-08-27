# REVISIÓN ARQUITECTÓNICA DEL SDD Y CONEXIÓN DE APIS (LÍNEA 61 / LÍNEA CONTROL)

**Fecha:** 27 de agosto de 2026  
**Documento evaluado:** [`SDD_actualizado_linea_control_v2_revisado.txt`](file:///c:/laragon/www/icontrol-seguimiento-lineas-main/SDD_actualizado_linea_control_v2_revisado.txt)  
**Proyecto Backend:** `c:\laragon\www\icontrol-seguimiento-lineas-main` (Laravel 11+ MVC / API Sanctum)  
**Aplicación Móvil Cliente:** `linea61_app` (`C:\Users\Usuario\AndroidStudioProjects\linea61_app`)

---

## 1. RESUMEN DE LA REVISIÓN DEL SDD

El archivo de especificación y diseño técnico **`SDD_actualizado_linea_control_v2_revisado.txt`** ha sido analizado en profundidad.

### Diagnóstico General:
El SDD v2 revisado es **sólido, coherente y técnicamente correcto** en cuanto a la delimitación de responsabilidades. Sin embargo, para su integración real con la aplicación móvil (`linea61_app`) y el backend MVC existente, se identificaron y tomaron decisiones indispensables que se detallan a continuación.

---

## 2. DECISIONES TÉCNICAS Y ARQUITECTÓNICAS ADOPTADAS

### 2.1 Respeto Estricto de la Arquitectura MVC y Capa de Servicios
Para no sobrecargar ni romper el patrón MVC:
1. **Controladores Delgados (Thin Controllers):** Los controladores Web (`AsignacionTurnoController`) y API (`AsignacionTurnoApi`, `AuthController`) únicamente gestionan el flujo HTTP, validaciones iniciales y formateo JSON/Blade.
2. **Capa de Servicios de Dominio (Service Layer):** Toda la lógica de negocio, validaciones de solapamiento de turnos, disponibilidad de micros/conductores y transiciones de estado (`pendiente` $\rightarrow$ `en_curso` $\rightarrow$ `completado`) residen centralizadas en `App\Services\AsignacionTurnoService`.
3. **Modelos Eloquent Enriquecidos:** Los modelos (`AsignacionTurno`, `Conductor`, `Micro`, `Turno`, `Ruta`, `User`) encapsulan relaciones, scopes (`buscarPorConductor`) y accessors de presentación (`estadoBadge`, `turnoEmoji`), sin efectos colaterales de base de datos en las vistas.

### 2.2 Estandarización de Estados de `AsignacionTurno`
- **Valores en Base de Datos:** `pendiente`, `en_curso`, `completado`, `retrasado`, `cancelado`.
- **Decisión:** Se mantiene `completado` (conforme a la migración oficial `2026_06_13_152645_create_asignacion_turnos_table.php`) y `retrasado`. A nivel de interfaz se maneja mediante badges de presentación amigables.

### 2.3 Normalización de Usuarios y Conductores
- Conforme a `NORMALIZACION_USUARIOS_PERFILES.md`, la tabla `users` es la fuente única de verdad para datos personales (`name`, `apellido`, `telefono`, `ci`, `email`, `password`), mientras que `conductor` conserva el vínculo obligatorio `user_id`, `licencia` y `estado`.
- **Compatibilidad API:** Se garantizó que el modelo `Conductor` mantenga compatibilidad con payloads legados en su fillable y accessors calculados para no romper llamadas externas ni pruebas.

---

## 3. INTEGRACIÓN Y VERIFICACIÓN DE APIS PARA LA APP MÓVIL (`linea61_app`)

Se habilitaron y verificaron los endpoints requeridos por la aplicación móvil del conductor:

| Método | Endpoint | Middleware | Propósito y Respuesta |
| :--- | :--- | :--- | :--- |
| `POST` | `/api/login` | Público | Autenticación con Sanctum. Retorna `access_token`, `token_type` y el objeto `user` con su relación `conductor` y `roles`. |
| `GET` | `/api/me` | `auth:sanctum` | Información del perfil del conductor logueado con datos personales y su `conductor.id`. |
| `GET` | `/api/mis/asignaciones` | `auth:sanctum` | Listado histórico y programado de turnos asignados exclusivamente al conductor autenticado. |
| `GET` | `/api/mis/asignacion-actual` | `auth:sanctum` | Devuelve el turno activo de hoy (`en_curso` o `pendiente`/`retrasado`) para inicio inmediato en la app móvil. |
| `POST` | `/api/mis/asignaciones/{id}/iniciar` | `auth:sanctum` | Inicia el turno: valida que pertenezca al conductor, cambia estado a `en_curso` y marca `hora_salida`. |
| `POST` | `/api/mis/asignaciones/{id}/finalizar` | `auth:sanctum` | Finaliza el turno: valida propiedad, cambia estado a `completado` y marca `hora_llegada`. |

---

## 4. VERIFICACIÓN Y PRUEBAS AUTOMATIZADAS REALIZADAS

Se implementó y ejecutó la suite de pruebas automatizadas en [`tests/Feature/Api/AsignacionTurnoDriverApiTest.php`](file:///c:/laragon/www/icontrol-seguimiento-lineas-main/tests/Feature/Api/AsignacionTurnoDriverApiTest.php):

1. **`test_conductor_can_see_assigned_shifts`:** ✅ Verifica que el conductor autenticado solo ve los turnos asignados a su ID.
2. **`test_conductor_can_get_current_active_or_pending_shift`:** ✅ Verifica la consulta de la asignación diaria activa para la pantalla principal de la app móvil.
3. **`test_conductor_can_start_and_complete_shift`:** ✅ Verifica el ciclo de vida completo: `pendiente` $\rightarrow$ `en_curso` (registrando `hora_salida`) $\rightarrow$ `completado` (registrando `hora_llegada`).
4. **`test_conductor_cannot_start_another_conductors_shift`:** ✅ Verifica la seguridad de que ningún conductor pueda operar o iniciar la asignación de otro conductor (retorna error `422` con mensaje de validación).

### Compatibilidad Multi-Base de Datos (MySQL / SQLite):
Se corrigieron sentencias SQL crudas (`ALTER TABLE ... MODIFY` y `UPDATE ... JOIN`) en las migraciones para que verifiquen `DB::getDriverName() === 'mysql'`, permitiendo que tanto el entorno de producción (MySQL/Laragon) como los tests automatizados (SQLite en memoria) corran sin errores.

---

## 5. GUÍA RÁPIDA DE CONSUMO PARA ANDROID (`linea61_app`)

En Android Studio con Retrofit/OkHttp:

1. **Login:**
   ```http
   POST http://<IP_LARAGON>:8000/api/login
   Content-Type: application/json
   {
       "email": "conductor@linea61.com",
       "password": "tu_password"
   }
   ```
2. **Adjuntar Bearer Token:**
   ```http
   Authorization: Bearer <access_token>
   ```
3. **Obtener Turno del Día:**
   ```http
   GET http://<IP_LARAGON>:8000/api/mis/asignacion-actual
   ```
4. **Iniciar Recorrido:**
   ```http
   POST http://<IP_LARAGON>:8000/api/mis/asignaciones/{id}/iniciar
   ```
5. **Finalizar Recorrido:**
   ```http
   POST http://<IP_LARAGON>:8000/api/mis/asignaciones/{id}/finalizar
   ```
