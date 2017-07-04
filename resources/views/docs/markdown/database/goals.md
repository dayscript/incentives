# goals

Esta tabla almacena las metas creadas en el sistema

| Campo        | Tipo           | Descripción  |
| ------------- |:-------------:| ----- |
| id | `int` | Llave primaria autoincremental |
| name | `varchar` | Nombre de la meta |
| description | `varchar` | Descripción de la meta |
| modifier | `varchar` | Modificador de cálculo |
| client_id | `int` | Llave a la tabla de clientes |
| created_at | `timestamp` | Fecha |
| updated_at | `timestamp` | Fecha |
