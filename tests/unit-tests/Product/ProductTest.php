<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Product;


class ProductTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Product\Product::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Product\Product::getId
     * @covers \BartoszBartniczak\EventSourcing\Shop\Product\Product::getName
     */
    public function testGetters()
    {
        $id = new Id(uniqid());

        $product = new Product($id, 'Milk');
        $this->assertSame($id, $product->getId());
        $this->assertSame('Milk', $product->getName());
    }

}
