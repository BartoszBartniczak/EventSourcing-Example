<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\Password;


class HashInfo extends \ArrayObject
{

    /**
     * @var int
     */
    private $algorithm;

    /**
     * @var string
     */
    private $algorithmName;

    /**
     * @var int
     */
    private $cost;

    /**
     * HashInfo constructor.
     * @param int $algorithm
     * @param string $algorithmName
     * @param int $cost
     */
    public function __construct(int $algorithm, string $algorithmName, int $cost)
    {
        $this->algorithm = $algorithm;
        $this->algorithmName = $algorithmName;
        $this->cost = $cost;
    }

    /**
     * @return int
     */
    public function getAlgorithm(): int
    {
        return $this->algorithm;
    }

    /**
     * @return string
     */
    public function getAlgorithmName(): string
    {
        return $this->algorithmName;
    }

    /**
     * @return int
     */
    public function getCost(): int
    {
        return $this->cost;
    }

}