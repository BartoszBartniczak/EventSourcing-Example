<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Command\Handler;


use BartoszBartniczak\CQRS\Command\Command;
use BartoszBartniczak\EventSourcing\Command\Handler\CommandHandler;
use BartoszBartniczak\EventSourcing\EventAggregate\EventAggregate;
use BartoszBartniczak\EventSourcing\Shop\Basket\Event\ProductHasBeenAddedToTheBasket;

class AddProductToTheBasket extends CommandHandler
{
    /**
     * @inheritDoc
     */
    public function handle(Command $command): EventAggregate
    {
        /* @var $command \BartoszBartniczak\EventSourcing\Shop\Basket\Command\AddProductToTheBasket */

        $command->getBasket()->apply(
            new ProductHasBeenAddedToTheBasket(
                $this->generateEventId(),
                $this->generateDateTime(),
                $command->getBasket()->getId(),
                $command->getProduct()->getId(),
                $command->getQuantity())
        );

        return $command->getBasket();
    }

}