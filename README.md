# Solati_prueba
## Instrucciones para configurar el proyecto

1. Clonar el repositorio en la carpeta donde tengas el servidor web (por ejemplo, Apache o Nginx), o puedes descargar el proyecto y descomprimirlo en la ubicación deseada.

2. Ejecutar los siguientes comandos para instalar las dependencias del proyecto en backend:

   ```
   cd solati/backend
   composer install
   ```

3. Agregar un archivo `.env` en la carpeta "backend" del proyecto con la siguiente estructura, ajustarlo si se requiere:

   ```plaintext
   HOST=localhost
   PORT=5432
   DB_NAME=db_prueba_solati
   DB_USER=root
   DB_PASSWORD=
   ```

   Asegúrate de completar la información para `DB_PASSWORD` con la contraseña correspondiente si es necesaria.
   La conexion que se utiliza es postgreSQL para tenerla instalada previamente.

Con estos pasos, el proyecto estará configurado y listo para su funcionamiento y conexión a la base de datos.
Solo ingresa al http://localhost/solati/frontend
