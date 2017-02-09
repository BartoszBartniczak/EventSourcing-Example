<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Command\Handler;


use BartoszBartniczak\CQRS\Command\Command;
use BartoszBartniczak\EventSourcing\Command\Handler\CommandHandler;
use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Shop\Basket\Event\ProductHasBeenRemovedFromTheBasket;

class RemoveProductFromTheBasket extends CommandHandler
{
    /**
     * @inheritDoc
     */
    public function handle(Command $command): Basket
    {
        /* @var $command \BartoszBartniczak\EventSourcing\Shop\Basket\Command\RemoveProductFromTheBasket */
        $command->getBasket()->apply(
            new ProductHasBeenRemovedFromTheBasket(
                $this->generateEventId(),
                $this->generateDateTime(),
                $command->getBasket()->getId(),
                $command->getProductId())
        );

        return $command->getBasket();
    }

}