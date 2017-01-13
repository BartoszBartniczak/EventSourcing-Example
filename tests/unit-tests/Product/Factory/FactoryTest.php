<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace Product\Factory;


use BartoszBartniczak\EventSourcing\Shop\Product\Factory\Factory;
use BartoszBartniczak\EventSourcing\UUID\Generator;
use BartoszBartniczak\EventSourcing\UUID\UUID;


class FactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Product\Factory\Factory::createNew
     * @covers \BartoszBartniczak\EventSourcing\Shop\Product\Factory\Factory::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Product\Factory\Factory::generateNewId
     */
    public function testCreateNew()
    {

        $generator = $this->getMockBuilder(Generator::class)
            ->setMethods([
                'generate'
            ])
            ->getMockForAbstractClass();
        $generator->expects($this->once())
            ->method('generate')
            ->willReturn(new UUID('secret-uuid'));
        /* @var $generator Generator */

        $factory = new Factory($generator);

        $product = $factory->createNew('Product Name');
        $this->assertSame('secret-uuid', $product->getId()->toNative());
        $this->assertSame('Product Name', $product->getName());

        $product = $factory->createNew('Another Product', 'another-uuid');
        $this->assertSame('another-uuid', $product->getId()->toNative());
        $this->assertSame('Another Product', $product->getName());
    }

}
