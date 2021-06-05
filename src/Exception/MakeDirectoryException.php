<?php
declare(strict_types=1);

namespace Stk2k\FileSystem\Exception;

use Throwable;

use Stk2k\FileSystem\File;

class MakeDirectoryException extends FileSystemException
{
    /**
     * MakeDirectoryException constructor.
     *
     * @param File $file
     * @param int $code
     * @param Throwable|NULL $prev
     */
    public function __construct( File $file, int $code = 0, Throwable $prev = NULL )
    {
        $message = "Making directory failed: $file";
        parent::__construct($message, $code, $prev);
    }
}

