# Agregar un valor de una meta a una entidad

Este servicio permite agregar una valor para una meta, a una entidad. 
Se valida la existencia del valor, para una entidad, para una meta, en una fecha, en caso de ya existir, 
se actualizan los valores.

`POST /api/entities/{identification}/addgoalvalue`

`identification`: Número de identificación único de la entidad o usuario/persona. 
En caso de no existir, se crea el registro en la base de datos.

Dentro de las variables esperadas en la petición POST, están:

- `value` **opcional**. Valor numérico de la cantidad de la meta. Por defecto = 1
- `goal` **obligatorio**. Identificador numérico de la meta a asociar
- `date` **opcional**. Valor de fecha de esta meta. (YYYY-MM-DD)
