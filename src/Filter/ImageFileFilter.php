<?php
declare(strict_types=1);

namespace stk2k\filesystem\Filter;

use stk2k\filesystem\FileFilterInterface;
use stk2k\filesystem\File;

class ImageFileFilter implements FileFilterInterface
{
    /** @var int[]  */
    private $image_types;
    
    /**
     * Construct object
     *
     * @param int[] $image_types
     */
    public function __construct(array $image_types)
    {
        $this->image_types  = $image_types;
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
        $res = @getimagesize($file->getPath());
        if (!$res || !is_array($res)){
            return false;
        }
        $imagetype = $res[2] ?? 0;
        return in_array($imagetype, $this->image_types);
    }
}