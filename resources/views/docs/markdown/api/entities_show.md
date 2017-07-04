# Muestra la entidad y su detalle de puntos

Este servicio permite consultar una entidad por su número de identificación.
Muestra los datos básicos de la entidad y su listado de puntos acumulados y de metas y cumplimientos.

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
    "goalvalues": [
          {
            "id": 1,
            "date": "2017-07-03",
            "value": 1000,
            "real": 850,
            "percentage": 85,
            "percentage_modified": 80,
            "percentage_weighed": 44
          },
          ...
        ],
    "points": [
      {
        "created_at": "2017-05-16 20:12:14",
        "points": 1000,
        "value": 1,
        "description": "Inscripción en el sistema"
      },
      ...
```