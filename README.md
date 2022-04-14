* Notas sobre el proyecto:
+ La encriptación de los audios se debe controlar desde el servicio FileSystem.
+ La BBDD es mejor cambiarla lo menos posible para evitar problemas a futuro.
+ Para arrancar el proyecto y que funcione es necesario lanzar los siguientes comandos.
  - sudo systemctl start postgres (Para linux. Para linux hay que arrancar postgresql)
  - php artisan serve.
+ La lógica que no pertenezca a los controladores debe ir en servicios para que se puedan utilizar en partes diferentes. Por ejemplo la lógica de control de archivos o el login.
  - Un caso de uso puede ser que una vez que hayamos hecho la recogida de archivos que podamos usar los mismos metodos para obtener la foto de perfil o de fondo sin cambiar nada del código.
+ Los metodos deben de ser pequeños y atómicos. Un metodo hace 1 cosa. 
+ Tiene que haber teses unitarios de todos los metodos de los servicios.
+ Tiene que haber teses de integración de todos los metodos de los controladores.
+ El funcionamiento de la API REST va a ser el siguiente.
** Get Audio via URL
1) Se solicita mediante URL un audio mediante petición GET.
2) La url debe de tener el siguiente formato XXXXX-XXXXX-XXXXX, la llamaremos shortUrl.
3) Se busca en la base de datos por esa shortUrl. Se obtienen todos los detalles del audio.
4) Se obtiene de local o servidor el audio y se desencripta.
5) Se devuelve el audio indicando en las cabeceras de la respuesta de que se trata de un mpeg.
** Get Posts 
1) Se solicita mediante una petición GET 
2) Devolvemos la información de los posts, la url de los audios y la información necesaria de los usuarios.
** Tareas a avanzar
- Hay que determinar como se van a organizar el resto del funcionamiento de la api. Trabajaremos principalmente con archivos así que tenemos que definir lo siguiente:
  + Como vamos a guardar los audios, en principio la idea es separarlos en distintos ficheros para evitar que haya un gran fichero con todos los audios juntos, hay que determinar un tamaño máximo.
  + Que condiciones vamos a poner para validar las llamadas, cuales restringiremos y cuales no.
  + Que modelos y controladores vamos a tener. No es necesario hacer un controlador por modelo, algunos controladores van a requerir de varios modelos para generar una respuesta.
 + Hay que crear teses de todo.
