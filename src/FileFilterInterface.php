<?php
declare(strict_types=1);

namespace stk2k\filesystem;

interface FileFilterInterface
{
    /**
     * Check if the filter select the specified file.
     *
     * @param File $file         Target fileto be tested.
     *
     * @return bool
     */
    public function accept( File $file ) : bool;

}

