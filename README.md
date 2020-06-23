#One File Slim Bolierplate

Instlar dependencias:

+ bower install
+ comnposer install

## Recursos

JSON con los todos los profes:

    http://localhost:8080/public/profes.json

### Migraciones

Migraciones con DBMATE - profes MySQL:

    $ dbmate -d "db/migrations/profes/mysql" -s "db/migrations/profes/mysql/schema.sql" -e "PROFES_MYSQL" new <<nombre_de_migracion>>
    $ dbmate -d "db/migrations/profes/mysql" -s "db/migrations/profes/mysql/schema.sql" -e "PROFES_MYSQL" up
    $ dbmate -d "db/migrations/profes/mysql" -s "db/migrations/profes/mysql/schema.sql" -e "PROFES_MYSQL" rollback

---

Fuentes:

+ https://stackoverflow.com/questions/43131888/how-to-render-front-end-pages-in-slim-framework-route
+ https://www.slimframework.com/
+ https://github.com/pepeul1191/slimphp-boilerplate
