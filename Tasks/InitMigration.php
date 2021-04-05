<?php

require_once(dirname(__FILE__) . '/../src/Logger.php');
require_once(dirname(__FILE__) . '/../src/Database.php');
require_once(dirname(__FILE__) . '/../Services/MigrationTableExistsService.php');

class InitMigration
{
    private $logger;
    private $connector;

    public function __construct()
    {
        $this->logger = new Logger();
        $this->connector = new Database();
    }

    public function __invoke()
    {
        $migrationTableAlreadyExistsService = new MigrationTableExistsService();

        if ($migrationTableAlreadyExistsService()) {
            $this->logger->errorMessage("The migrations table already exists.");
        }

        try {
            $this->connector
                ->connectToDatabase()
                ->execute($this->getSqlToCreateMigrationsTable());
        } catch (\Exception $exception) {
            $this->logger->errorMessage($exception->getMessage());
        }
    }

    private function getSqlToCreateMigrationsTable()
    {
        $config = include(dirname(__FILE__) . '/../Configuration/config.php');
        $schema = isset($config['DATABASE']) ? $config['DATABASE'] : '';
        return "CREATE TABLE `$schema`.`migration_version` (
            `version` VARCHAR(14) NOT NULL,
            `executed_at` DATETIME NOT NULL,
            PRIMARY KEY (`version`)
        )";
    }
}