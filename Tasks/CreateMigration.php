<?php

require_once(__DIR__ . '/../src/Logger.php');

class createMigration
{
    private $logger;
    const MIGRATION_DIRECTORY = 'Migrations';
    const TEMPLATE_MIGRATIONS = __DIR__ . '/../Template/migrationFile';
    const PERMISSIONS = 0755;


    public function __construct()
    {
        $this->logger = new Logger();
    }

    public function __invoke()
    {
        $this->createMigrationDirectoryIfDoesNotExists();
        $this->createNewMigrationFile();
    }

    private function createMigrationDirectoryIfDoesNotExists()
    {
        $directory = self::MIGRATION_DIRECTORY;
        if (is_dir($directory)) {
            return null;
        }

        mkdir($directory, self::PERMISSIONS, true);
    }

    private function createNewMigrationFile()
    {
        $migrationVersion = 'Version' . date('YmdHis');
        $migrationPathFileName = 'Migrations/' . $migrationVersion . '.php';
        $migrationTemplate = file_get_contents(self::TEMPLATE_MIGRATIONS);
        $migrationTemplate = str_replace('{{name}}', $migrationVersion, $migrationTemplate);

        if (file_put_contents($migrationPathFileName, $migrationTemplate) !== false) {
            $this->logger->successMessage('Migration file created: ' . basename($migrationPathFileName));
        } else {
            $this->logger->errorMessage('Cannot create migration file: ' . basename($migrationPathFileName));
        }
    }
}