<?php
declare(strict_types=1);

namespace stk2k\filesystem\Exception;

use Throwable;

use stk2k\filesystem\File;

class FileCopyException extends FileSystemException
{
    /**
     * FileCopyException constructor.
     *
     * @param File $from_file
     * @param File $to_file
     * @param int $code
     * @param Throwable|null $prev
     */
    public function __construct(File $from_file, File $to_file, int $code = 0, Throwable $prev = null)
    {
        $message = "Copying file failed: {$from_file} to {$to_file}";
        parent::__construct($message, $code, $prev);
    }
}
