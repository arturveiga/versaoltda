FROM mysql:8.0

# MySQL executes this file only when its data volume is empty.
COPY versao-ltda-dev.sql /docker-entrypoint-initdb.d/10-versao-ltda-dev.sql
COPY disable-maintenance.sql /docker-entrypoint-initdb.d/20-disable-maintenance.sql
