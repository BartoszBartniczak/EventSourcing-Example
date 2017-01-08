<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Event;


use BartoszBartniczak\ArrayObject\ArrayOfObjects;

class EventStream extends ArrayOfObjects
{

    /**
     * EventStream constructor.
     * @param array|null $items
     */
    public function __construct(array $items = null)
    {
        parent::__construct(Event::class, $items);
    }

}