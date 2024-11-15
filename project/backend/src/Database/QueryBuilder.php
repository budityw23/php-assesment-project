<?php

namespace Database;

use Exception\DatabaseException;
use mysqli;

class QueryBuilder
{
    private $db;
    private $query;
    private $params = [];
    private $types = '';

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function select(string $table, array $columns = ['*']): self
    {
        $this->query = "SELECT " . implode(', ', $columns) . " FROM " . $table;
        return $this;
    }

    public function where(string $column, $value, string $operator = '='): self
    {
        $this->query .= " WHERE {$column} {$operator} ?";
        $this->params[] = $value;
        $this->types .= $this->getParamType($value);
        return $this;
    }

    public function execute()
    {
        $stmt = $this->db->prepare($this->query);
        if (!$stmt) {
            throw new DatabaseException("Failed to prepare statement: " . $this->db->error);
        }

        if (!empty($this->params)) {
            $stmt->bind_param($this->types, ...$this->params);
        }

        if (!$stmt->execute()) {
            throw new DatabaseException("Failed to execute query: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $stmt->close();
        
        return $result;
    }

    private function getParamType($value): string
    {
        switch (gettype($value)) {
            case 'integer': return 'i';
            case 'double': return 'd';
            case 'string': return 's';
            default: return 's';
        }
    }
}
