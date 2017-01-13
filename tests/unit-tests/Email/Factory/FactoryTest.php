<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Email\Factory;


use BartoszBartniczak\EventSourcing\UUID\Generator;
use BartoszBartniczak\EventSourcing\UUID\UUID;

class FactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Email\Factory\Factory::createEmpty
     * @covers \BartoszBartniczak\EventSourcing\Shop\Email\Factory\Factory::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Email\Factory\Factory::generateNewId
     */
    public function testCreateEmpty()
    {

        $generator = $this->getMockBuilder(Generator::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'generate'
            ])
            ->getMockForAbstractClass();
        $generator->method('generate')
            ->willReturn(new UUID('0c1d1247-1d90-4dd1-bd8b-03d59b2acb55'));
        /* @var $generator Generator */

        $factory = new Factory($generator);
        $email = $factory->createEmpty();
        $this->assertSame('0c1d1247-1d90-4dd1-bd8b-03d59b2acb55', $email->getId()->toNative());
    }

}
