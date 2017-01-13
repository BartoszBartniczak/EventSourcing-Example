<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Order\Factory;


use BartoszBartniczak\EventSourcing\Shop\Basket\Id as BasketId;
use BartoszBartniczak\EventSourcing\Shop\Order\Id as OrderId;
use BartoszBartniczak\EventSourcing\Shop\Order\Order;
use BartoszBartniczak\EventSourcing\UUID\Generator;

class Factory
{
    /**
     * @var Generator
     */
    private $uuidGenerator;

    /**
     * Factory constructor.
     * @param Generator $uuidGenerator
     */
    public function __construct(Generator $uuidGenerator)
    {
        $this->uuidGenerator = $uuidGenerator;
    }

    /**
     * @return Order
     */
    public function createEmpty(): Order
    {
        return new Order($this->generateNewId(), $this->generateEmptyBasketId());
    }

    /**
     * @return OrderId
     */
    private function generateNewId(): OrderId
    {
        return new OrderId($this->uuidGenerator->generate()->toNative());
    }

    /**
     * @return BasketId
     */
    private function generateEmptyBasketId(): BasketId
    {
        return new BasketId('');
    }


}