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
     * @param int $code
     * @param Throwable|null $prev
     */
    public function __construct(File $file, int $code = 0, Throwable $prev = null)
    {
        $message = "Not file: $file";
        parent::__construct($message, $code, $prev);
    }
}

