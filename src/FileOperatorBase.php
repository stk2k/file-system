<?php /** @noinspection PhpUnusedParameterInspection */
declare(strict_types=1);

namespace stk2k\filesystem;

use stk2k\filesystem\Exception\FileOperatorException;

class FileOperatorBase
{
    /** @var resource */
    private $fp;

    /** @var File */
    private $file;

    /** @var bool */
    private $locked = false;

    /**
     * FileReader constructor.
     *
     * @param resource $fp
     * @param File $file
     */
    public function __construct($fp, File $file)
    {
        $this->fp = $fp;
        $this->file = $file;
    }

    /**
     * destructor
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Returns if file pointer reaches EOF
     *
     * @return bool
     */
    public function isEOF() : bool
    {
        return $this->isClosed() || feof($this->fp);
    }

    /**
     * Lock file
     *
     * @param bool $block
     *
     * @throws FileOperatorException
     */
    public function lock(bool $block = false) : void
    {
        if ($this->locked){
            throw new FileOperatorException('File is already locked: ' . $this->file);
        }
        $this->locked = true;
    }

    /**
     * Unlock file
     *
     * @throws FileOperatorException
     */
    public function unlock() : void
    {
        if (!$this->locked){
            return;
        }
        if (!$this->isClosed()){
            throw new FileOperatorException('File operator is already closed: ' . $this->getFile());
        }
        $this->locked = false;
        $ret = flock($this->fp, LOCK_UN);
        if ($ret === false){
            throw new FileOperatorException('Failed to unlock file: ' . $this->getFile());
        }
    }

    /**
     * Returns if file is locked
     *
     * @return bool
     */
    public function isLocked() : bool
    {
        return $this->locked;
    }

    /**
     * Returns file pointer
     *
     * @return resource
     */
    protected function getFilePointer()
    {
        return $this->fp;
    }

    /**
     * Returns file object
     *
     * @return File
     */
    public function getFile() : File
    {
        return $this->file;
    }

    /**
     * Returns if reader is closed
     *
     * @return bool
     */
    public function isClosed() : bool
    {
        return !$this->fp;
    }

    /**
     * Close file
     */
    public function close() : void
    {
        if ($this->fp){
            fclose($this->fp);
            $this->fp = null;
        }
    }

    /**
     * Returns file pointer position
     *
     * @return int
     * @throws FileOperatorException
     */
    public function tell() : int
    {
        if (!$this->fp){
            throw new FileOperatorException('File operator is already closed: ' . $this->file);
        }
        $ret = ftell($this->fp);
        if ($ret === false){
            throw new FileOperatorException('Failed to tell file: ' . $this->file);
        }
        return $ret;
    }

    /**
     * Rewind file pointer
     *
     * @throws FileOperatorException
     */
    public function rewind() : void
    {
        if (!$this->fp){
            throw new FileOperatorException('File operator is already closed: ' . $this->file);
        }
        $ret = rewind($this->fp);
        if ($ret === false){
            throw new FileOperatorException('Failed to rewind file: ' . $this->file);
        }
    }

    /**
     * Seek file pointer from start
     *
     * @param int $offset
     *
     * @throws FileOperatorException
     */
    public function seekToStart(int $offset = 0) : void
    {
        if (!$this->fp){
            throw new FileOperatorException('File operator is already closed: ' . $this->file);
        }
        $ret = fseek($this->fp, $offset, SEEK_SET);
        if ($ret === -1){
            throw new FileOperatorException('Failed to seek file to start: ' . $this->file);
        }
    }

    /**
     * Seek file pointer from current position
     *
     * @param int $offset
     *
     * @throws FileOperatorException
     */
    public function seek(int $offset) : void
    {
        if (!$this->fp){
            throw new FileOperatorException('File operator is already closed: ' . $this->file);
        }
        $ret = fseek($this->fp, $offset, SEEK_CUR);
        if ($ret === -1){
            throw new FileOperatorException('Failed to seek file from current: ' . $this->file);
        }
    }

    /**
     * Seek file pointer from start
     *
     * @param int $offset
     *
     * @throws FileOperatorException
     */
    public function seekToEnd(int $offset = 0) : void
    {
        if (!$this->fp){
            throw new FileOperatorException('File operator is already closed: ' . $this->file);
        }
        $ret = fseek($this->fp, $offset, SEEK_END);
        if ($ret === -1){
            throw new FileOperatorException('Failed to seek file to end: ' . $this->file);
        }
    }

}