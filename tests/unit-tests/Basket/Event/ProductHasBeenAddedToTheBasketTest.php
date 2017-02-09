<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Event;


use BartoszBartniczak\EventSourcing\Shop\Basket\Id as BasketId;
use BartoszBartniczak\EventSourcing\Shop\Product\Id as ProductId;
use BartoszBartniczak\EventSourcing\Test\EventTestCase;

class ProductHasBeenAddedToTheBasketTest extends EventTestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Event\ProductHasBeenAddedToTheBasket::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Event\ProductHasBeenAddedToTheBasket::getProductId
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Event\ProductHasBeenAddedToTheBasket::getQuantity
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
            ->setMethods(null)
            ->getMock();
        /* @var $productId ProductId */

        $productHasBeenAddedToTheBasket = new ProductHasBeenAddedToTheBasket(
            $this->generateEventId(),
            $this->generateDateTime(),
            $basketId,
            $productId,
            12.00
        );
        $this->assertInstanceOf(Event::class, $productHasBeenAddedToTheBasket);
        $this->assertSameEventIdAsGenerated($productHasBeenAddedToTheBasket);
        $this->assertSameDateTimeAsGenerated($productHasBeenAddedToTheBasket);
        $this->assertSame($basketId, $productHasBeenAddedToTheBasket->getBasketId());
        $this->assertSame($productId, $productHasBeenAddedToTheBasket->getProductId());
        $this->assertSame(12.00, $productHasBeenAddedToTheBasket->getQuantity());
    }

}
