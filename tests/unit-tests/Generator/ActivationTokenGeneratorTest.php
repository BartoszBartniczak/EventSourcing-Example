<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Generator;


class ActivationTokenGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Generator\ActivationTokenGenerator::__construct
     */
    public function testConstructor()
    {
        $generator = new ActivationTokenGenerator();
        $this->assertInstanceOf(Generator::class, $generator);
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Generator\ActivationTokenGenerator::generate
     */
    public function testGenerate()
    {
        $generator = new ActivationTokenGenerator();
        $this->assertNotEmpty($generator->generate());
        $this->assertGreaterThanOrEqual(8, $generator->generate());
        $this->assertNotEquals($generator->generate(), $generator->generate());
    }
}
