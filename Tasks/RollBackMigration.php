<?php

require_once(dirname(__FILE__) . '/../Services/FetchMigrationByVersion.php');
require_once(dirname(__FILE__) . '/../Services/GetMigrationClass.php');
require_once(dirname(__FILE__) . '/../src/Logger.php');

class RollBackMigration
{
    private $getMigrationClassService;
    private $logger;

    public function __construct()
    {
        $this->getMigrationClassService = new GetMigrationClass();
        $this->logger = new Logger();
    }

    public function __invoke($migrationName)
    {
        if (!$migrationName) {
            $this->logger->errorMessage('You need to pass a migration version name');
        }

        $fetchMigrationByVersion = new FetchMigrationByVersion();
        $migrationsToUndo = $fetchMigrationByVersion->__invoke($migrationName);

        foreach ($migrationsToUndo as $migration) {
            $this->executeMigration(
                $this->getMigrationClassService->__invoke($migration),
                $migration['version']
            );
        }
    }

    private function executeMigration($migration, $version)
    {
        $this->logger->infoMessage($version . ' - ' . $migration->getDescription());
        $this->logger->infoMessage($version . ' changes are going to be reverted');
        $migration->down();
        try {
            $migration->execute();
            $migration->delete($version);
            $this->logger->successMessage($version . ' changes has been applied');
        } catch (\Exception $exception) {
            $this->logger->errorMessage($exception->getMessage());
        }
    }
}