<?php

require_once __DIR__ . '/vendor/autoload.php';

use Service\UserService;
use Controller\UserController;
use Config\Config;
use Database\Database;
use Handler\ResponseHandler;
use Router\Router;

// Load configuration
Config::load();

// Security headers
header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("Content-Security-Policy: default-src 'self'");
header("Referrer-Policy: strict-origin-when-cross-origin");

// CORS headers
header('Access-Control-Allow-Origin: ' . Config::get('cors.allowed_origins'));
header('Access-Control-Allow-Methods: ' . Config::get('cors.allowed_methods'));
header('Access-Control-Allow-Headers: ' . Config::get('cors.allowed_headers'));
header('Content-Type: application/json');

// Error reporting
ini_set('display_errors', Config::get('security.display_errors'));
ini_set('display_startup_errors', Config::get('security.display_errors'));
error_reporting(E_ALL);

try {
    // Get database connection
    $db = Database::getInstance()->getConnection();
    
    // Initialize services and controllers
    $userService = new UserService($db);
    $userController = new UserController($userService);
    $router = new Router($userController);

    // Handle request
    $request = $_SERVER['REQUEST_URI'];
    $method = $_SERVER['REQUEST_METHOD'];

    // Handle OPTIONS request for CORS
    if ($method === 'OPTIONS') {
        http_response_code(200);
        exit();
    }

    // Route the request
    $result = $router->handleRequest($request, $method);
    
    // Set HTTP status code
    http_response_code($result['code']);
    
    // Output response
    unset($result['code']);
    echo json_encode($result);

} catch (DatabaseException $e) {
    $response = ResponseHandler::serverError('Database connection error');
    http_response_code($response['code']);
    echo json_encode($response);
} catch (Exception $e) {
    $response = ResponseHandler::serverError('An unexpected error occurred');
    http_response_code($response['code']);
    echo json_encode($response);
}
