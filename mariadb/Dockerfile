FROM mariadb:10.7.3-focal
WORKDIR /

COPY ./mariadb/db /var/lib/mysql

# Copia el script SQL al directorio de inicialización
COPY ./contabilidad/docs/rexy_contabilidad.sql /docker-entrypoint-initdb.d/

EXPOSE 3306