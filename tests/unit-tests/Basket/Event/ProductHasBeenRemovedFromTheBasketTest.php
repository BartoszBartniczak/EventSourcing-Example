<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Event;


use BartoszBartniczak\EventSourcing\Shop\Basket\Id as BasketId;
use BartoszBartniczak\EventSourcing\Shop\Product\Id as ProductId;
use BartoszBartniczak\EventSourcing\Test\EventTestCase;

class ProductHasBeenRemovedFromTheBasketTest extends EventTestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Event\ProductHasBeenRemovedFromTheBasket::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Event\ProductHasBeenRemovedFromTheBasket::getProductId
     */
    public function testConstructor()
    {

        $basketId = $this->getMockBuilder(BasketId::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();
        /* @var $basketId BasketId */

        $productId = $this->getMockBuilder(ProductId::class)
            ->disableOriginalConstructor()
            ->getMock();
        /* @var $productId ProductId */

        $productHasBeenRemovedFromTheBasket = new ProductHasBeenRemovedFromTheBasket(
            $this->generateEventId(),
            $this->generateDateTime(),
            $basketId,
            $productId
        );

        $this->assertSameEventIdAsGenerated($productHasBeenRemovedFromTheBasket);
        $this->assertSameDateTimeAsGenerated($productHasBeenRemovedFromTheBasket);
        $this->assertSame($basketId, $productHasBeenRemovedFromTheBasket->getBasketId());
        $this->assertSame($productId, $productHasBeenRemovedFromTheBasket->getProductId());

    }

}
