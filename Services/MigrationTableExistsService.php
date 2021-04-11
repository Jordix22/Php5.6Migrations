<?php

require_once(__DIR__ . '/../src/Database.php');

class MigrationTableExistsService
{
    public function __invoke()
    {
        $connector = new Database();
        $connector->connectToDatabase();
        $result = $connector->fetch($this->getSql());
        return count($result) > 0;
    }

    private function getSql()
    {
        $config = include(__DIR__ . '/../Configuration/config.php');
        $schema = isset($config['DATABASE']) ? $config['DATABASE'] : '';

        return "SELECT * FROM information_schema.tables WHERE table_schema = '$schema' AND table_name ='migration_version' LIMIT 1";
    }
}