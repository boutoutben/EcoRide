<?php

namespace App\Tests;

use App\Entity\Mark;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MarkTest extends KernelTestCase
{
    protected function setUp(): void
    {
        $kernel = self::bootKernel();
    }
    public function getEntity(): ?Mark
    {
        $mark = (new Mark())->setLabel("Peugot");

        return $mark;
    }
    public function testEntityIsValid(): void
    {
        $mark = $this->getEntity();
        $errors = static::getContainer()->get("validator")->validate($mark);
        $this->assertCount(0, $errors);
    }
    public function testEntityNotBlank(): void
    {
        $mark = $this->getEntity();
        $mark->setLabel("");
        $errors = static::getContainer()->get("validator")->validate($mark);
        $this->assertCount(1, $errors);
    }
    public function testRegexText(): void
    {
        $mark = $this->getEntity();
        $error = 0;

        $valuesToTest = ['eiofu;', 'Aeieu1233', "ieuzri-oezu", ",,,,,,,", "A11111:::", "test123!"];

        foreach ($valuesToTest as $value) {
            $mark->setLabel($value);
            // Validate the user and count the number of violations
            $violations = static::getContainer()->get("validator")->validate($mark);
            $error += count($violations);
        }
        $this->assertEquals(4, $error);
    }

}
