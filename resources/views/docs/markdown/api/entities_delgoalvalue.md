# Elimina un valor de una meta a una entidad

Este servicio permite eliminar una valor para una meta, a una entidad. Se debe proveer el id del valor.

`GET /api/entities/{identification}/delgoalvalue/{id}`

`identification`: Número de identificación único de la entidad o usuario/persona. 
En caso de no existir, se crea el registro en la base de datos.

`id`: Identificador del valor a eliminar
