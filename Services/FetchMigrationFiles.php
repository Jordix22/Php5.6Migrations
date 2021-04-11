<?php

class FetchMigrationFiles
{
    const MIGRATIONS_PATH = 'Migrations/';
    const VERSION_NAME = 'Version';

    private $migrationsPath;

    public function __construct()
    {
        $this->migrationsPath = __DIR__ . '/../' . self::MIGRATIONS_PATH;
    }

    public function __invoke()
    {
        $rawMigrations = array_diff(scandir($this->migrationsPath), ['..', '.']);
        $migrations = [];
        foreach ($rawMigrations as $migration) {
            $version = $this->getVersion($migration);

            if (!$version) {
                continue;
            }

            $migrations[$version] = [
              'version' => $version,
              'class' => self::VERSION_NAME . $version,
              'php_file' => $migration
            ];
        }
        return $migrations;
    }

    private function getVersion($file)
    {
        preg_match('/\d+/', $file, $version);
        return isset($version[0]) ? $version[0] : null;
    }
}