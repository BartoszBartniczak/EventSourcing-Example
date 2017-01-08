<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Product\Repository\Event;


use BartoszBartniczak\EventSourcing\Shop\EventTestCase;

class ProductHasNotBeenFoundTest extends EventTestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Product\Repository\Event\ProductHasNotBeenFound::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Product\Repository\Event\ProductHasNotBeenFound::getUserEmail
     * @covers \BartoszBartniczak\EventSourcing\Shop\Product\Repository\Event\ProductHasNotBeenFound::getProductName
     */
    public function testGetters()
    {

        $productHasNotBeenFound = new ProductHasNotBeenFound(
            $this->generateEventId(),
            $this->generateDateTime(),
            'Batmobile',
            'user@email.com'
        );
        $this->assertInstanceOf(Event::class, $productHasNotBeenFound);
        $this->assertSameEventIdAsGenerated($productHasNotBeenFound);
        $this->assertSameDateTimeAsGenerated($productHasNotBeenFound);
        $this->assertSame('user@email.com', $productHasNotBeenFound->getUserEmail());
        $this->assertSame('Batmobile', $productHasNotBeenFound->getProductName());
    }

}
