<?php
declare(strict_types=1);

namespace stk2k\filesystem\Exception;

use Throwable;

use stk2k\filesystem\File;

class MakeFileException extends FileSystemException
{
    /**
     * MakeFileException constructor.
     *
     * @param File $file
     * @param Throwable|NULL $prev
     */
    public function __construct( File $file, Throwable $prev = NULL )
    {
        $message = "Making file failed: $file";
        parent::__construct($message, $prev );
    }
}


