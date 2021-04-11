<?php

require_once(__DIR__ . '/../Services/FetchExecutedMigrations.php');
require_once(__DIR__ . '/../Services/FetchMigrationFiles.php');

class FetchNotExecutedMigrations
{
    private $fetchExecutedMigrationsService;
    private $fetchMigrationsFiles;

    public function __construct()
    {
        $this->fetchExecutedMigrationsService = new FetchExecutedMigrations();
        $this->fetchMigrationsFiles = new FetchMigrationFiles();
    }

    public function __invoke()
    {
        return $this->getMigrationsNotExecuted(
            $this->fetchExecutedMigrationsService->__invoke(),
            $this->fetchMigrationsFiles->__invoke()
        );
    }

    private function getMigrationsNotExecuted($executedMigrations, $allMigrations)
    {
        $notExecutedMigration = [];
        foreach ($allMigrations as $migration) {
            $version = $migration['version'];
            if (!isset($executedMigrations[$version])) {
                $notExecutedMigration[] = $migration;
            }
        }

        return $notExecutedMigration;
    }
}