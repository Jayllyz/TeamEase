FROM mysql:latest AS sql

COPY ./conf.d/my.cnf /etc/mysql/conf.d
#create tables from sql file
COPY ./init.sql /docker-entrypoint-initdb.d/
