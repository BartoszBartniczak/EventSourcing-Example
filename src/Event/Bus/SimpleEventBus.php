<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Event\Bus;


use BartoszBartniczak\EventSourcing\Shop\Event\Event;
use BartoszBartniczak\EventSourcing\Shop\Event\EventStream;

class SimpleEventBus implements EventBus
{

    /**
     * @var array
     */
    private $handlers;

    public function __construct()
    {
        $this->handlers = [];
    }

    public function emmit(EventStream $eventStream)
    {
        foreach ($eventStream as $event) {
            $this->handle($event);
        }
    }

    protected function handle(Event $event)
    {
        try {
            $method = $this->findHandleMethod($event);
        } catch (\InvalidArgumentException $invalidArgumentException) {
            return;
        }
        $method($event);
    }

    /**
     * @param Event $event
     * @return callable
     * @throws \InvalidArgumentException
     */
    private function findHandleMethod(Event $event): callable
    {

        $className = get_class($event);
        if (isset($this->handlers[$className])) {
            return $this->handlers[$className];
        } else {
            throw new \InvalidArgumentException();
        }

    }

    /**
     * @param string $className
     * @param callable $callback
     */
    public function registerHandler(string $className, callable $callback)
    {
        $this->handlers[$className] = $callback;
    }

}