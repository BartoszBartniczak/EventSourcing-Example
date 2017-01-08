<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Order\Position;


use BartoszBartniczak\EventSourcing\Shop\Product\Product;

class PositionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Position\Position::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Position\Position::getProduct()
     * @covers \BartoszBartniczak\EventSourcing\Shop\Order\Position\Position::getQuantity()
     */
    public function testGetters()
    {

        $product = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $product Product */

        $position = new Position($product, 12.3);
        $this->assertSame($product, $position->getProduct());
        $this->assertSame(12.3, $position->getQuantity());

    }

}
