<?php
declare(strict_types=1);

namespace Stk2k\FileSystem\Exception;

use Throwable;

use Stk2k\FileSystem\File;

class FileInputException extends FileSystemException
{
    /**
     * FileInputException constructor.
     *
     * @param File $file
     * @param string $message
     * @param int $code
     * @param Throwable|null $prev
     */
    public function __construct(File $file, string $message, int $code = 0, Throwable $prev = NULL)
    {
        $message = $message . ' at file: ' . $file;
        parent::__construct($message, $code, $prev);
    }
}


