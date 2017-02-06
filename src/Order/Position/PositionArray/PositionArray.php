<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Order\Position\PositionArray;


use BartoszBartniczak\ArrayObject\ArrayOfObjects;
use BartoszBartniczak\EventSourcing\Shop\Order\Position\Position;

class PositionArray extends ArrayOfObjects implements \IteratorAggregate, \Countable, \ArrayAccess
{
    /**
     * @var KeyNamingStrategy
     */
    protected $keyNamingStrategy;

    public function __construct(KeyNamingStrategy $keyNamingStrategy, array $items = null)
    {
        $this->keyNamingStrategy = $keyNamingStrategy;
        parent::__construct(Position::class, $items);
    }

    public function offsetSet($index, $newval)
    {
        /* @var $newval Position */
        parent::offsetSet($this->keyNamingStrategy->fromProduct($newval->getProduct()), $newval);
    }

    /**
     * @return KeyNamingStrategy
     */
    public function getKeyNamingStrategy(): KeyNamingStrategy
    {
        return $this->keyNamingStrategy;
    }

}