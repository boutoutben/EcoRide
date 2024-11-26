<?php

namespace App\Tests\App\Tests\Unit\Entity;

use App\Entity\Carpool;
use App\Entity\User;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CarpoolTest extends KernelTestCase
{
    public function getEntity(): ?Carpool
    {
        $user = new User();
        return (new Carpool)->setStartPlace("Lille")
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
        $kernel = self::bootKernel();
        $container = static::getContainer();

        $carpool = $this->getEntity();
        $errors = $container->get("validator")->validate($carpool);
        $this->assertCount(0, $errors);
    }

    public function testNotBlankAndLenghtMin(): void
    {
        $kernel = self::bootKernel();
        $container = static::getContainer();

        $carpool = $this->getEntity();
        $carpool->setStartPlace('');
        $carpool->setEndPlace("");
        $errors = $container->get("validator")->validate($carpool);
        $this->AssertCount(4, $errors);
    }

    public function testLenghtMax(): void
    {
        $kernel = self::bootKernel();
        $container = static::getContainer();

        $carpool = $this->getEntity();
        $carpool->setStartPlace("ifokdsofiueodfisusodifuospidfuoidsufoipsdufpoisdfupoisdfuiodsfuposidfuisodfuoisdfuosidufoipsduf");
        $carpool->setEndPlace("foisdfopisdfospidfposdifpo^sdifpos^dfip^sodifps^doifdfjdkfidifjdifuidufiduf");
        $errors = $container->get("validator")->validate($carpool);
        $this->AssertCount(2, $errors);
    }
    public function testPositive(): void
    {
        $kernel = self::bootKernel();
        $container = static::getContainer();

        $carpool = $this->getEntity();
        $carpool->setPlaceLeft(-1);
        $errors = $container->get("validator")->validate($carpool);
        $this->AssertCount(1, $errors);
    }

    public function testLessThan(): void
    {
        $kernel = self::bootKernel();
        $container = static::getContainer();

        $carpool = $this->getEntity();
        $carpool->setPlaceLeft(10);
        $errors = $container->get("validator")->validate($carpool);
        $this->AssertCount(1, $errors);
    }

    public function testGreaterThanOrEqual(): void
    {
        $kernel = self::bootKernel();
        $container = static::getContainer();

        $carpool = $this->getEntity();
        $carpool->setPrice(2);
        $errors = $container->get("validator")->validate($carpool);
        $this->AssertCount(0, $errors);
        $carpool->setPrice(1);
        $errors = $container->get("validator")->validate($carpool);
        $this->AssertCount(1, $errors);
    }
}
