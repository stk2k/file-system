<?php
declare(strict_types=1);

namespace stk2k\filesystem\test;

use PHPUnit\Framework\TestCase;
use stk2k\filesystem\Exception\FileInputException;
use stk2k\filesystem\Exception\FileOperatorException;
use stk2k\filesystem\File;
use stk2k\filesystem\test\classes\FileOperator;

final class FileOperatorBaseTest extends TestCase
{
    public function testClose()
    {
        try{
            $file = new File('test/files/a.txt');
            $r = $file->openForRead();

            $this->assertEquals(false, $r->isClosed());

            $r->close();

            $this->assertEquals(true, $r->isClosed());
        }
        catch(FileInputException $ex)
        {
            $this->fail($ex->getMessage());
        }
    }
    public function testRewind()
    {
        try{
            $file = new File(__DIR__ . '/files/a.txt');
            $op = new FileOperator(fopen($file->getPath(), 'r'), $file);

            $op->seekToEnd();

            $this->assertEquals(208, $op->tell());

            $op->rewind();

            $this->assertEquals(0, $op->tell());
        }
        catch(FileOperatorException $ex)
        {
            $this->fail($ex->getMessage());
        }
    }
    public function testTell()
    {
        try{
            $file = new File(__DIR__ . '/files/a.txt');
            $op = new FileOperator(fopen($file->getPath(), 'r'), $file);

            $op->seekToEnd();

            $this->assertEquals(208, $op->tell());

            $op->rewind();

            $this->assertEquals(0, $op->tell());

            $op->seekToStart();

            $this->assertEquals(0, $op->tell());

            $op->seek(3);
            $op->seek(3);

            $this->assertEquals(6, $op->tell());

        }
        catch(FileOperatorException $ex)
        {
            $this->fail($ex->getMessage());
        }
    }
}