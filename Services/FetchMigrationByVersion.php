<?php

require_once(dirname(__FILE__) . '/FetchMigrationsByQuery.php');

class FetchMigrationByVersion
{
    private $fetchMigrationsByQueryService;
    private $schema;

    public function __construct()
    {
        $config = include(dirname(__FILE__) . '/../Configuration/config.php');
        $this->schema = isset($config['DATABASE']) ? $config['DATABASE'] : '';
        $this->fetchMigrationsByQueryService = new FetchMigrationsByQuery();
    }

    public function __invoke($version)
    {
        if (!$version) {
            return [];
        }

        return $this->fetchMigrationsByQueryService->__invoke("SELECT * from {$this->schema}.migration_version where version = '$version'");
    }
}