<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Validator\Validator;
use Exception\ValidationException;

class ValidatorTest extends TestCase
{
    public function testValidateEmailSuccess($input, $expected)
    {
        $result = Validator::validateEmail($input);
        $this->assertEquals($expected, $result);
    }

    public function testValidateEmailInvalid($invalidEmail)
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Invalid email format');
        Validator::validateEmail($invalidEmail);
    }

    public function testValidateIdSuccess($input, $expected)
    {
        $result = Validator::validateId($input);
        $this->assertEquals($expected, $result);
    }

    public function testValidateIdInvalid($invalidId)
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Invalid ID format');
        Validator::validateId($invalidId);
    }

    public function validEmailProvider(): array
    {
        return [
            'normal email' => ['test@example.com', 'test@example.com'],
            'email with dots' => ['test.name@example.com', 'test.name@example.com'],
            'email with plus' => ['test+label@example.com', 'test+label@example.com'],
            'email with subdomain' => ['test@sub.example.com', 'test@sub.example.com'],
            'email with numbers' => ['test123@example.com', 'test123@example.com'],
            'email with trimmed spaces' => [' test@example.com ', 'test@example.com'],
        ];
    }

    public function invalidEmailProvider(): array
    {
        return [
            'empty string' => [''],
            'null' => [null],
            'number' => [123],
            'missing @' => ['testexample.com'],
            'missing domain' => ['test@'],
            'missing local part' => ['@example.com'],
            'multiple @' => ['test@@example.com'],
            'no domain extension' => ['test@example'],
            'spaces in email' => ['test @example.com'],
            'special characters' => ['test!#$%@example.com'],
            'invalid chars' => ['test<>@example.com'],
        ];
    }

    public function validIdProvider(): array
    {
        return [
            'positive integer' => [1, 1],
            'positive integer string' => ['1', 1],
            'larger number' => [999, 999],
            'number with leading spaces' => [' 123 ', 123],
            'number with leading zeros' => ['000123', 123],
        ];
    }

    public function invalidIdProvider(): array
    {
        return [
            'zero' => [0],
            'negative' => [-1],
            'empty string' => [''],
            'null' => [null],
            'float' => [1.5],
            'text' => ['abc'],
            'special chars' => ['!@#'],
            'zero string' => ['0'],
            'negative string' => ['-1'],
            'alphanumeric' => ['1a'],
            'spaces only' => ['   '],
            'decimal string' => ['1.5'],
        ];
    }

    public function testValidateIdMaxValue()
    {
        $result = Validator::validateId(PHP_INT_MAX);
        $this->assertEquals(PHP_INT_MAX, $result);
    }

    public function testValidateEmailMaxLength()
    {
        $localPart = str_repeat('a', 64);
        $domain = str_repeat('b', 63);
        $email = $localPart . '@' . $domain . '.com';
        
        $result = Validator::validateEmail($email);
        $this->assertEquals($email, $result);
    }
}
