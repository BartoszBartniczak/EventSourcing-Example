<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Command\Bus;


use BartoszBartniczak\CQRS\Command\Bus\CannotExecuteTheCommandException;
use BartoszBartniczak\CQRS\Command\Bus\CannotFindHandlerException;
use BartoszBartniczak\CQRS\Command\Bus\CommandBus as BasicCommandBus;
use BartoszBartniczak\CQRS\Command\Command;
use BartoszBartniczak\CQRS\Command\Handler\CommandHandler as BasicCommandHandler;
use BartoszBartniczak\CQRS\Command\Query;
use BartoszBartniczak\EventSourcing\Shop\Command\Handler\CommandHandler;
use BartoszBartniczak\EventSourcing\Shop\Event\Bus\EventBus;
use BartoszBartniczak\EventSourcing\Shop\Event\EventStream;
use BartoszBartniczak\EventSourcing\Shop\Event\Repository\EventRepository;
use BartoszBartniczak\EventSourcing\Shop\EventAggregate\EventAggregate;
use BartoszBartniczak\EventSourcing\Shop\UUID\Generator as UUIDGenerator;

class CommandBus extends BasicCommandBus
{

    /**
     * @var EventBus
     */
    protected $eventBus;
    /**
     * @var array
     */
    private $eventRepository;
    /**
     * @var UUIDGenerator
     */
    private $generator;

    /**
     * CommandBus constructor.
     * @param UUIDGenerator $generator
     * @param EventRepository $eventRepository
     * @param EventBus $eventBus
     */
    public function __construct(UUIDGenerator $generator, EventRepository $eventRepository, EventBus $eventBus)
    {
        parent::__construct();
        $this->eventRepository = $eventRepository;
        $this->generator = $generator;
        $this->eventBus = $eventBus;
    }

    /**
     * @inheritDoc
     */
    public function registerHandler(string $commandClassName, BasicCommandHandler $commandHandler)
    {
        if (!$commandHandler instanceof CommandHandler) {
            throw new InvalidArgumentException(sprintf("CommandHandler has to be instance of: '%s'.", CommandHandler::class));
        }

        parent::registerHandler($commandClassName, $commandHandler);
    }


    /**
     * @param Query $query
     * @throws CannotExecuteTheCommandException
     * @throws CannotFindHandlerException
     * @return mixed
     */
    protected function executeQuery(Query $query)
    {
        $this->clearOutput();
        $handler = $this->findHandler($query);
        /* @var $handler CommandHandler */
        $eventAggregate = $this->tryToHandleCommand($query, $handler);

        $this->saveOutput($eventAggregate);

        $additionalEvents = $handler->getAdditionalEvents();
        $this->eventRepository->saveEventStream($additionalEvents);

        $this->eventBus->emmit($additionalEvents);
        return $this->getOutput();
    }

    /**
     * @param BasicCommandHandler $handler
     * @throws CannotExecuteTheCommandException
     */
    protected function handleHandlerException(BasicCommandHandler $handler)
    {
        /* @var $handler CommandHandler */
        $additionalEvents = $handler->getAdditionalEvents();
        $this->eventRepository->saveEventStream($additionalEvents);
        $this->eventBus->emmit($additionalEvents);
    }

    /**
     * @param Command $command
     * @throws CannotExecuteTheCommandException
     * @throws CannotFindHandlerException
     */
    protected function executeCommand(Command $command)
    {
        $handler = $this->findHandler($command);
        /* @var $handler CommandHandler */
        $data = $this->tryToHandleCommand($command, $handler);

        if ($data instanceof EventAggregate) {
            $eventsToEmmit = clone $data->getUncommittedEvents();
            $this->saveDataInRepository($data);
        } else {
            $eventsToEmmit = new EventStream();
        }

        $additionalEvents = $handler->getAdditionalEvents();
        $this->eventRepository->saveEventStream($additionalEvents);

        $eventsToEmmit->merge($additionalEvents);
        $this->eventBus->emmit($eventsToEmmit);

        $this->executeNextCommands($handler->getNextCommands());
    }

    /**
     * @param $data
     */
    protected function saveDataInRepository($data)
    {
        if ($data instanceof EventAggregate) {
            $this->eventRepository->saveEventAggregate($data);
        }
    }


}