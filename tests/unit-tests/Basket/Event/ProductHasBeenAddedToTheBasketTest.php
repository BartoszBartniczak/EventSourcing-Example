<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Event;


use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Test\EventTestCase;
use BartoszBartniczak\EventSourcing\Shop\Product\Product;

class ProductHasBeenAddedToTheBasketTest extends EventTestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Event\ProductHasBeenAddedToTheBasket::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Event\ProductHasBeenAddedToTheBasket::getProduct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Event\ProductHasBeenAddedToTheBasket::getQuantity
     */
    public function testConstructor()
    {

        $basket = $this->getMockBuilder(Basket::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $basket Basket */

        $product = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $product Product */

        $productHasBeenAddedToTheBasket = new ProductHasBeenAddedToTheBasket(
            $this->generateEventId(),
            $this->generateDateTime(),
            $basket,
            $product,
            12.00
        );
        $this->assertInstanceOf(Event::class, $productHasBeenAddedToTheBasket);
        $this->assertSameEventIdAsGenerated($productHasBeenAddedToTheBasket);
        $this->assertSameDateTimeAsGenerated($productHasBeenAddedToTheBasket);
        $this->assertSame($basket, $productHasBeenAddedToTheBasket->getBasket());
        $this->assertSame($product, $productHasBeenAddedToTheBasket->getProduct());
        $this->assertSame(12.00, $productHasBeenAddedToTheBasket->getQuantity());
    }

}
