# Normalización de usuarios, conductores y propietarios

## Implementación

- Se añadió `apellido`, `telefono` y `ci` a `users`: la cuenta de Seguridad es la fuente de datos personales.
- Se añadió `licencia` a `conductor`; los perfiles conservan `estado` y su vínculo `user_id`.
- Los datos antiguos de perfiles vinculados se copian a campos vacíos de su usuario durante la migración. Las columnas históricas se mantienen nullable para no perder registros previos aún no vinculados.
- Conductores y propietarios usan `user_id` obligatorio y único por perfil. El mismo usuario puede existir una vez en cada perfil.
- Los formularios de Usuario permiten registrar apellido, CI y teléfono. Los formularios de perfil muestran los datos del usuario seleccionado en modo solo lectura.
- Las relaciones Eloquent exponen los datos personales del usuario y conservan un fallback a columnas históricas, por compatibilidad con Micros y Asignaciones existentes.

## Archivos modificados

- `database/migrations/2026_08_25_000001_normalize_personal_data_in_users.php`: estructura y migración de datos.
- `app/Models/User.php`, `Conductor.php`, `Propietario.php`: relaciones, campos permitidos y compatibilidad.
- `app/Http/Controllers/UserController.php`, `ConductorController.php`, `PropietarioController.php`: validación y carga MVC.
- `app/Http/Requests/PropietarioRequest.php`: validación del vínculo con usuario.
- `resources/views/users/create.blade.php`, `users/edit.blade.php`: captura de datos personales.
- `resources/views/conductor/create.blade.php`, `conductor/edit.blade.php`, `propietario/form.blade.php`: selector y datos solo lectura.

## Registros anteriores

Los perfiles anteriores sin `user_id` se preservan con sus datos heredados. Al editarlos, se debe seleccionar una cuenta de Seguridad; desde entonces los datos personales se leerán de esa cuenta. No se crean cuentas automáticamente para no asignar contraseñas ni acceso sin autorización.
