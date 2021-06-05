<?php
declare(strict_types=1);

namespace Stk2k\FileSystem\Filter;

use Stk2k\FileSystem\FileFilterInterface;
use Stk2k\FileSystem\File;

class CombinedFileFilter implements FileFilterInterface
{
    /** @var FileFilterInterface[]  */
    private $filters;

    /**
     * Construct object
     *
     * @param FileFilterInterface[] $filters      Array of file filters. All of the elements must implement Charcoal_IFileFilter interface.
     */
    public function __construct( array $filters )
    {
        $this->filters = $filters;
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
        foreach( $this->filters as $filter ){
            if ( $filter->accept($file) ){
                return TRUE;
            }
        }

        return FALSE;
    }
}


