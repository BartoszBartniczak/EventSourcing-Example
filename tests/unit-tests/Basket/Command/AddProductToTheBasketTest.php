<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Command;


use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Shop\Product\Product;

class AddProductToTheBasketTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Command\AddProductToTheBasket::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Command\AddProductToTheBasket::getBasket
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Command\AddProductToTheBasket::getProduct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Command\AddProductToTheBasket::getQuantity
     */
    public function testGetters()
    {
        $basket = $this->getMockBuilder(Basket::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $basket Basket */

        $product = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $product Product */

        $addProductToTheBasket = new AddProductToTheBasket($basket, $product, 23.41);
        $this->assertSame($basket, $addProductToTheBasket->getBasket());
        $this->assertSame($product, $addProductToTheBasket->getProduct());
        $this->assertSame(23.41, $addProductToTheBasket->getQuantity());
    }

}
