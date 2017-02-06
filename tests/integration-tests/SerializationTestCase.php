<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop;

use BartoszBartniczak\EventSourcing\Event\Event;
use BartoszBartniczak\EventSourcing\Event\Id as EventId;
use BartoszBartniczak\EventSourcing\Event\Serializer\JMSJsonSerializer;
use BartoszBartniczak\EventSourcing\Event\Serializer\Serializer;
use BartoszBartniczak\EventSourcing\UUID\Generator as UUIDGenerator;
use BartoszBartniczak\EventSourcing\UUID\RamseyGeneratorAdapter;
use JMS\Serializer\Naming\CamelCaseNamingStrategy;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\Finder\Finder;

abstract class SerializationTestCase extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @var UUIDGenerator
     */
    protected $uuidGenerator;

    final public function testOutputJson()
    {
        $this->assertIdentical($this->loadJsonFromFile($this->getJsonFileName()), $this->getJson());
    }

    public function assertIdentical($expected, $actual)
    {
        $expected = $this->stripWhitespaces($expected);
        $actual = $this->stripWhitespaces($actual);
        $this->assertSame($expected, $actual);
    }

    protected function stripWhitespaces(string $string): string
    {
        return preg_replace('/\s+/', '', $string);
    }

    protected function loadJsonFromFile(string $name): string
    {
        $finder = new Finder();
        $files = $finder->in(__DIR__)
            ->files()
            ->name($name);
        foreach ($files as $file) {
            /* @var $file \Symfony\Component\Finder\SplFileInfo */
            return $file->getContents();
        }
        throw new \InvalidArgumentException('Cannot find file.');
    }

    abstract protected function getJsonFileName(): string;

    final protected function getJson(): string
    {
        return $this->serializer->serialize($this->getEvent());
    }

    abstract protected function getEvent(): Event;

    protected function setUp()
    {
        parent::setUp();
        $propertyNamingStrategy = new CamelCaseNamingStrategy();

        $jmsSerializer = SerializerBuilder::create()
            ->setPropertyNamingStrategy($propertyNamingStrategy)
            ->addMetadataDir(__DIR__ . '/../../config/serializer', "BartoszBartniczak\EventSourcing\Shop")
            ->addMetadataDir(__DIR__ . '/../../config/serializer', "BartoszBartniczak\EventSourcing")
            ->addMetadataDir(__DIR__ . '/../../config/serializer', "BartoszBartniczak")
            ->build();
        $this->serializer = new JMSJsonSerializer($jmsSerializer, $propertyNamingStrategy);

        $this->uuidGenerator = new RamseyGeneratorAdapter();

    }

    protected function generateEventId(string $uuid = ''): EventId
    {

        if (empty($uuid)) {
            $uuid = $this->uuidGenerator->generate()->toNative();
        }

        return new EventId($uuid);
    }

    protected function generateDateTime(string $dateTime): \DateTime
    {

        if (empty($dateTime)) {
            return new \DateTime();
        } else {
            return new \DateTime($dateTime);
        }

    }

}
