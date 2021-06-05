<?php
declare(strict_types=1);

namespace Stk2k\FileSystem\Test;

final class StringableObject
{
    private $values;

    public function __construct(array $values)
    {
        $this->values = $values;
    }

    public function __toString() : string
    {
        return implode(',', $this->values);
    }
}