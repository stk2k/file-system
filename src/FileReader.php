<?php /** @noinspection DuplicatedCode */
declare(strict_types=1);

namespace stk2k\filesystem;

use InvalidArgumentException;
use stk2k\filesystem\Exception\FileReaderException;

class FileReader extends FileOperatorBase
{
    /**
     * Lock file
     *
     * @param bool $block
     *
     * @throws Exception\FileOperatorException
     * @throws FileReaderException
     */
    public function lock(bool $block = false) : void
    {
        if ($this->isClosed()){
            throw new FileReaderException('File operator is already closed: ' . $this->getFile());
        }
        parent::lock($block);
        $lock = LOCK_SH;
        if ($block){
            $lock |= LOCK_NB;
        }
        $ret = flock($this->getFilePointer(), $lock);
        if ($ret === false){
            throw new FileReaderException('Failed to lock file: ' . $this->getFile());
        }
    }

    /**
     * Read file
     *
     * @param int $length
     *
     * @return string
     * @throws FileReaderException
     */
    public function read(int $length) : ?string
    {
        if ($this->isClosed()){
            throw new FileReaderException('File operator is already closed: ' . $this->getFile());
        }
        if ($length <= 0){
            throw new InvalidArgumentException('Length paramter must be greater than 0: ' . (string)$length);
        }
        $str = fread($this->getFilePointer(), $length);
        if ($str === false){
            return null;
        }
        return $str;
    }

    /**
     * Read a character
     *
     * @return string
     * @throws FileReaderException
     */
    public function getChar() : ?string
    {
        if ($this->isClosed()){
            throw new FileReaderException('File operator is already closed: ' . $this->getFile());
        }
        $str = fgetc($this->getFilePointer());
        if ($str === false){
            return null;
        }
        return $str;
    }

    /**
     * Read a character
     *
     * @param int|null $length
     *
     * @return string
     * @throws FileReaderException
     */
    public function getLine(int $length = null) : ?string
    {
        if ($this->isClosed()){
            throw new FileReaderException('File operator is already closed: ' . $this->getFile());
        }
        if ($length !== null && $length <= 0){
            throw new InvalidArgumentException('Length paramter must be greater than 0: ' . (string)$length);
        }
        $str = $length === null ? fgets($this->getFilePointer()) : fgets($this->getFilePointer(), $length);
        if ($str === false){
            return null;
        }
        return $str;
    }
}