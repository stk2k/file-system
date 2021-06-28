<?php
declare(strict_types=1);

namespace stk2k\filesystem\Exception;

use Throwable;

use stk2k\filesystem\File;

class FileIsNotReadableException extends FileSystemException
{
    /**
     * FileIsNotReadableException constructor.
     *
     * @param File $file
     * @param Throwable|null $prev
     */
    public function __construct( File $file, Throwable $prev = NULL )
    {
        $message = "Specified file is not readable: $file";
        parent::__construct($message, $prev);
    }
}


