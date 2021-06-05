<?php
declare(strict_types=1);

namespace stk2k\filesystem\test;

final class JsonSerializableObject implements \JsonSerializable
{
    private $values;

    public function __construct(array $values)
    {
        $this->values = $values;
    }

    public function jsonSerialize()
    {
        return $this->values;
    }

}