<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Order\Position\PositionArray;


use BartoszBartniczak\ArrayObject\ArrayOfObjects;
use BartoszBartniczak\EventSourcing\Shop\Order\Position\Position;

class PositionArrayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Position\PositionArray\PositionArray::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Position\PositionArray\PositionArray::getKeyNamingStrategy
     */
    public function testConstructor()
    {

        $positionArray = new PositionArray(new ProductIdStrategy());
        $this->assertInstanceOf(ArrayOfObjects::class, $positionArray);
        $this->assertEquals(Position::class, $positionArray->getClassName());

        $positions = [];
        $positions[] = $this->getMockBuilder(Position::class)
            ->disableOriginalConstructor()
            ->getMock();

        $keyNamingStrategy = new ProductIdStrategy();
        $positionArray = new PositionArray($keyNamingStrategy, $positions);
        $this->assertEquals(1, $positionArray->count());
        $this->assertSame($keyNamingStrategy, $positionArray->getKeyNamingStrategy());
    }
}
