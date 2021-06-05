<?php
declare(strict_types=1);

namespace stk2k\filesystem\Exception;

use Throwable;

use stk2k\filesystem\File;

class FileRenameException extends FileSystemException
{
    /**
     * FileRenameException constructor.
     *
     * @param File $old_file
     * @param File $new_file
     * @param int $code
     * @param Throwable|null $prev
     */
    public function __construct( File $old_file, File $new_file, int $code = 0, Throwable $prev = NULL )
    {
        $message = "File renaming failed: {$old_file} to {$new_file}";
        parent::__construct($message, $code, $prev);
    }
}


