<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Event;


use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Test\EventTestCase;
use BartoszBartniczak\EventSourcing\Shop\Product\Id as ProductId;

class QuantityOfTheProductHasBeenChangedTest extends EventTestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Event\QuantityOfTheProductHasBeenChanged::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Event\QuantityOfTheProductHasBeenChanged::getProductId()
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Event\QuantityOfTheProductHasBeenChanged::getQuantity()
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

        $quantityOfTheProductHasBeenChanged = new QuantityOfTheProductHasBeenChanged(
            $this->generateEventId(),
            $this->generateDateTime(),
            $basket,
            $productId,
            32.12
        );
        $this->assertSameEventIdAsGenerated($quantityOfTheProductHasBeenChanged);
        $this->assertSameDateTimeAsGenerated($quantityOfTheProductHasBeenChanged);
        $this->assertSame($basket, $quantityOfTheProductHasBeenChanged->getBasket());
        $this->assertSame($productId, $quantityOfTheProductHasBeenChanged->getProductId());
        $this->assertSame(32.12, $quantityOfTheProductHasBeenChanged->getQuantity());

    }

}
