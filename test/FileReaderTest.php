<?php
declare(strict_types=1);

namespace stk2k\filesystem\test;

use PHPUnit\Framework\TestCase;
use stk2k\filesystem\Exception\FileInputException;
use stk2k\filesystem\Exception\FileOperatorException;
use stk2k\filesystem\Exception\FileReaderException;
use stk2k\filesystem\File;

final class FileReaderTest extends TestCase
{
    public function testIsEof()
    {
        try{
            $file = new File('test/files/a.txt');
            $r = $file->openForRead();

            $this->assertEquals(false, $r->isEOF());

            $r->read(10000);

            $this->assertEquals(true, $r->isEOF());
        }
        catch(FileInputException|FileReaderException $ex)
        {
            $this->fail($ex->getMessage());
        }
    }
    public function testRead()
    {
        try{
            $file = new File('test/files/a.txt');
            $r = $file->openForRead();

            $this->assertEquals(false, $r->isEOF());

            $data = $r->read(20);

            $this->assertEquals('PHP is a popular gen', $data);

            $r->rewind();
            $data = $r->read(999);

            $this->assertEquals(file_get_contents('test/files/a.txt'), $data);
        }
        catch(FileInputException|FileOperatorException $ex)
        {
            $this->fail($ex->getMessage());
        }
    }
    public function testGetChar()
    {
        try{
            $file = new File('test/files/a.txt');
            $r = $file->openForRead();

            $this->assertEquals(false, $r->isEOF());

            $this->assertEquals('P', $r->getChar());
            $this->assertEquals('H', $r->getChar());
            $this->assertEquals('P', $r->getChar());
            $this->assertEquals(' ', $r->getChar());

            $file = new File('test/files/b.txt');
            $r = $file->openForRead();

            $this->assertEquals(false, $r->isEOF());

            $this->assertEquals(null, $r->getChar());
            $this->assertEquals(true, $r->isEOF());
        }
        catch(FileInputException|FileReaderException $ex)
        {
            $this->fail($ex->getMessage());
        }
    }
    public function testGetLine()
    {
        try{
            $file = new File('test/files/a.txt');
            $r = $file->openForRead();

            $this->assertEquals(false, $r->isEOF());

            $this->assertEquals("PHP is a popular general-purpose scripting language that is especially suited to web development.\n", $r->getLine());
            $this->assertEquals("\n", $r->getLine());
            $this->assertEquals('Fast, flexible and pragmatic, PHP powers everything from your blog to the most popular websites in the world.', $r->getLine());
            $this->assertEquals(null, $r->getLine());

            $file = new File('test/files/b.txt');
            $r = $file->openForRead();

            $this->assertEquals(false, $r->isEOF());

            $this->assertEquals(null, $r->getLine());
            $this->assertEquals(true, $r->isEOF());
        }
        catch(FileInputException|FileReaderException $ex)
        {
            $this->fail($ex->getMessage());
        }
    }
}