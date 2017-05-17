# Prueba de conexión al API

Para realizar una conexión al API, es necesario generar un token personal de acceso.
Para esto se debe iniciar sesión y acceder al menu de Usuarios -> API Tokens

Una vez generado el token, se pueden realizar peticiones al API incluyendo el token en el header adecuado. 
Se recomienda revisar la documentación de [Laravel](https://laravel.com/docs/5.4/passport#passing-the-access-token)

`GET /api/test` debe obtener la respuesta: `API authentication OK`
