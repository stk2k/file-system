<?php
declare(strict_types=1);

namespace stk2k\filesystem\Filter;

use stk2k\filesystem\FileFilterInterface;
use stk2k\filesystem\File;

class ExtensionFileFilter implements FileFilterInterface
{
    /** @var string  */
    private $extension;
    
    /**
     * Construct object
     *
     * @param string $extension
     */
    public function __construct(string $extension)
    {
        $this->extension  = $extension;
    }
    
    /**
     * Check if the filter select the specified file.
     *
     * @param File $file         Target fileto be tested.
     *
     * @return bool
     */
    public function accept( File $file ) : bool
    {
        return $file->isFile() && $file->getExtension() === $this->extension;
    }
}