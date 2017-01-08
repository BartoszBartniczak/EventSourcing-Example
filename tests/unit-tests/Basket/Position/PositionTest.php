<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Position;


use BartoszBartniczak\EventSourcing\Shop\Product\Product;

class PositionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Position\Position::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Position\Position::getQuantity()
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Position\Position::getProduct()
     */
    public function testGetters()
    {

        $product = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $product Product */

        $position = new Position($product, 15.39);
        $this->assertSame($product, $position->getProduct());
        $this->assertSame(15.39, $position->getQuantity());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Position\Position::changeQuantity
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Position\Position::getQuantity
     */
    public function testChangeQuantity()
    {
        $product = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $product Product */

        $position = new Position($product, 15.39);
        $position->changeQuantity(193.87);
        $this->assertSame(193.87, $position->getQuantity());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Position\Position::addToQuantity
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Position\Position::getQuantity
     */
    public function testAddToQuantity()
    {
        $product = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $product Product */

        $position = new Position($product, 15.39);
        $position->addToQuantity(1.00);
        $this->assertSame(16.39, $position->getQuantity());
    }

}
