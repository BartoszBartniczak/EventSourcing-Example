<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Application\Order\Position\PositionArray;


use BartoszBartniczak\ArrayObject\ArrayOfObjects;
use BartoszBartniczak\EventSourcing\Shop\Order\Position\Position;
use BartoszBartniczak\EventSourcing\Shop\Order\Position\PositionArray\KeyNamingStrategy;
use BartoszBartniczak\EventSourcing\Shop\Order\Position\PositionArray\PositionArray;
use BartoszBartniczak\EventSourcing\Shop\Order\Position\PositionArray\ProductIdStrategy;
use BartoszBartniczak\EventSourcing\Shop\Product\Product;
use BartoszBartniczak\SymfonySerializer\Normalizer\ArrayOfObjectsNormalizer;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

interface SerializerDenormalizerInterface extends SerializerInterface, DenormalizerInterface
{
}

class NormalizerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Normalizer
     */
    protected $normalizer;

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Application\Order\Position\PositionArray\Normalizer::__construct
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(ArrayOfObjectsNormalizer::class, $this->normalizer);
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Application\Order\Position\PositionArray\Normalizer::supportsNormalization
     */
    public function testSupportsNormalization()
    {
        $keyNamingStrategy = $this->getMockBuilder(KeyNamingStrategy::class)
            ->getMockForAbstractClass();
        /* @var $keyNamingStrategy KeyNamingStrategy */

        $this->assertTrue($this->normalizer->supportsNormalization(new PositionArray($keyNamingStrategy)));

        $this->assertFalse($this->normalizer->supportsNormalization(new ArrayOfObjects(\DateTime::class)));
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Application\Order\Position\PositionArray\Normalizer::normalize
     */
    public function testNormalize()
    {

        $normalizerInterface = $this->getMockBuilder(NormalizerInterface::class)
            ->setMethods([
                'normalize'
            ])
            ->getMockForAbstractClass();
        $normalizerInterface->expects($this->once())
            ->method('normalize')
            ->with([
                Normalizer::PROPERTY_CLASSNAME => Position::class,
                Normalizer::PROPERTY_KEY_NAMING_STRATEGY => [
                    'className' => 'KeyNamingStrategyMock'
                ],
                Normalizer::PROPERTY_STORAGE => []
            ])
            ->willReturn('result_mock');
        /* @var $normalizerInterface NormalizerInterface */

        $this->normalizer->setNormalizer($normalizerInterface);

        $keyNamingStrategy = $this->getMockBuilder(KeyNamingStrategy::class)
            ->setMockClassName('KeyNamingStrategyMock')
            ->getMockForAbstractClass();
        /* @var $keyNamingStrategy KeyNamingStrategy */

        $result = $this->normalizer->normalize(new PositionArray($keyNamingStrategy));
        $this->assertSame('result_mock', $result);
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Application\Order\Position\PositionArray\Normalizer::supportsDenormalization
     */
    public function testSupportsDenormalization()
    {

        $this->assertTrue($this->normalizer->supportsDenormalization(null, PositionArray::class));

        $this->assertFalse($this->normalizer->supportsDenormalization(null, ArrayOfObjects::class));
    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Application\Order\Position\PositionArray\Normalizer::denormalize
     */
    public function testDenormalize()
    {
        $product = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();
        /* @var $product Product */

        $position1 = new Position($product, 100);
        $position2 = new Position($product, 200);

        $serializerInterface = $this->getMockBuilder(SerializerDenormalizerInterface::class)
            ->setMethods([
                'denormalize'
            ])
            ->getMockForAbstractClass();

        $serializerInterface
            ->expects($this->at(0))
            ->method('denormalize')
            ->with('position1', Position::class)
            ->willReturn($position1);

        $serializerInterface
            ->expects($this->at(1))
            ->method('denormalize')
            ->with('position2', Position::class)
            ->willReturn($position2);
        /* @var $serializerInterface SerializerDenormalizerInterface */

        $this->normalizer->setSerializer($serializerInterface);

        $result = $this->normalizer->denormalize([
            Normalizer::PROPERTY_CLASSNAME => Position::class,
            Normalizer::PROPERTY_KEY_NAMING_STRATEGY => [
                'className' => ProductIdStrategy::class
            ],
            Normalizer::PROPERTY_STORAGE => [
                'position1',
                'position2'
            ]
        ], PositionArray::class);

        $keyNamingStrategy = new ProductIdStrategy();

        $positionArray = new PositionArray($keyNamingStrategy, [
            $position1,
            $position2
        ]);

        $this->assertEquals($positionArray, $result);

    }

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Application\Order\Position\PositionArray\Normalizer::denormalize
     */
    public function testDenormalizeThrowsUnexpectedValueExceptionIfKeyIsNotSameTypeAsExpectedInContext()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('The type of the key "pos1" must be "int" ("string" given).');

        $serializerDeserializerInterface = $this->getMockBuilder(SerializerDenormalizerInterface::class)
            ->getMockForAbstractClass();
        /* @var $serializerDeserializerInterface SerializerDenormalizerInterface */
        $this->normalizer->setSerializer($serializerDeserializerInterface);

        $this->normalizer->denormalize([
            Normalizer::PROPERTY_CLASSNAME => Position::class,
            Normalizer::PROPERTY_KEY_NAMING_STRATEGY => [
                'className' => ProductIdStrategy::class
            ],
            Normalizer::PROPERTY_STORAGE => [
                'pos1' => 'position1',
                'pos2' => 'position2'
            ]
        ],
            PositionArray::class,
            'json', ['key_type' => new class()
            {
                public function getBuiltinType()
                {
                    return "int";

                }
            }]);
    }

    protected function setUp()
    {
        $this->normalizer = new Normalizer();
    }

}
