<?php
declare(strict_types=1);

namespace stk2k\filesystem\Exception;

use Throwable;

use stk2k\filesystem\File;

class NotDirectoryException extends FileSystemException
{
    /**
     * NotDirectoryException constructor.
     *
     * @param File $file
     * @param Throwable|null $prev
     */
    public function __construct( File $file, Throwable $prev = null)
    {
        $message = "Not directory: $file";
        parent::__construct($message, $prev);
    }
}

