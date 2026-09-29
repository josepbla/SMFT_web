# Base de datos del Colegio Santa Maria

Este repositorio contiene los scripts SQL del proyecto para recrear la base de datos del sistema.

## Archivos

- `001_schema.sql`: estructura de tablas del sistema.
- `002_seed.sql`: datos iniciales de configuración y menú.
- `backup_full.sql`: script completo con esquema + datos base para importar en MySQL.

## Importar base de datos

Desde una terminal MySQL:

```bash
mysql -u root -p < database/sql/backup_full.sql
```

O bien desde phpMyAdmin:

1. Crear la base de datos `colegio_santa_maria`.
2. Importar el archivo `database/sql/backup_full.sql`.

## Base actual

La base de datos usada por el proyecto es:

```text
colegio_santa_maria
```
