<?php
require_once('IDatabase.php');

class Database extends MySQLi implements IDataBase
{
    private $database;

    public function connectToDatabase()
    {
        $config = include(__DIR__ . '/../Configuration/config.php');
        if (!$config['HOST'] || !$config['USERNAME'] || !$config['PASSWORD'] || !$config['DATABASE']){
            return null;
        }

        $this->database = new self($config['HOST'], $config['USERNAME'], $config['PASSWORD'], $config['DATABASE'], null);

        return $this;
    }

    public function fetch($query)
    {
        if (!$query) {
            return null;
        }

        return $this->database->query($query)->fetch_all();
    }

    public function execute($query)
    {
        if (!$query) {
            return null;
        }

        $this->database->prepare($query)->execute();
    }
}