<?php
namespace Controller;

use Exception\DatabaseException;
use Exception\ValidationException;
use Handler\ResponseHandler;
use Validator\Validator;
use Exception;

class UserController {
    private $userService;

    public function __construct($userService) {
        $this->userService = $userService;
    }

    public function getUser($id) {
        try {
            $validId = Validator::validateId($id);
            $user = $this->userService->findById($validId);
            
            if (!$user) {
                return ResponseHandler::notFound('User not found');
            }
            return ResponseHandler::success($user->toArray());

        } catch (ValidationException $e) {
            return ResponseHandler::validationError($e->getMessage());
        } catch (DatabaseException $e) {
            return ResponseHandler::serverError('Database error occurred');
        } catch (Exception $e) {
            return ResponseHandler::serverError('An unexpected error occurred');
        }
    }

    public function getUserByEmail($email) {
        try {
            $validEmail = Validator::validateEmail($email);
            $user = $this->userService->findByEmail($validEmail);

            if (!$user) {
                return ResponseHandler::notFound('User not found');
            }
            return ResponseHandler::success($user->toArray());

        } catch (ValidationException $e) {
            return ResponseHandler::validationError($e->getMessage());
        } catch (DatabaseException $e) {
            return ResponseHandler::serverError('Database error occurred');
        } catch (Exception $e) {
            return ResponseHandler::notFound($e->getMessage());
        }
    }

    public function getAllUsers() {
        try {
            $users = $this->userService->findAll();
            return ResponseHandler::success($users);

        } catch (DatabaseException $e) {
            return ResponseHandler::serverError('Database error occurred');
        } catch (Exception $e) {
            return ResponseHandler::serverError('An unexpected error occurred');
        }
    }
}
