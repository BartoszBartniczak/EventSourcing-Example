<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Position;


use BartoszBartniczak\EventSourcing\Shop\Product\Id as ProductId;

class PositionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Position\Position::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Position\Position::getQuantity()
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Position\Position::getProductId()
     */
    public function testGetters()
    {

        $productId = $this->getMockBuilder(ProductId::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();
        /* @var $productId ProductId */

        $position = new Position($productId, 15.39);
        $this->assertSame($productId, $position->getProductId());
        $this->assertSame(15.39, $position->getQuantity());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Position\Position::changeQuantity
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Position\Position::getQuantity
     */
    public function testChangeQuantity()
    {
        $productId = $this->getMockBuilder(ProductId::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();
        /* @var $productId ProductId */

        $position = new Position($productId, 15.39);
        $position->changeQuantity(193.87);
        $this->assertSame(193.87, $position->getQuantity());
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Position\Position::addToQuantity
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Position\Position::getQuantity
     */
    public function testAddToQuantity()
    {
        $productId = $this->getMockBuilder(ProductId::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();
        /* @var $productId ProductId */

        $position = new Position($productId, 15.39);
        $position->addToQuantity(1.00);
        $this->assertSame(16.39, $position->getQuantity());
    }

}
