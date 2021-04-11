<?php

require_once(__DIR__ . '/Database.php');

class AbstractMigration
{
    private $queries = [];
    private $databaseConnection;
    private $schema;

    public function __construct()
    {
        $this->databaseConnection = new Database();
        $this->databaseConnection->connectToDatabase();
        $config = include(__DIR__ . '/../Configuration/config.php');
        $this->schema = isset($config['DATABASE']) ? $config['DATABASE'] : '';
    }

    public function addSql($query)
    {
        if (!$query) {
            return null;
        }

        $this->queries[] = $query;
    }

    public function execute()
    {
        foreach ($this->queries as $query) {
            $this->databaseConnection->execute($query);
        }
    }

    public function save($version)
    {
        if (!$version) {
            return null;
        }

        $currentTime = date('Y-m-d H:i:s');
        $this->databaseConnection->execute("INSERT INTO $this->schema.migration_version values ($version, '$currentTime')");
    }

    public function delete($version)
    {
        if (!$version) {
            return null;
        }

        $this->databaseConnection->execute("DELETE FROM $this->schema.migration_version WHERE version = '$version'");
    }
}