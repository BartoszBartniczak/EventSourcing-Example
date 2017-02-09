<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Product\Repository\Event;


use BartoszBartniczak\EventSourcing\Event\Event;
use BartoszBartniczak\EventSourcing\Shop\DeSerializationTestCase;

class ProductHasNotBeenFoundTest extends DeSerializationTestCase
{
    protected function getJsonFileName(): string
    {
        return 'ProductHasNotBeenFound.json';
    }

    protected function getEvent(): Event
    {
        return new ProductHasNotBeenFound(
            $this->generateEventId('250f5d1a-d68d-4c10-8ffc-f63cd1f99455'),
            $this->generateDateTime('2017-01-26T08:51:10+0100'),
            'Cookies',
            'user@user.com'
        );
    }


}
