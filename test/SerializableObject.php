<?php
declare(strict_types=1);

namespace stk2k\filesystem\test;

use Exception;

final class SerializableObject implements \Serializable
{
    private $values;

    public function __construct(array $values)
    {
        $this->values = $values;
    }

    public function serialize() : ?string
    {
        return serialize($this->values);
    }

    public function unserialize($data)
    {
    }

}