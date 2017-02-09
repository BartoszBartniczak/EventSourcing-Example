<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Application\Order\Position\PositionArray;

use BartoszBartniczak\EventSourcing\Shop\Order\Position\PositionArray\PositionArray;
use BartoszBartniczak\SymfonySerializer\Normalizer\ArrayOfObjectsNormalizer;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;

class Normalizer extends ArrayOfObjectsNormalizer
{
    const PROPERTY_KEY_NAMING_STRATEGY = 'keyNamingStrategy';

    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === PositionArray::class;
    }


    /**
     * {@inheritdoc}
     *
     * @throws UnexpectedValueException
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        $this->checkRequirements($data, $class);

        $serializer = $this->serializer;

        $builtinType = isset($context['key_type']) ? $context['key_type']->getBuiltinType() : null;

        $subclass = $data[self::PROPERTY_CLASSNAME];

        if (isset($data[self::PROPERTY_STORAGE])) {
            foreach ($data[self::PROPERTY_STORAGE] as $key => $value) {
                if (null !== $builtinType && !call_user_func('is_' . $builtinType, $key)) {
                    throw new UnexpectedValueException(sprintf('The type of the key "%s" must be "%s" ("%s" given).', $key, $builtinType, gettype($key)));
                }

                $data[self::PROPERTY_STORAGE][$key] = $serializer->denormalize($value, $subclass, $format, $context);
            }
        }

        $keyNamingStrategy = new $data[self::PROPERTY_KEY_NAMING_STRATEGY]['className']();

        return new PositionArray($keyNamingStrategy, $data[self::PROPERTY_STORAGE]);
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof PositionArray;
    }

    public function normalize($object, $format = null, array $context = array())
    {
        /* @var $object PositionArray */

        return $this->normalizer->normalize([
            self::PROPERTY_CLASSNAME => $object->getClassName(),
            self::PROPERTY_KEY_NAMING_STRATEGY => ['className' => get_class($object->getKeyNamingStrategy())],
            self::PROPERTY_STORAGE => $object->getArrayCopy(),
        ]);
    }


}