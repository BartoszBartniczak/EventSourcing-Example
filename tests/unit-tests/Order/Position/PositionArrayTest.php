<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Order\Position;


use BartoszBartniczak\ArrayObject\ArrayOfObjects;

class PositionArrayTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Position\PositionArray::__construct
     */
    public function testConstructor()
    {

        $positionArray = new PositionArray();
        $this->assertInstanceOf(ArrayOfObjects::class, $positionArray);
        $this->assertEquals(Position::class, $positionArray->getClassName());

        $positions = [];
        $positions[] = $this->getMockBuilder(Position::class)
            ->disableOriginalConstructor()
            ->getMock();

        $positionArray = new PositionArray($positions);
        $this->assertEquals(1, $positionArray->count());
    }
}
