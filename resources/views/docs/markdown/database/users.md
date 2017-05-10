# users

Esta tabla almacena registros de los usuarios creados para acceder a la plataforma

| Campo        | Tipo           | Descripción  |
| ------------- |:-------------:| ----- |
| id      | `int` | Llave primaria autoincremental |
| name | `varchar` | Nombre del usuario |
| email | `varchar` | Email del usuario |
| avatar | `varchar` | Ruta de la imagen asociada al usuario |
| position | `varchar` | Cargo |
| city_id | `int` | Relación a la tabla de ciudades |
| remember_token | `varchar` | Token para guardar sesión del usuario |
| created_at | `timestamp`      |    Fecha |
| updated_at | `timestamp`      |    Fecha |