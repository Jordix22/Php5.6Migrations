<?php

require_once(dirname(__FILE__) . '/../Services/FetchNotExecutedMigrations.php');
require_once(dirname(__FILE__) . '/../Services/GetMigrationClass.php');
require_once(dirname(__FILE__) . '/../src/Logger.php');

class MigrateMigration
{
    private $fetchNotExecutedMigrationsService;
    private $getMigrationClassService;
    private $logger;

    public function __construct()
    {
        $this->fetchNotExecutedMigrationsService = new FetchNotExecutedMigrations();
        $this->getMigrationClassService = new GetMigrationClass();
        $this->logger = new Logger();
    }

    public function __invoke()
    {
        $notExecutedMigrations = $this->fetchNotExecutedMigrationsService->__invoke();
        if (!$notExecutedMigrations) {
            $this->logger->errorMessage('There are not migrations to execute.');
        }
        foreach ($notExecutedMigrations as $migration) {
            $this->executeMigration(
                $this->getMigrationClassService->__invoke($migration),
                $migration['version']
            );
        }
    }

    private function executeMigration($migration, $version)
    {
        $this->logger->infoMessage($version . ' - ' . $migration->getDescription());
        $this->logger->infoMessage($version . ' is going to be migrated.');
        $migration->up();
        try {
            $migration->execute();
            $migration->save($version);
            $this->logger->successMessage($version . ' changes has been applied');
        } catch (\Exception $exception) {
            $this->logger->errorMessage($exception->getMessage());
        }
    }
}