<?php
declare(strict_types=1);

namespace Stk2k\FileSystem\Test;

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