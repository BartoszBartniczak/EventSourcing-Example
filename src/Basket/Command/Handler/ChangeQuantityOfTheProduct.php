<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Basket\Command\Handler;


use BartoszBartniczak\CQRS\Command\Command;
use BartoszBartniczak\EventSourcing\Shop\Basket\Basket;
use BartoszBartniczak\EventSourcing\Shop\Basket\Event\QuantityOfTheProductHasBeenChanged;
use BartoszBartniczak\EventSourcing\Command\Handler\CommandHandler;

class ChangeQuantityOfTheProduct extends CommandHandler
{
    /**
     * @inheritDoc
     */
    public function handle(Command $command): Basket
    {
        /* @var $command \Shop\Basket\Command\ChangeQuantityOfTheProduct */

        $command->getBasket()->apply(
            new QuantityOfTheProductHasBeenChanged(
                $this->generateEventId(),
                $this->generateDateTime(),
                $command->getBasket(),
                $command->getProductId(),
                $command->getQuantity())
        );

        return $command->getBasket();
    }


}