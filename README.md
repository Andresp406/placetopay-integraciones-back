
## Datos de instalacion

Prueba de seleccion Evertec. para la instalacion se debe clonar el repositorio con el comando `git clone https://github.com/andresp406/placetopay-integraciones-back.git` por consola de comando, copiar el archivo .env con el siguiente comando `copy .env.example .env`, luego se debe generar la key por medio del comando `php artisan key:generate`, esta aplicacion usa docker como servidor en el back-end, asi que se puede instalar por medio de composer por el comando `docker-compose up -d nginx mysql workspace`, luego generamos las migraciones por medio del comando `php artisan migrate:fresh --seed` a la vez generamos los seeders de prueba para tener ya datos de pruebas en los endpoint solicitados.

## Instalacion de Postman
Nos dirigimos a la pagina oficial de [Postman](https://www.postman.com/) lo instalamos e importamos el json que adjunto para cargar los endpoint. y de esa manera probarlos antes de la implementacion en la app
- {{url_base}}/api/v1/login
- {{url_base}}/api/v1/register
- {{url_base}}/api/v1/product/all?search
- {{url_base}}/api/v1/me
- {{url_base}}/api/v1/sale/?search
- {{url_base}}/sale/my-sales?search=

## Datos del desarrollo del back-end

para la realizacion del back utilice 2 controladores:
- OrderController: este controlador se encarga de recibir la data del front y hace la comunicacion con la pasarela placetopay ademas, genera los estados de la peticion, ademas guarda en base de datos la data que nos siver para mostrarlo en el table de order en el front

- ProductController: encargado de traer los productos que han sido creados mediante faker.

- WebServiceController: encargado de crear la sesion con placetopay con los datos propocionados secret_key, nonce, trankey ademas de la ip que se va usar en el json de creacion de sesion


cree 2 modelos aparte del modelo por defecto de User:

- model Client cree dos mutators para la fecha y retornala como es requerido y para el FullName ya que por defecto se ingresan aparte, cree un queryScope para el filtro por nombre, ademas de una relacion de uno a muchos con el modelo Sales

- model Sales solo se lleno la propiedad fillable para la asignacion masiva.

En el route service provider cree tres archivos de rutas personalizadas con prefijo api/v1/auth las cuales son:

- routes/api/auth.php
- routes/api/client.php
- routes/api/sales.php

las cuales son las encargadas de enviar los datos a los endpoint solicitados

como buena practica de programacion el back genero responsabilidad a Rules para la autorizacion por el parametro de edad y tambien por el pago pendiente los cuales 

el sistema cuenta con migraciones y sus seeder para generar datos de prueba


