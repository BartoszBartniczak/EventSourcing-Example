<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Position;


use BartoszBartniczak\ArrayObject\ArrayOfObjects;

class PositionArrayTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Position\PositionArray::__construct
     */
    public function testConstructor()
    {

        $positionArray = new PositionArray();
        $this->assertInstanceOf(ArrayOfObjects::class, $positionArray);
        $this->assertSame(Position::class, $positionArray->getClassName());

        $position1 = $this->getMockBuilder(Position::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $position Position */

        $position2 = $this->getMockBuilder(Position::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $position Position */

        $positionArray = new PositionArray([$position1, $position2]);
        $this->assertEquals(2, $positionArray->count());
    }

}
