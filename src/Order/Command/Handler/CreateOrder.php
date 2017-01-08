<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Order\Command\Handler;


use BartoszBartniczak\CQRS\Command\Command;
use BartoszBartniczak\EventSourcing\Shop\Basket\Command\CloseBasket;
use BartoszBartniczak\EventSourcing\Shop\Command\Handler\CommandHandler;
use BartoszBartniczak\EventSourcing\Shop\Email\Command\SendEmail;
use BartoszBartniczak\EventSourcing\Shop\Order\Event\OrderHasBeenCreated;
use BartoszBartniczak\EventSourcing\Shop\Order\Id;
use BartoszBartniczak\EventSourcing\Shop\Order\Order;

class CreateOrder extends CommandHandler
{
    /**
     * @inheritDoc
     */
    public function handle(Command $command): Order
    {
        /* @var $command \Shop\Order\Command\CreateOrder */

        $order = new Order(
            new Id($command->getUuidGenerator()->generate()->toNative()),
            $command->getBasket()->getId()
        );
        $order->addPositionsFromBasket($command->getBasket()->getPositions());

        $order->apply(
            new OrderHasBeenCreated(
                $this->generateEventId(),
                $this->generateDateTime(),
                $order->getOrderId(),
                $order->getBasketId(),
                $order->getPositions()
            )
        );

        $this->addNextCommand(new CloseBasket($command->getBasket()));
        $this->addNextCommand(new SendEmail($command->getEmailSenderService(), $command->getEmail()));

        return $order;
    }


}