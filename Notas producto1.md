**COMANDO PARA CREAR CARPETA**

cd "C:\\Users\\sergio.gomezg\\Desktop\\UOC DAW\\Back End PHP\\Proyecto 1"





**CREAR, CONFIGURAR Y ENCENDER SERVIDOR**

docker run -d -p "80:80" -p "3307:3306" -v ${PWD}/src:/app --name mi-servidor-lamp mattrayner/lamp





**COMPROBAR SI EL SERVIDOR ESTÁ ENCENDIDO**

docker ps  o   docker rm mi-servidor-lamp    //     http://localhost





**Para CONFIGURAR Laravel**

composer create-project laravel/laravel laravel-project  (crear proyecto)



cd laravel-project  (Entrar)



cp .env.example .env  (copiar el archivo en el proyecto)



./vendor/bin/sail up -d

./vendor/bin/sail artisan key:generate     (Generar clave de la aplicación)



./vendor/bin/sail artisan storage:link  (crear enlace para storage)





**Para iniciar LARAVEL**



cd /home/sergiogomezg/laravel-project

./vendor/bin/sail up -d



http://localhost:60643/





**Para iniciar WordPress**

cd /home/sergiogomezg/wordpress-docker

docker-compose up -d



http://localhost:8002/





**WordPress conectado en el servidor**



https://fp064.techlab.uoc.edu/~uocx3/wp-admin/





**Laravel conectado al servidor**

https://fp064.techlab.uoc.edu/~uocx3/laravel/public

