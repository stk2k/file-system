<?php
declare(strict_types=1);

namespace stk2k\filesystem\Exception;

use Throwable;

class FileOperatorException extends FileSystemException
{
    /**
     * FileInputException constructor.
     *
     * @param string $message
     * @param Throwable|null $prev
     */
    public function __construct(string $message, Throwable $prev = NULL)
    {
        parent::__construct($message, $prev);
    }
}


