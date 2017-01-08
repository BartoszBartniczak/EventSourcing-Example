<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Password;


class HashInfoTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \BartoszBartniczak\EventSourcing\Shop\Password\HashInfo::__construct
     * @covers \BartoszBartniczak\EventSourcing\Shop\Password\HashInfo::getAlgorithm
     * @covers \BartoszBartniczak\EventSourcing\Shop\Password\HashInfo::getAlgorithmName
     * @covers \BartoszBartniczak\EventSourcing\Shop\Password\HashInfo::getCost
     */
    public function testGetters()
    {
        $hashInfo = new HashInfo(1, 'bcrypt', 10);
        $this->assertSame(1, $hashInfo->getAlgorithm());
        $this->assertEquals('bcrypt', $hashInfo->getAlgorithmName());
        $this->assertSame(10, $hashInfo->getCost());
    }

}
