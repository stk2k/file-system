<?php
declare(strict_types=1);

namespace Stk2k\FileSystem\Filter;

use Stk2k\FileSystem\FileFilterInterface;
use Stk2k\FileSystem\File;

class IsDirectoryFileFilter implements FileFilterInterface
{
    /**
     * Check if the filter select the specified file.
     *
     * @param File $file         Target fileto be tested.
     *
     * @return bool
     */
    public function accept( File $file ) : bool
    {
        return $file->isDir();
    }
}