<?php
namespace Service;

use Exception\DatabaseException;
use Model\User;
use mysqli;
use Database\QueryBuilder;

class UserService
{
    private $db;
    private $queryBuilder;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
        $this->queryBuilder = new QueryBuilder($db);
    }

    public function findById($id)
    {
        try {
            $result = $this->queryBuilder
                ->select('users')
                ->where('id', $id)
                ->execute();

            if ($result->num_rows === 0) {
                return null;
            }

            $userData = $result->fetch_assoc();
            return new User($userData['id'], $userData['email'], $userData['name']);
        } catch (\Exception $e) {
            throw new DatabaseException($e->getMessage());
        }
    }

    public function findByEmail($email)
    {
        try {
            $result = $this->queryBuilder
                ->select('users')
                ->where('email', $email)
                ->execute();

            if ($result->num_rows === 0) {
                return null;
            }

            $userData = $result->fetch_assoc();
            return new User($userData['id'], $userData['email'], $userData['name']);
        } catch (\Exception $e) {
            throw new DatabaseException($e->getMessage());
        }
    }

    public function findAll()
    {
        try {
            $result = $this->queryBuilder
                ->select('users', ['id', 'name', 'email'])
                ->execute();

            $users = [];
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
            
            return $users;
        } catch (\Exception $e) {
            throw new DatabaseException("Database error: " . $e->getMessage());
        }
    }
}
