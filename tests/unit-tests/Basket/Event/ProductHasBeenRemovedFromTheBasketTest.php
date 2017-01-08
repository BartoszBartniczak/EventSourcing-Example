<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Event;


use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Shop\EventTestCase;
use BartoszBartniczak\EventSourcing\Shop\Product\Id as ProductId;

class ProductHasBeenRemovedFromTheBasketTest extends EventTestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Event\ProductHasBeenRemovedFromTheBasket::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Event\ProductHasBeenRemovedFromTheBasket::getProductId
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

        $productHasBeenRemovedFromTheBasket = new ProductHasBeenRemovedFromTheBasket(
            $this->generateEventId(),
            $this->generateDateTime(),
            $basket,
            $productId
        );

        $this->assertSameEventIdAsGenerated($productHasBeenRemovedFromTheBasket);
        $this->assertSameDateTimeAsGenerated($productHasBeenRemovedFromTheBasket);
        $this->assertSame($basket, $productHasBeenRemovedFromTheBasket->getBasket());
        $this->assertSame($productId, $productHasBeenRemovedFromTheBasket->getProductId());

    }

}
