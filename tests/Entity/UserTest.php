<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserTest extends KernelTestCase
{
    private UserPasswordHasherInterface $passwordHasher;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);
    }
    public function getEntity(): ?User
    {
        $user =(new User())
            ->setName("Tester")
            ->setSurname("SubTester")
            ->setEmail("test@gmail.com")
            ->setPhone("01 23 45 67 89")
            ->setNbCredit(20)
            ->setImg("img.png")
            ->setPlainPassword("Test123!")
            ->setUsername("tester123")
            ->setRoles(["ROLE_USER"]);
            

        $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPlainPassword());
        $user->setPassword($hashedPassword);
        return $user;
    }

    public function testEntityIsValid(): void
    {
        $user = $this->getEntity();
        $errors = static::getContainer()->get("validator")->validate($user);
        $this->assertCount(0, $errors);
    }

    public function testNotBlankAndMinLength(): void{
        $user = $this->getEntity();
        $user->setName("")
            ->setSurname("")
            ->setEmail("")
            ->setPhone("")
            ->setImg("")
            ->setPlainPassword('')
            ->setPassword('')
            ->setUsername("");
        $errors = static::getContainer()->get("validator")->validate($user);
        $this->assertCount(11, $errors);
    }
    public function testMaxLength(): void
    {
        $user = $this->getEntity();
        $user->setName("sssssssssssssssssssssssssssssssssssssssssssssssssssssss")
            ->setSurname("sssssssssssssssssssssssssssssssssssssssssssssssssssssss")
            ->setEmail("sssssssssssssssssssssssssssssssssssssssssssssssssssssss")
            ->setPlainPassword('sssssssssssssssssssssssssssssssssssssssssssssssssssssss')
            ->setUsername("sssssssssssssssssssssssssssssssssssssssssssssssssssssss");
        $errors = static::getContainer()->get("validator")->validate($user);
        $this->assertCount(7, $errors);
    }

    public function testRegexSimplyText(): void
    {
        $user = $this->getEntity();
        $error = 0;

        $valuesToTest = ['eiofu;', 'Aeieu1233', "ieuzrioezu", ",,,,,,,", "A11111:::@","test@gmail.com"];

        foreach ($valuesToTest as $value) {
            $user->setUsername($value);
            $user->setName($value);
            $user->setSurname($value);
            $user->setEmail($value);

            // Validate the user and count the number of violations
            $violations = static::getContainer()->get("validator")->validate($user);
            $error += count($violations);
        }
        // Assert that the total number of violations is 8
        $this->assertEquals(17, $error, "Expected 8 total validation errors.");
    }
    public function testRegexPassword(): void
    {
        $user = $this->getEntity();
        $error = 0;
        $valuesToTest = ['eiofu;', 'Aeieu1233', "Beeiu123!", ",,,,,,,", "A11111:::@", "Bei123", "A123!"];

        foreach ($valuesToTest as $value) {
            $user->setPlainPassword($value);

            // Validate the user and count the number of violations for each password
            $violations = static::getContainer()->get("validator")->validate($user);
            $error += count($violations);
        }

        // Assert that the total number of validation errors is 5
        $this->assertEquals(6, $error, "Expected 6 total validation errors for passwords.");
    }
    public function testRegexImg(): void
    {
        $user = $this->getEntity();
        $error = 0;
        $valuesToTest = ['eiofu', 'Aeieu1233.png', "Beeiu123.jpg", "ieoruz.pdf"];

        foreach ($valuesToTest as $value) {
            $user->setImg($value);

            // Validate the user and count the number of violations for each password
            $violations = static::getContainer()->get("validator")->validate($user);
            $error += count($violations);
        }

        // Assert that the total number of validation errors is 5
        $this->assertEquals(2, $error, "Expected 6 total validation errors for passwords.");
    }
    public function testPositiveNumber(): void
    {
        $user = $this->getEntity();
        $error = 0;
        $valuesToTest = [-5, -10,0, 1, 100];

        foreach ($valuesToTest as $value) {
            $user->setNbCredit($value);

            // Validate the user and count the number of violations for each password
            $violations = static::getContainer()->get("validator")->validate($user);
            $error += count($violations);
        }

        // Assert that the total number of validation errors is 5
        $this->assertEquals(2, $error, "Expected 6 total validation errors for passwords.");
    }
}
