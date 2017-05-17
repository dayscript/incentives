# Agregar un valor a una entidad

Este servicio permite agregar una valor para una regla, a una entidad. El sistema calcula inmediatamente los puntos resultantes y asocia el registro a la entidad.

`POST /api/entities/{identification}/addvalue`

`identification`: Número de identificación único de la entidad o usuario/persona. En caso de no existir, se crea el registro en la base de datos.

Dentro de las variables esperadas en la petición POST, están:

- `value` **opcional**. Valor numérico de la cantidad a aplicar. Por defecto = 1
- `rule` **obligatorio**. Identificador numérico de la regla a asociar
- `description` **opcional**. Valor de texto con descripción sobre el registro

El sistema asigna el número de puntos resultante de multiplicar `value` y el numero de `puntos` definidos 
en la regla con id=`rule`, agrega la `description` si existe y guarda la fecha y hora de creación del registro.