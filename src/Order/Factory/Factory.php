<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Order\Factory;


use BartoszBartniczak\EventSourcing\Shop\Basket\Id as BasketId;
use BartoszBartniczak\EventSourcing\Shop\Order\Id as OrderId;
use BartoszBartniczak\EventSourcing\Shop\Order\Order;
use BartoszBartniczak\EventSourcing\Shop\Order\Position\PositionArray\Factory as PositionsFactory;
use BartoszBartniczak\EventSourcing\UUID\Generator;

class Factory
{
    /**
     * @var Generator
     */
    private $uuidGenerator;

    /**
     * @var PositionsFactory
     */
    private $positionsFactory;

    /**
     * Factory constructor.
     * @param Generator $uuidGenerator
     * @param PositionsFactory $positionsFactory
     */
    public function __construct(Generator $uuidGenerator, PositionsFactory $positionsFactory)
    {
        $this->uuidGenerator = $uuidGenerator;
        $this->positionsFactory = $positionsFactory;
    }

    /**
     * @return Order
     */
    public function createEmpty(): Order
    {
        return new Order($this->generateNewId(), $this->generateEmptyBasketId(), $this->positionsFactory->createEmpty());
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