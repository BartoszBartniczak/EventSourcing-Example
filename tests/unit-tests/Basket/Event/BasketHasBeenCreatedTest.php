<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Event;


use BartoszBartniczak\EventSourcing\Shop\Basket\Id as BasketId;
use BartoszBartniczak\EventSourcing\Test\EventTestCase;

class BasketHasBeenCreatedTest extends EventTestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Event\BasketHasBeenCreated::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Basket\Event\BasketHasBeenCreated::getOwnerEmail()
     */
    public function testConstructor()
    {
        $basketId = $this->getMockBuilder(BasketId::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();
        /* @var $basketId BasketId */

        $basketHasBeenCreated = new BasketHasBeenCreated(
            $this->generateEventId(),
            $this->generateDateTime(),
            $basketId,
            'owner@email.com'
        );
        $this->assertInstanceOf(Event::class, $basketHasBeenCreated);
        $this->assertSameEventIdAsGenerated($basketHasBeenCreated);
        $this->assertSameDateTimeAsGenerated($basketHasBeenCreated);
        $this->assertSame($basketId, $basketHasBeenCreated->getBasketId());
        $this->assertSame('owner@email.com', $basketHasBeenCreated->getOwnerEmail());
    }

}
