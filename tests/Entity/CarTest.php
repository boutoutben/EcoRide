<?php

namespace App\Tests;

use App\Entity\Car;
use App\Entity\Mark;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CarTest extends KernelTestCase
{
    protected function setUp(): void
    {
        $kernel = self::bootKernel();
    }
    public function getEntity(): ?Car
    {
        $car = (new Car())
            ->setLicensePlate("AB-123-CD")
            ->setFirstRegistration(new \DateTime("2024-11-25 00:00:00"))
            ->setMark(new Mark())
            ->setModel("XS")
            ->setColor("vert")
            ->setEnergie("thermal")
            ->setNbPassenger(3);

        return $car;
    }
    public function testEntityIsValid(): void
    {
        $car = $this->getEntity();
        $errors = static::getContainer()->get("validator")->validate($car);
        $this->assertCount(0, $errors);
    }
}
