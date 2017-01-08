<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Event\Serializer;

use BartoszBartniczak\EventSourcing\Shop\Event\Event;
use JMS\Serializer\Metadata\StaticPropertyMetadata;
use JMS\Serializer\Naming\PropertyNamingStrategyInterface;
use JMS\Serializer\Serializer as JMSSerializer;


class JMSJsonSerializer implements Serializer
{

    /**
     * @var JMSSerializer
     */
    private $jmsSerializer;

    /**
     * @var PropertyNamingStrategyInterface
     */
    private $namingStrategy;

    /**
     * JMSJsonSerializer constructor.
     * @param JMSSerializer $jmsSerializer
     * @param PropertyNamingStrategyInterface $namingStrategy
     */
    public function __construct(JMSSerializer $jmsSerializer, PropertyNamingStrategyInterface $namingStrategy)
    {
        $this->jmsSerializer = $jmsSerializer;
        $this->namingStrategy = $namingStrategy;
    }

    /**
     * @param Event $event
     * @return string
     */
    public function serialize(Event $event): string
    {
        return $this->jmsSerializer->serialize($event, 'json');
    }

    /**
     * @param $data
     * @return Event
     */
    public function deserialize($data): Event
    {
        $className = $this->tryToExtractClassName($data);
        return $this->jmsSerializer->deserialize($data, $className, 'json');
    }

    /**
     * @param $data
     * @return string
     */
    private function tryToExtractClassName($data): string
    {
        $data = json_decode($data, true);
        if (!isset($data['name'])) {
            throw new InvalidArgumentException('Cannot extract class name of the event');
        }
        return $data['name'];
    }

    public function getPropertyKey(string $propertyName): string
    {
        $propertyMetadata = new StaticPropertyMetadata('string', $propertyName, '');
        return $this->namingStrategy->translateName($propertyMetadata);
    }


}