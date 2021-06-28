<?php
declare(strict_types=1);

namespace stk2k\filesystem\Exception;

use Throwable;

use stk2k\filesystem\File;

class NotFileException extends FileSystemException
{
    /**
     * NotFileException constructor.
     *
     * @param File $file
     * @param Throwable|null $prev
     */
    public function __construct(File $file, Throwable $prev = null)
    {
        $message = "Not file: $file";
        parent::__construct($message, $prev);
    }
}

