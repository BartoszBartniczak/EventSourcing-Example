<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Product;


use BartoszBartniczak\ArrayObject\ArrayOfObjects;

class ProductArrayTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Product\ProductArray::__construct
     */
    public function testConstructor()
    {
        $productArray = new ProductArray();
        $this->assertInstanceOf(ArrayOfObjects::class, $productArray);
        $this->assertSame(Product::class, $productArray->getClassName());
    }

}
