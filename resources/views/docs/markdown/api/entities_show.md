# Muestra la entidad y su detalle de puntos

Este servicio permite consultar una entidad por su número de identificación.
Muestra los datos básicos de la entidad y su listado de puntos acumulados.

`GET /api/entities/{identification}`

`identification`: Número de identificación único de la entidad o usuario/persona. En caso de no existir, retorna mensaje de error.

```json
{
  "entity": {
    "id": 1,
    "identification": 555555,
    "name": "Pepe Perez",
    "created_at": "2017-05-16 19:39:06",
    "updated_at": "2017-05-16 19:39:06",
    "points": [
      {
        "created_at": "2017-05-16 20:12:14",
        "points": 1000,
        "value": 1,
        "description": "Inscripción en el sistema"
      },
      ...
```