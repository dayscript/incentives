# Elimina un valor a una entidad

Este servicio permite eliminar una valor para una regla, a una entidad. Se debe proveer el id del valor.

`GET /api/entities/{identification}/delvalue/{id}`

`identification`: Número de identificación único de la entidad o usuario/persona. En caso de no existir, se crea el registro en la base de datos.
`id`: Identificador del valor a eliminar
