<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Event;


use BartoszBartniczak\EventSourcing\Shop\Basket\Id as BasketId;
use BartoszBartniczak\EventSourcing\Shop\Product\Id as ProductId;
use BartoszBartniczak\EventSourcing\Test\EventTestCase;

class QuantityOfTheProductHasBeenChangedTest extends EventTestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Event\QuantityOfTheProductHasBeenChanged::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Event\QuantityOfTheProductHasBeenChanged::getProductId()
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Event\QuantityOfTheProductHasBeenChanged::getQuantity()
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

        $quantityOfTheProductHasBeenChanged = new QuantityOfTheProductHasBeenChanged(
            $this->generateEventId(),
            $this->generateDateTime(),
            $basketId,
            $productId,
            32.12
        );
        $this->assertSameEventIdAsGenerated($quantityOfTheProductHasBeenChanged);
        $this->assertSameDateTimeAsGenerated($quantityOfTheProductHasBeenChanged);
        $this->assertSame($basketId, $quantityOfTheProductHasBeenChanged->getBasketId());
        $this->assertSame($productId, $quantityOfTheProductHasBeenChanged->getProductId());
        $this->assertSame(32.12, $quantityOfTheProductHasBeenChanged->getQuantity());

    }

}
