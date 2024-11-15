<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Controller\UserController;
use Service\UserService;
use Model\User;
use Exception\DatabaseException;
use Exception\ValidationException;

class UserControllerTest extends TestCase
{
    private $userService;
    private $userController;

    protected function setUp(): void
    {
        $this->userService = $this->createMock(UserService::class);
        $this->userController = new UserController($this->userService);
    }

    public function testGetUserSuccess()
    {
        $userId = 1;
        $expectedUser = new User(1, 'test@example.com', 'Test User');

        $this->userService->expects($this->once())
            ->method('findById')
            ->with($userId)
            ->willReturn($expectedUser);

        $result = $this->userController->getUser($userId);

        $this->assertEquals('success', $result['status']);
        $this->assertEquals($expectedUser->toArray(), $result['data']);
        $this->assertEquals(200, $result['code']);
    }

    public function testGetUserNotFound()
    {
        $userId = 999;

        $this->userService->expects($this->once())
            ->method('findById')
            ->with($userId)
            ->willReturn(null);

        $result = $this->userController->getUser($userId);

        $this->assertEquals('error', $result['status']);
        $this->assertEquals('User not found', $result['message']);
        $this->assertEquals(404, $result['code']);
    }

    public function testGetUserValidationError()
    {
        $result = $this->userController->getUser('invalid-id');

        $this->assertEquals('error', $result['status']);
        $this->assertEquals('Invalid ID format', $result['message']);
        $this->assertEquals(422, $result['code']);
    }

    public function testGetUserDatabaseError()
    {
        $userId = 1;

        $this->userService->expects($this->once())
            ->method('findById')
            ->with($userId)
            ->willThrowException(new DatabaseException('Database error'));

        $result = $this->userController->getUser($userId);

        $this->assertEquals('error', $result['status']);
        $this->assertEquals('Database error occurred', $result['message']);
        $this->assertEquals(500, $result['code']);
    }

    public function testGetUserByEmailSuccess()
    {
        $email = 'test@example.com';
        $expectedUser = new User(1, $email, 'Test User');

        $this->userService->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn($expectedUser);

        $result = $this->userController->getUserByEmail($email);

        $this->assertEquals('success', $result['status']);
        $this->assertEquals($expectedUser->toArray(), $result['data']);
        $this->assertEquals(200, $result['code']);
    }

    public function testGetUserByEmailNotFound()
    {
        $email = 'notfound@example.com';

        $this->userService->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn(null);

        $result = $this->userController->getUserByEmail($email);

        $this->assertEquals('error', $result['status']);
        $this->assertEquals('User not found', $result['message']);
        $this->assertEquals(404, $result['code']);
    }

    public function testGetUserByEmailValidationError()
    {
        $result = $this->userController->getUserByEmail('invalid-email');

        $this->assertEquals('error', $result['status']);
        $this->assertEquals('Invalid email format', $result['message']);
        $this->assertEquals(422, $result['code']);
    }
}
