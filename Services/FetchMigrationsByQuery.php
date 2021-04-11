<?php

require_once(__DIR__ . '/../src/Database.php');

class FetchMigrationsByQuery
{
    const MIGRATION_NAME_INDEX = 0;
    const VERSION_NAME = 'Version';
    const EXTENSION = '.php';

    private $connector;

    public function __construct()
    {
        $this->connector = new Database();
    }

    public function __invoke($query)
    {
        $rawResult = $this->connector
            ->connectToDatabase()
            ->fetch($query);

        if (count($rawResult) < 1) {
            return [];
        }

        $executedMigrations = [];
        foreach ($rawResult as $result) {
            $version = $result[self::MIGRATION_NAME_INDEX];
            $executedMigrations[$version] = [
                'version' => $version,
                'class' => self::VERSION_NAME . $version,
                'php_file' => self::VERSION_NAME . $version . self::EXTENSION
            ];
        }

        return $executedMigrations;
    }
}