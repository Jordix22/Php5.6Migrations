<?php

require_once(__DIR__ . '/FetchMigrationsByQuery.php');

class FetchExecutedMigrations
{
    private $fetchMigrationsByQueryService;

    public function __construct()
    {
        $this->fetchMigrationsByQueryService = new FetchMigrationsByQuery();
    }

    public function __invoke()
    {
        $config = include(__DIR__ . '/../Configuration/config.php');
        $schema = isset($config['DATABASE']) ? $config['DATABASE'] : '';

        return $this->fetchMigrationsByQueryService->__invoke("SELECT * FROM $schema.migration_version");
    }
}