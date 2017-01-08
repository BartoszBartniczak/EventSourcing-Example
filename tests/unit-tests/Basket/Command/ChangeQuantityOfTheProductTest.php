<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Command;


use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Shop\Product\Id as ProductId;

class ChangeQuantityOfTheProductTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Command\ChangeQuantityOfTheProduct::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Command\ChangeQuantityOfTheProduct::getBasket()
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Command\ChangeQuantityOfTheProduct::getProductId()
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Command\ChangeQuantityOfTheProduct::getQuantity()
     */
    public function testConstructor()
    {

        $basket = $this->getMockBuilder(Basket::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $basket Basket */

        $productId = $this->getMockBuilder(ProductId::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $productId ProductId */

        $changeQuantityOfTheProduct = new ChangeQuantityOfTheProduct($basket, $productId, 34.97);
        $this->assertSame($basket, $changeQuantityOfTheProduct->getBasket());
        $this->assertSame($productId, $changeQuantityOfTheProduct->getProductId());
        $this->assertSame(34.97, $changeQuantityOfTheProduct->getQuantity());

    }

}
