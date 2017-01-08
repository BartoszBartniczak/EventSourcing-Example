<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Command\Handler;


use BartoszBartniczak\CQRS\Command\Command;
use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Shop\Basket\Event\ProductHasBeenRemovedFromTheBasket;
use BartoszBartniczak\EventSourcing\Shop\Command\Handler\CommandHandler;

class RemoveProductFromTheBasket extends CommandHandler
{
    /**
     * @inheritDoc
     */
    public function handle(Command $command): Basket
    {
        /* @var $command \Shop\Basket\Command\RemoveProductFromTheBasket */
        $command->getBasket()->apply(
            new ProductHasBeenRemovedFromTheBasket(
                $this->generateEventId(),
                $this->generateDateTime(),
                $command->getBasket(),
                $command->getProductId())
        );

        return $command->getBasket();
    }

}