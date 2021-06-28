<?php
declare(strict_types=1);

namespace stk2k\filesystem\Exception;

use Throwable;

use stk2k\filesystem\File;

class FileOutputException extends FileSystemException
{
    /**
     * FileOutputException constructor.
     *
     * @param File $file
     * @param string $message
     * @param Throwable|null $prev
     */
    public function __construct(File $file, string $message, Throwable $prev = null)
    {
        $message = $message . ' at file: ' . $file;
        parent::__construct($message, $prev);
    }
}


