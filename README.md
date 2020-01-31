# php-clickhouse-migrator

Migrations for Clickhouse

## Requirements

PHP 7.0 or newer

### Installation

It is available from composer

```bash
composer require khaydarov\php-clickhouse-migrator
```

After installation you can run migrator commands via 
script `./vendor/bin/clickhouse-migrator`

## Usage

When you run the script there will be the list of available commands

```bash
$ ./clickhouse-migrator
Console Tool

Usage:
  command [options] [arguments]

Options:
  -h, --help            Display this help message
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Available commands:
  create    Create new migration file
  help      Displays help for a command
  init      initialize clickhouse migrations
  list      Lists commands
  migrate   Migrate to specific revision of database
  rollback  Rollback to specific version of database
  status    Show status of migrations
```

### Initialization

Each command is executed in the project's root where composer.json
and vendor directory placed.

Before running commands you need to create a config file. It is possible 
to use your own created file or run `init` to create new one.

The command below creates new php config file

```bash
vendor/bin/clickhouse-migrator init -f php
```   

### Configuration

There are two supporting config extensions: YAML and PHP

The config structure

```yaml
default_environment: development

paths:
  migrations: migrations

environments:
  development:
    cluster: local
    host: localhost
    port: 8123
    database: db
    username: user
    password: pass

  staging:
    cluster: stage
    host: stage.host
    port: 8123
    database: db
    username: user
    password: pass

  production:
    cluster: production
    host: production.host
    port: 8123
    database: db
    username: user
    password: pass
```

`default_environment` points to the environment credentials.
This property value used when `-e` is not passed

### Creating new revision

Use the `create` command to create a new revision

```bash
vendor/bin/clickhouse-migrator create RevisionName
``` 

The RevisionName is a class name, so it must be in camel case notation.
Migration file will be like `20200125120301_RevisionName`, where `20200125120301` is ID and the rest is class name.

After running the command the file `20200125120301_RevisionName.php` will be appeared in migration path

```php
<?php

use Khaydarovm\Clickhouse\Migrator\AbstractMigration;

class RevisionName extends AbstractMigration
{
    public function up()
    {
    }

    public function down()
    {
    }
}
```

`up()` method is used for migrations and `down()` for rollbacks.

### Migration status

The `status` command shows the list of executed revisions. Status is `up` when revision is applied or
`down` when it is reverted

## Development

not ready yet

## Tests

not ready yet
