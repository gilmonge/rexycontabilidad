Sistema básico de contabilidad creado por Rexy Studio
========

> __Proyecto realizado por:__
> [Rexy Studios](https://rexystudios.com)

> __Copyright (C):__
> Todos los derechos reservados [Rexy Studios](https://rexystudios.com), 2020

---
Instanciamiento en servidor
--------
 1. Se crea un clon del proyecto estando vacío 
    ```sh
     $ git clone --bare conta conta.git
    ``` 
 2. Se sube al servidor y se crea el __post-receive__
    ```sh
     $ touch post-receive
    ``` 
 3. Se da permisos para ejecución a __post-receive__
    ```sh
     $ chmod u+x post-receive
    ``` 
 4. Se agrega repositorio del servidor a publicar
    ```sh
     $ git remote add centos ssh://root@rexystudios.com:/var/www/git_repository/conta.git
    ```
---
Realizar deploy del proyecto en el servidor
--------
 1. Se comprueban los cambios a realizar
    ```sh
     $ git status
    ```
 2. Se agregan los cambios realizados
    ```sh
     $ git add -A
    ```
 3. Se realiza el commit respectivo
    ```sh
     $ git commit -m  "texto"
    ```
 4. Se realiza la carga al servidor
    ```sh
     $ git push centos master
    ```
 5. En caso de requerir ambiente de pruebas se debe ejecutar el siguiente código
    ```sh
     $ git push centos nuevos_ajustes
    ```
---