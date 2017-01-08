<?php
/**
 * Created by PhpStorm.
 * User: Bartosz Bartniczak <kontakt@bartoszbartniczak.pl>
 */

namespace BartoszBartniczak\EventSourcing\Shop\UUID;


use Ramsey\Uuid\Uuid as RamseyUUID;

class RamseyGenerator implements Generator
{
    /**
     * @return UUID
     */
    public function generate(): UUID
    {
        return new UUID(RamseyUUID::uuid4());
    }


}