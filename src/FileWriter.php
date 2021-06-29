<?php /** @noinspection DuplicatedCode */
declare(strict_types=1);

namespace stk2k\filesystem;

use InvalidArgumentException;
use stk2k\filesystem\Exception\FileWriterException;
use stk2k\filesystem\Exception\FileOperatorException;

final class FileWriter extends FileOperatorBase
{
    /**
     * Lock file
     *
     * @param bool $block
     *
     * @throws FileOperatorException
     * @throws FileWriterException
     */
    public function lock(bool $block = false) : void
    {
        if ($this->isClosed()){
            throw new FileWriterException('File operator is already closed: ' . $this->getFile());
        }
        parent::lock($block);
        $lock = LOCK_EX;
        if ($block){
            $lock |= LOCK_NB;
        }
        $ret = flock($this->getFilePointer(), $lock);
        if ($ret === false){
            throw new FileWriterException('Failed to lock file: ' . $this->getFile());
        }
    }

    /**
     * Flushes file buffer
     *
     * @throws FileWriterException
     */
    public function flush() : void
    {
        if ($this->isClosed()){
            throw new FileWriterException('File operator is already closed: ' . $this->getFile());
        }
        $ret = fflush($this->getFilePointer());
        if ($ret === false){
            throw new FileWriterException('Failed to flush file: ' . $this->getFile());
        }
    }

    /**
     * Writes file
     *
     * @param string $data
     * @param int|null $length
     *
     * @return int
     * @throws FileWriterException
     */
    public function write(string $data, int $length = null) : int
    {
        if ($this->isClosed()){
            throw new FileWriterException('File operator is already closed: ' . $this->getFile());
        }
        if (is_int($length) && $length <= 0){
            throw new InvalidArgumentException('Length paramter must be greater than 0: ' . (string)$length);
        }
        $ret = $length === null ? fwrite($this->getFilePointer(), $data) : fwrite($this->getFilePointer(), $data, $length);
        if ($ret === false){
            throw new FileWriterException('Failed to write file: ' . $this->getFile());
        }
        return $ret;
    }
}