# entity_goal

Esta tabla almacena los valores definidos para una entidad, con respecto a una meta dada.

| Campo        | Tipo           | Descripción  |
| ------------- |:-------------:| ----- |
| id | `int` | Llave primaria autoincremental |
| entity_id | `int` | Llave a la tabla de entidades |
| goal_id | `int` | Llave a la tabla de metas |
| value | `double` | Valor de la meta para esta entidad |
| date | `date` | Fecha (mes) correspondiente a este valor de meta |
| created_at | `timestamp` | Fecha |
| updated_at | `timestamp` | Fecha |
