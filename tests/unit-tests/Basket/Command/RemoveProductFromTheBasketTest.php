<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Command;

use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Shop\Product\Id as ProductId;

class RemoveProductFromTheBasketTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Command\RemoveProductFromTheBasket::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Command\RemoveProductFromTheBasket::getBasket
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Command\RemoveProductFromTheBasket::getProductId
     */
    public function testGetters()
    {
        $basket = $this->getMockBuilder(Basket::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $basket Basket */
        $productId = $this->getMockBuilder(ProductId::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $productId ProductId */

        $removeProductFromTheBasket = new RemoveProductFromTheBasket($basket, $productId);
        $this->assertSame($basket, $removeProductFromTheBasket->getBasket());
        $this->assertSame($productId, $removeProductFromTheBasket->getProductId());
    }

}
