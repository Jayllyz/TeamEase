FROM mysql:latest AS sql

COPY ./conf.d/my.cnf /etc/mysql/conf.d

COPY ./init.sql /docker-entrypoint-initdb.d/
