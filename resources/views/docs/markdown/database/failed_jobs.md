# failed_jobs

Procesos que no han sido procesados correctamente en una cola, se almacenan en esta tabla

| Campo        | Tipo           | Descripción  |
| ------------- |:-------------:| ----- |
| id      | `int` | Identificador del proceso |
| connection | `varchar` | Conexión de base de datos usada |
| queue | `varchar` | Cola en la que se encuentra el proceso |
| payload | `text` | Contenido del proceso |
| exception | `text` | Error obtenido |
| failed_at | `timestamp` | Fecha en la que falló |