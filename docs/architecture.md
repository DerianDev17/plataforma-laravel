# Arquitectura de servicios

Este proyecto mantiene la logica de dominio fuera de controladores, Livewire y modelos cuando el flujo empieza a repetirse o toca integraciones externas.

## Servicios actuales

| Servicio | Responsabilidad | Contrato / resultado |
| --- | --- | --- |
| `App\Services\Attendance\AttendanceService` | Registro, marcado manual y revocacion de asistencias | `AttendanceResult` |
| `App\Services\Attendance\Contracts\AttendancePolicy` | Reglas para permitir asistencia | Implementado por `DefaultAttendancePolicy` |
| `App\Services\Audit\AuditLogService` | Escritura y consulta de eventos de auditoria | `AuditLogger` |
| `App\Services\LiveClass\StudentLiveClassAccessService` | Acceso y sincronizacion de estudiantes con proveedor de clases | `LiveClassOperationResult`, `LiveClassSyncResult` |
| `App\Services\Payment\PaymentAccessService` | Normalizacion y evaluacion de estados de pago | `PaymentAccessResult` |
| `App\Services\Registration\UserRegistrationService` | Alta de usuarios via Fortify y auditoria | `User` |
| `App\Services\StudentImport\StudentImportService` | Importacion masiva, borrado faltante, passwords temporales | `StudentImportResult` |
| `App\Services\NotificationService` | Envio centralizado de credenciales o recordatorios de cuenta | `bool` |
| `App\Services\Zoom\ZoomOAuthClient` | Cliente HTTP Zoom Server-to-Server OAuth | `Illuminate\Http\Client\Response` |

## Patron de resultado

Los servicios que ejecutan flujos con estados esperados devuelven value objects pequenos en vez de arrays sueltos. Ejemplos:

- `AttendanceResult`: aprobado, denegado, ya registrado, mensaje.
- `PaymentAccessResult`: estado efectivo, etiqueta, acceso.
- `StudentImportResult`: creados, actualizados, eliminados, emails invalidos, errores.

## Reglas de autenticacion

- El login usa `username` y `password`, no email.
- Las contrasenas temporales se generan con valores aleatorios y se almacenan con `Hash::make`.
- Ningun flujo debe usar el `username` como contrasena.
- Cuando se genera una temporal, el usuario queda con `must_change_password = true`.

## Integraciones externas

- Zoom debe pasar por `ZoomOAuthClient`; el trait JWT fue retirado porque Zoom JWT fue deprecado.
- El registro web ya no lee `Base_Alumnos.xlsx` de forma implicita. Si hace falta sincronizar desde archivo, debe agregarse como comando Artisan o servicio explicito de sincronizacion.
