# Siroko **Senior** Code Challenge

## Breve descripción del proyecto.
Se trata de una plataforma API encargada de la gestión de la compra de los productos de Siroko que se comunicará con un frontend, y posiblemente otros elementos de Siroko, para permitir a los clientes añadir, editar y eliminar productos del carrito de la compra. 

Los clientes también podrán obtener los elementos del carrito.

Finalmente podrán procesar el pago y generar una orden de compra persistente.

## OpenAPI Specification.
Se ha desarrollado una especificación openapi accesible en la misma plataforma: https://localhost/openapi

## Modelado del dominio.
Se han modelado basicamente dos flujos diferentes, uno de gestión del carrito y otro de pago y generación del pedido persistente. 

El flujo de gestión del carrito se ha simplificado dado el alcance del ejercicio y no tenemos control de stock, consultas de disponibilidad, etc. Lo hemos reducido a comandos que solicitan añadir elementos (items) al carrito, que será el _aggregate root_.

En el flujo de __pago__ tenemos el comando que desencadena el pago del carrito actual y un proveedor de pago externo que nos notifica mediante un evento de que el pago se ha realizado correctamente. Por razones de tiempo, dejamos de lado la implementación real de la conexión con este proveedor externo y simularemos el pago mediante un comando. Esto permitirá obtener el evento de carrito procesado y desencadenar la creación del __pedido__.

[Imagen](docs/modelado.jpeg)

## Tecnología utilizada.
Hemos usado de base Symfony 7.3 con docker (dunglas/symfony-docker) y una arquitectura hexagonal separada en bounded context lo que es bastante sencillo pero permite que estemos desacoplados del framework y nos deja agilidad para cambiar a una arquitectura más compleja en el futuro como CQRS si fuera necesario.

Para la base de datos hemos usado PostgreSQL.

Por motivos de tiempo hemos simplificado las tecnologias utilizadas a todos los niveles: comunicación, gestión de eventos, persistencia, ... 

Sería interesante añadir algunas características al sistema:
- Persistir los eventos antes de publicarlos por si hubiera algun problema poder recuperar el estado del sistema
- Usar una cola para la publicación de eventos como rabbitmq
- Debería de modificarse la forma en la que se almacenan los datos en base de datos para usar transacciones, idealmente, una única transacción por "request".
- Securizacion de la API y de la documentación

## Instrucciones para levantar el entorno con `docker -compose up`.  
Se ha creado un fichero Makefile con los comandos necesarios para levantar el entorno. La mayoría se explican ellos mismos.

- `make start` para hacer build y up
- `make build`
- `make up`
- `make down`

Para iniciar el proyecto basta con hacer `make start` (o dependiendo de los permisos `sudo make start`)

Para probar el proyecto en http://localhost es necesario hacer las migraciones de la base de datos tras el `make start`:
- `make migrate`

## Comando para lanzar los tests.
Para la parte de tests hemos usado Behat para aceptación y Phpunit para los tests unitarios.

Podemos lanzarlos con el Makefile

- `make test-unit` solo tests unitarios
- `make test-acceptance` solo tests de aceptación
- `make test` tests unitarios y de aceptación

## Readme original

Siroko es una marca que vende productos de deporte - especialmente relacionados con ciclismo y fitness - a través de su plataforma *e -commerce*.

Como parte de la plataforma, necesitamos diseñar una **cesta de compra (carrito)** que permita a cualquier persona interesada comprar de forma **rápida** y **eficiente** y, a continuación, **completar el proceso de pago** generando una **orden**.

El equipo ha decidido que la mejor forma de implementar todo esto es partir de una **API** desacoplada de la UI.

Tu misión consiste en iniciar el desarrollo de ese **carrito + checkout**, que después consumirá la interfaz de usuario.

---

## Requerimientos obligatorios

- Gestión de productos que permita **añadir, actualizar y eliminar** ítems del carrito.
- Obtener productos del carrito.
- **Procesar el pago** (checkout) y generar una **orden** persistente al confirmar la compra.
- El diseño de dominio es libre, siempre que el mismo esté **desacoplado** del framework.

---

## ¿Qué valoramos?

1. **Código limpio, simple y fácil de entender... pero con previsión de escala.**  
2. **Conocimientos de Arquitectura Hexagonal y DDD**. Aplicar entidades, agregados, value objects, eventos de dominio, etc...  
3. **Soltura aplicando CQRS.**  
4. **Testing exhaustivo**: máxima cobertura de casos de uso.  
5. **Time to market**: preferimos una solución fácil de evolucionar a la perfección académica.  
6. **Performance, performance, performance**...medida y justificada con datos.  
7. No valoraremos la **UI**; concéntrate en la **API**.  
8. Symfony framework, dominio desacoplado del mismo.  
9. Uso sólido de **Git** y un historial de commits comprensible (feature branches + PR).  

Si algo no está claro, **pregunta**.

---

## Entrega

Sube el código a un repositorio público y añade en el **README**:

- Breve descripción del proyecto.
- OpenAPI Specification.
- Modelado del dominio.
- Tecnología utilizada.
- Instrucciones para levantar el entorno con `docker -compose up`.  
- Comando para lanzar los tests.

---

*¡Manos a la obra y suerte!*