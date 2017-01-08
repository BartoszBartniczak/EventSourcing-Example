<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\UUID;


class RamseyGeneratorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\UUID\RamseyGenerator::generate
     */
    public function testGenerate()
    {

        $ramseyGenerator = new RamseyGenerator();
        $this->assertNotEmpty($ramseyGenerator->generate());
        $this->assertNotEquals($ramseyGenerator->generate(), $ramseyGenerator->generate());
    }

}
