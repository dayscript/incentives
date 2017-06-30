# entity_rule

Esta tabla almacena los valores obtenidos por una entidad, con respecto a una regla dada.

| Campo        | Tipo           | Descripción  |
| ------------- |:-------------:| ----- |
| id | `int` | Llave primaria autoincremental |
| entity_id | `int` | Llave a la tabla de entidades |
| rule_id | `int` | Llave a la tabla de reglas |
| value | `double` | Valor obtenido por la entidad para la regla |
| points | `double` | Puntos resultantes del cálculo |
| description | `varchar` | Descripción de este registro |
| created_at | `timestamp` | Fecha |
| updated_at | `timestamp` | Fecha |
