# rules

Esta tabla almacena las reglas de cálculo simples creadas en el sistema

| Campo        | Tipo           | Descripción  |
| ------------- |:-------------:| ----- |
| id | `int` | Llave primaria autoincremental |
| name | `varchar` | Nombre de la regla |
| description | `varchar` | Descripción de la regla |
| points | `varchar` | Puntos que se obtienen al cumplir la regla |
| client_id | `int` | Llave a la tabla de clientes |
| created_at | `timestamp` | Fecha |
| updated_at | `timestamp` | Fecha |
