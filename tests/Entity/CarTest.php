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

    public function testNotBlank(): void
    {
        $car = $this->getEntity();
        $car->setLicensePlate("")
            ->setEnergie("")
            ->setModel("")
            ->setColor("");
        $errors = static::getContainer()->get("validator")->validate($car);
        $this->assertCount(4, $errors);
    }
    public function testRegexText(): void
    {
        $car = $this->getEntity();
        $error = 0;

        $valuesToTest = ['eiofu;', 'Aeieu1233', "ieuzri-oezu", ",,,,,,,", "A11111:::", "test123!"];

        foreach ($valuesToTest as $value) {
            $car->setModel($value)
                 ->setColor($value)
                ->setEnergie($value);
            // Validate the user and count the number of violations
            $violations = static::getContainer()->get("validator")->validate($car);
            $error += count($violations);
        }
        $this->assertEquals(12, $error);
    }

    public function testRegexLicensePlate(): void
    {
        $car = $this->getEntity();
        $error = 0;

        $valuesToTest = ['XX-123-XX', 'XX-123-D', "ieuzri", "aa-123-bb", "AA-AA-AA", "A-123-A"];

        foreach ($valuesToTest as $value) {
            $car->setLicensePlate($value);

            // Validate the user and count the number of violations
            $violations = static::getContainer()->get("validator")->validate($car);
            $error += count($violations);
        }
        $this->assertEquals(5, $error);
    }

    public function testRegex(): void
    {
        $car = $this->getEntity();
        $error = 0;

        $valuesToTest = [0, 12, 5];

        foreach ($valuesToTest as $value) {
            $car->setNbPassenger($value);

            // Validate the user and count the number of violations
            $violations = static::getContainer()->get("validator")->validate($car);
            $error += count($violations);
        }
        $this->assertEquals(2, $error);
    }
}
