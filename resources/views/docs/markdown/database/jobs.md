# jobs

Todos los procesos en cola o en procesamiento se guardan en esta tabla

| Campo        | Tipo           | Descripción  |
| ------------- |:-------------:| ----- |
| id      | `int` | Identificador del proceso |
| queue | `varchar` | Cola en la que se encuentra el proceso |
| payload | `text` | Contenido del proceso |
| attempts | `int` | Número de intentos |
| available_at | `timestamp` | Fecha |
| created_at | `timestamp` | Fecha |