<?php
namespace Router;

use Handler\ResponseHandler;
use Controller\UserController;

class Router {
    private $userController;

    public function __construct($userController) {
        $this->userController = $userController;
    }

    public function handleRequest($request, $method) {
        // Route for getting all users
        if ($request === '/api/users' && $method === 'GET') {
            return $this->userController->getAllUsers();
        }
        
        // Route for getting user by email
        if (preg_match('/\/api\/user\/email/', $request) && $method === 'GET') {
            $email = $_GET['email'] ?? null;
            if (!$email) {
                return ResponseHandler::validationError('Email parameter is required');
            }
            return $this->userController->getUserByEmail($email);
        }
        
        // Route for getting single user by ID
        if (preg_match('/\/api\/user\/(\d+)/', $request, $matches)) {
            $userId = $matches[1];
            return $this->userController->getUser($userId);
        }

        return ResponseHandler::notFound('Route not found');
    }
}
