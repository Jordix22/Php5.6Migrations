<?php


class GetMigrationClass
{
    const MIGRATIONS_PATH = '/Migrations/';

    public function __invoke($migration)
    {
        $migrationFile = $migration['php_file'];
        require_once(dirname(__FILE__) . '/..' . self::MIGRATIONS_PATH . $migrationFile);
        $migrationClass = $migration['class'];

        return new $migrationClass();
    }
}