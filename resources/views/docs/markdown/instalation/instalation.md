# Instalación

1. Clonar el repositorio (https://github.com/dayscript/incentives.git)
2. Dentro del directorio ejecutar: 
``` 
composer install
```
3. Crear archivo de configuración `.env` en la raiz del proyecto.
4. Crear base de datos vacía y usuario de conexión, y agregar las credenciales al archivo `.env`
5. Una vez instaladas las dependencias, ejecutar:
``` 
php artisan migrate --force --step
``` 
6. *(opcional)* Si se va a desarrollar en esta máquina se deben instalar las dependencias de npm:
```
npm install
```
