<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

use BartoszBartniczak\EventSourcing\Event\Serializer\SymfonyJsonSerializer;
use BartoszBartniczak\EventSourcing\Shop\Application\Order\Position\PositionArray\Normalizer as PositionArrayNormalizer;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\YamlFileLoader;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;

$classMetadataFactory = new ClassMetadataFactory(new YamlFileLoader(__DIR__ . '/config/serializer/definition.yml'));

if (!class_exists('UUIDNormalizer')) {
    class UUIDNormalizer implements \Symfony\Component\Serializer\Normalizer\NormalizerInterface, \Symfony\Component\Serializer\Normalizer\DenormalizerInterface
    {
        const UUID_KEY_NAME = 'uuid';
        const CLASS_NAME_KEY_NAME = 'className';

        public function normalize($object, $format = null, array $context = array())
        {
            return [
                self::UUID_KEY_NAME => $object->toNative(),
                self::CLASS_NAME_KEY_NAME => get_class($object),
            ];
        }

        public function supportsNormalization($data, $format = null)
        {
            return $data instanceof \BartoszBartniczak\EventSourcing\UUID\UUID;
        }

        public function denormalize($data, $class, $format = null, array $context = array())
        {
            $uuid = new $class($data[self::UUID_KEY_NAME]);
            return $uuid;
        }

        public function supportsDenormalization($data, $type, $format = null)
        {
            if (!is_array($data)) {
                return false;
            }

            if (isset($data[self::CLASS_NAME_KEY_NAME]) && isset($data[self::UUID_KEY_NAME])) {
                return true;
            }
            return false;

        }


    }
}

$symfonySerializer = new SymfonySerializer(
    [
        new PositionArrayNormalizer(),
        new \BartoszBartniczak\SymfonySerializer\Normalizer\ArrayOfObjectsNormalizer(),
        new \BartoszBartniczak\SymfonySerializer\Normalizer\ArrayObjectNormalizer(),
        new \Symfony\Component\Serializer\Normalizer\DateTimeNormalizer(),
        new UUIDNormalizer(),
        new \Symfony\Component\Serializer\Normalizer\ObjectNormalizer($classMetadataFactory),
    ],
    [
        new \Symfony\Component\Serializer\Encoder\JsonEncoder()
    ]
);
$serializer = new SymfonyJsonSerializer($symfonySerializer, ['event']);

return $serializer;