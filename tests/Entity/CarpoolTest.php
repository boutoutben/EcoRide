<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Carpool;
use App\Entity\User;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CarpoolTest extends KernelTestCase
{
    private UserPasswordHasherInterface $passwordHasher;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);
    }

    public function getEntity(): ?Carpool
    {
        $user = new User($this->passwordHasher);
        return (new Carpool)
            ->setStartPlace("Lille")
            ->setEndPlace("Valencienne")
            ->setPlaceLeft(5)
            ->setStartDate(new DateTime("2012-02-01 07:00:00"))
            ->setEndDate(new DateTime("2012-02-01 08:30:00"))
            ->setEcologique(true)
            ->setGreat(false)
            ->setStart(false)
            ->setPrice(2.0)
            ->setUser($user);
    }

    public function testEntityIsValid(): void
    {
        $carpool = $this->getEntity();
        $errors = static::getContainer()->get("validator")->validate($carpool);
        $this->assertCount(0, $errors);
    }

    public function testNotBlankAndLenghtMin(): void
    {
        $carpool = $this->getEntity();
        $carpool->setStartPlace('');
        $carpool->setEndPlace('');
        $errors = static::getContainer()->get("validator")->validate($carpool);
        $this->assertCount(4, $errors);
    }

    public function testLenghtMax(): void
    {
        $carpool = $this->getEntity();
        $carpool->setStartPlace(str_repeat('a', 256));
        $carpool->setEndPlace(str_repeat('b', 256));
        $errors = static::getContainer()->get("validator")->validate($carpool);
        $this->assertCount(2, $errors);
    }

    public function testPositive(): void
    {
        $carpool = $this->getEntity();
        $carpool->setPlaceLeft(-1);
        $errors = static::getContainer()->get("validator")->validate($carpool);
        $this->assertCount(1, $errors);
    }

    public function testLessThan(): void
    {
        $carpool = $this->getEntity();
        $carpool->setPlaceLeft(10);
        $errors = static::getContainer()->get("validator")->validate($carpool);
        $this->assertCount(1, $errors);
    }

    public function testGreaterThanOrEqual(): void
    {
        $carpool = $this->getEntity();
        $carpool->setPrice(2);
        $errors = static::getContainer()->get("validator")->validate($carpool);
        $this->assertCount(0, $errors);
        $carpool->setPrice(1);
        $errors = static::getContainer()->get("validator")->validate($carpool);
        $this->assertCount(1, $errors);
    }
}
