<?php

namespace Database;

use mysqli;
use Exception\DatabaseException;
use Config\Config;

class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        try {
            $this->connection = new mysqli(
                Config::get('db.host'),
                Config::get('db.user'),
                Config::get('db.pass'),
                Config::get('db.name')
            );

            if ($this->connection->connect_error) {
                throw new DatabaseException("Connection failed: " . $this->connection->connect_error);
            }

            $this->connection->set_charset(Config::get('db.charset'));
        } catch (\Exception $e) {
            throw new DatabaseException("Database connection error: " . $e->getMessage());
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): mysqli
    {
        return $this->connection;
    }

    public function beginTransaction()
    {
        $this->connection->begin_transaction();
    }

    public function commit()
    {
        $this->connection->commit();
    }

    public function rollback()
    {
        $this->connection->rollback();
    }

    public function __destruct()
    {
        if ($this->connection) {
            $this->connection->close();
        }
    }
}
