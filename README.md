# One File Slim Bolierplate

Instlar dependencias:

+ bower install
+ comnposer install

## Recursos

JSON con los todos los profes:

    http://localhost:8080/public/profes.json

### Migraciones

Migraciones con DBMATE - profes MySQL:

    $ dbmate -d "db/migrations/profes/mysql" -s "db/profes-mysql-schema.sql" -e "PROFES_MYSQL" new <<nombre_de_migracion>>
    $ dbmate -d "db/migrations/profes/mysql" -s "db/profes-mysql-schema.sql" -e "PROFES_MYSQL" up
    $ dbmate -d "db/migrations/profes/mysql" -s "db/profes-mysql-schema.sql" -e "PROFES_MYSQL" rollback

Migraciones con DBMATE - profes Postgres:

    $ dbmate -d "db/migrations/profes/postgres" -s "db/profes-postgres-schema.sql" -e "PROFES_POSTGRES" new <<nombre_de_migracion>>
    $ dbmate -d "db/migrations/profes/postgres" -s "db/profes-postgres-schema.sql" -e "PROFES_POSTGRES" up
    $ dbmate -d "db/migrations/profes/postgres" -s "db/profes-postgres-schema.sql" -e "PROFES_POSTGRES" rollback

Migraciones con DBMATE - pokemons MySQL:

    $ dbmate -d "db/migrations/pokemons/mysql" -s "db/pokemons-mysql-schema.sql" -e "POKEMONS_MYSQL" new <<nombre_de_migracion>>
    $ dbmate -d "db/migrations/pokemons/mysql" -s "db/pokemons-mysql-schema.sql" -e "POKEMONS_MYSQL" up
    $ dbmate -d "db/migrations/pokemons/mysql" -s "db/pokemons-mysql-schema.sql" -e "POKEMONS_MYSQL" rollback

Migraciones con DBMATE - pokemons Postgres:

    $ dbmate -d "db/migrations/pokemons/postgres" -s "db/pokemons-postgres-schema.sql" -e "POKEMONS_POSTGRES" new <<nombre_de_migracion>>
    $ dbmate -d "db/migrations/pokemons/postgres" -s "db/pokemons-postgres-schema.sql" -e "POKEMONS_POSTGRES" up
    $ dbmate -d "db/migrations/pokemons/postgres" -s "db/pokemons-postgres-schema.sql" -e "POKEMONS_POSTGRES" rollback

### Dump y Restore Mysql

    $ mysqldump -u root -p profes > db/profes.sql
    $ mysql -u root -p profes < db/profes.sql

    $ mysqldump -u root -p pokemons > db/pokemons.sql
    $ mysql -u root -p pokemons < db/pokemons.sql



### Dump y Restore Postgres

    $ sudo -u postgres pg_dump local > local.bak
    $ sudo -u postgres psql local < local.bak

---

Fuentes:

+ https://stackoverflow.com/questions/43131888/how-to-render-front-end-pages-in-slim-framework-route
+ https://www.slimframework.com/
+ https://github.com/pepeul1191/slimphp-boilerplate
+ https://github.com/pepeul1191/tutoriales/blob/master/Postgres.md
