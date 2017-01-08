<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\UUID;


class UUID
{

    /**
     * @var string
     */
    private $uuid;

    /**
     * UUID constructor.
     * @param $uuid
     */
    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return string
     */
    public function toNative(): string
    {
        return $this->uuid;
    }

}