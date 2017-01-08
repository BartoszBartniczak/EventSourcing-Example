<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Command\Handler;


use BartoszBartniczak\CQRS\Command\Handler\CommandHandler as BasicCommandHandler;
use BartoszBartniczak\EventSourcing\Shop\Command\CommandList;
use BartoszBartniczak\EventSourcing\Shop\Event\Event;
use BartoszBartniczak\EventSourcing\Shop\Event\EventStream;
use BartoszBartniczak\EventSourcing\Shop\Event\Id;
use BartoszBartniczak\EventSourcing\Shop\UUID\Generator as UUIDGenerator;

abstract class CommandHandler extends BasicCommandHandler
{

    /**
     * @var UUIDGenerator;
     */
    protected $uuidGenerator;

    /**
     * Additional events are not connected with EventAggregate. Although they are emitted to EventBus.
     * @var  EventStream
     */
    private $additionalEvents;

    /**
     * CommandHandler constructor.
     * @param UUIDGenerator $uuidGenerator
     */
    public function __construct(UUIDGenerator $uuidGenerator)
    {
        parent::__construct();
        $this->uuidGenerator = $uuidGenerator;
        $this->additionalEvents = new EventStream();
    }

    /**
     * @return EventStream
     */
    public function getAdditionalEvents(): EventStream
    {
        return $this->additionalEvents;
    }

    /**
     * @param Event $event
     */
    protected function addAdditionalEvent(Event $event)
    {
        $this->additionalEvents[] = $event;
    }

    /**
     * @return Id
     */
    protected function generateEventId(): Id
    {
        return new Id($this->uuidGenerator->generate()->toNative());
    }

    /**
     * @return \DateTime
     */
    protected function generateDateTime(): \DateTime
    {
        return new \DateTime();
    }

}