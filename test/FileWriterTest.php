<?php
declare(strict_types=1);

namespace stk2k\filesystem\test;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use stk2k\filesystem\Exception\FileSystemException;
use stk2k\filesystem\File;

final class FileWriterTest extends TestCase
{
    public function testWrite()
    {
        try{
            vfsStream::setup("myrootdir");
            vfsStream::copyFromFileSystem(__DIR__ . '/files');

            $file = new File(vfsStream::url('myrootdir/a.txt'));
            $w = $file->openForWrite();     // default option truncates file content

            $w->write("test");

            $r = $file->openForRead();
            $this->assertEquals('test', $r->read(10000));

            $w = $file->openForWrite(File::FILE_WRITE_APPEND);

            $w->write("test");

            $r = $file->openForRead();
            $this->assertEquals('testtest', $r->read(10000));

            vfsStream::copyFromFileSystem(__DIR__ . '/files');

            $w = $file->openForWrite(File::FILE_WRITE_APPEND);

            $w->write("test");

            $r = $file->openForRead();
            $this->assertEquals(file_get_contents('test/files/a.txt') . 'test', $r->read(10000));
        }
        catch(FileSystemException $ex)
        {
            $this->fail($ex->getMessage());
        }
    }
}