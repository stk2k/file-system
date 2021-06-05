<?php
declare(strict_types=1);

namespace Stk2k\FileSystem\Test;

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