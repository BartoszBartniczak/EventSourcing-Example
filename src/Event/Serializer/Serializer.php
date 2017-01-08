<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Event\Serializer;


use BartoszBartniczak\EventSourcing\Shop\Event\Event;

interface Serializer
{

    public function serialize(Event $event);

    public function deserialize($data): Event;

    /**
     * The key naming strategy can be different every time. This function converts property name to property key.
     * @param string $propertyName
     * @return string
     */
    public function getPropertyKey(string $propertyName): string;

}