<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Factory;


use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Shop\UUID\Generator;
use BartoszBartniczak\EventSourcing\Shop\UUID\UUID;

class FactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Factory\Factory::createNew
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Factory\Factory::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Factory\Factory::generateNewId
     */
    public function testCreateNew()
    {

        $uuid = new UUID('8f512495-c546-4b6c-9d3a-559d67fa69d3');

        $generator = $this->getMockBuilder(Generator::class)
            ->setMethods([
                    'generate'
                ]
            )
            ->getMock();
        $generator->method('generate')
            ->willReturn($uuid);
        /* @var $generator Generator */


        $factory = new Factory($generator);
        $basket = $factory->createNew('owner@email.com');
        $this->assertSame($uuid->toNative(), $basket->getId()->toNative());
        $this->assertSame('owner@email.com', $basket->getOwnerEmail());
        $this->assertEquals(0, $basket->getPositions()->count());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Factory\Factory::createEmpty
     */
    public function testCreateEmpty()
    {
        $uuid = new UUID('8f512495-c546-4b6c-9d3a-559d67fa69d3');

        $generator = $this->getMockBuilder(Generator::class)
            ->setMethods([
                    'generate'
                ]
            )
            ->getMock();
        $generator->method('generate')
            ->willReturn($uuid);
        /* @var $generator Generator */


        $factory = new Factory($generator);
        $basket = $factory->createEmpty();
        $this->assertInstanceOf(Basket::class, $basket);
        $this->assertSame($uuid->toNative(), $basket->getId()->toNative());
        $this->assertSame('', $basket->getOwnerEmail());
        $this->assertEquals(0, $basket->getPositions()->count());
    }

}
