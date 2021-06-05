<?php
declare(strict_types=1);

namespace stk2k\filesystem\test;

use PHPUnit\Framework\TestCase;

use org\bovigo\vfs\vfsStream;

use stk2k\filesystem\File;
use stk2k\filesystem\FileSystem;
use stk2k\filesystem\Filter\ExtensionFileFilter;
use stk2k\filesystem\Filter\ImageFileFilter;
use stk2k\filesystem\Filter\IsDirectoryFileFilter;
use stk2k\filesystem\Filter\IsFileFileFilter;
use stk2k\filesystem\Exception\MakeDirectoryException;
use stk2k\filesystem\Exception\FileInputException;
use stk2k\filesystem\Exception\FileOutputException;
use stk2k\filesystem\Exception\FileRenameException;

class FileSystemTest extends TestCase
{
    private static function getFileList(array $files) : array
    {
        $ret = [];
        foreach($files as $file){
            /** @var File $file */
            $ret[] = $file->getName();
        }
        sort($ret);
        return $ret;
    }
    public function testListFiles()
    {
        $path = __DIR__ . '/files';

        // filterless
        $files = FileSystem::listFiles($path);
        $this->assertEquals([
            'a.txt',
            'b.txt',
            'c.sql',
            'dangohiyoko.png',
            'neko.jpg',
            'piyopiyo.gif',
            'x',
            'y',
            'z',
        ], self::getFileList($files));

        // directory filter
        $files = FileSystem::listFiles($path, new IsDirectoryFileFilter());
        $this->assertEquals([
            'x',
            'y',
            'z',
        ], self::getFileList($files));

        // file filter
        $files = FileSystem::listFiles($path, new IsFileFileFilter());
        $this->assertEquals([
            'a.txt',
            'b.txt',
            'c.sql',
            'dangohiyoko.png',
            'neko.jpg',
            'piyopiyo.gif',
        ], self::getFileList($files));

        // extension file filter
        $files = FileSystem::listFiles($path, new ExtensionFileFilter('txt'));
        $this->assertEquals([
            'a.txt',
            'b.txt',
        ], self::getFileList($files));

        $files = FileSystem::listFiles($path, new ExtensionFileFilter('sql'));
        $this->assertEquals([
            'c.sql',
        ], self::getFileList($files));

        // image file filter
        $files = FileSystem::listFiles($path, new ImageFileFilter([IMAGETYPE_GIF]));
        $this->assertEquals([
            'piyopiyo.gif',
        ], self::getFileList($files));

        $files = FileSystem::listFiles($path, new ImageFileFilter([IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG]));
        $this->assertEquals([
            'dangohiyoko.png',
            'neko.jpg',
            'piyopiyo.gif',
        ], self::getFileList($files));
    }

    /**
     * @throws
     */
    public function testHash()
    {
        $path = __DIR__ . '/files/a.txt';
        $this->assertEquals(true, FileSystem::exists($path));
        $this->assertEquals('ae1ee9b3697975a181ac41fb3d2b1703e33df179', FileSystem::hash($path));
        $this->assertEquals('12d29e0edc6c68f84667e1bb51bd8225', FileSystem::hash($path, 'md5'));
        $this->assertEquals(sprintf("%x",crc32(FileSystem::get($path))), FileSystem::hash($path, 'crc32b'));

        $path = __DIR__ . '/files/neko.jpg';
        $this->assertEquals(true, FileSystem::exists($path));
        $this->assertEquals('49c3979c90053537c461df7d1b69b4c5c2419942', FileSystem::hash($path));
        $this->assertEquals('81e748c64578317d0b80dcc69d8f2f1a', FileSystem::hash($path, 'md5'));
        $this->assertEquals(sprintf("%x",crc32(FileSystem::get($path))), FileSystem::hash($path, 'crc32b'));
    }

    public function testCanRead()
    {
        $path = __DIR__ . '/files/a.txt';
        $this->assertEquals(true, FileSystem::canRead($path));
    }

    public function testCanWrite()
    {
        $path = __DIR__ . '/files/a.txt';
        $this->assertEquals(true, FileSystem::canWrite($path));
    }

    public function testGetFileSize()
    {
        $path = __DIR__ . '/files/a.txt';
        $this->assertEquals(208, FileSystem::getFileSize($path));

        $path = __DIR__ . '/files/piyopiyo.gif';
        $this->assertEquals(31568, FileSystem::getFileSize($path));
    }

    public function testGetFilePerms()
    {
        vfsStream::setup("myrootdir");
        vfsStream::copyFromFileSystem(__DIR__ . '/files');

        $path = vfsStream::url('myrootdir/a.txt');
        chmod(FileSystem::getPath($path), 0666);

        $perms = sprintf("%o",FileSystem::getFilePerms($path));

        $this->assertEquals(0666, octdec(substr($perms, -3)));

        chmod(FileSystem::getPath($path), 0644);

        $perms = sprintf("%o",FileSystem::getFilePerms($path));

        $this->assertEquals(0644, octdec(substr($perms, -3)));
    }

    public function testGetFileType()
    {
        $path = __DIR__ . '/files/a.txt';
        $this->assertEquals('file', FileSystem::getFileType($path));

        $path = __DIR__ . '/files/x';
        $this->assertEquals('dir', FileSystem::getFileType($path));
    }

    public function testDelete()
    {
        vfsStream::setup("myrootdir");
        vfsStream::copyFromFileSystem(__DIR__ . '/files');

        // remove single file
        $path = vfsStream::url('myrootdir/a.txt');
        $this->assertEquals(true, FileSystem::exists($path));
        FileSystem::delete($path);
        $this->assertEquals(false, FileSystem::exists($path));
    }

    public function testDeleteRecursive()
    {
        vfsStream::setup("myrootdir");
        vfsStream::copyFromFileSystem(__DIR__ . '/files');

        // remove directoy which include directory/files
        $target_dir = vfsStream::url('myrootdir/x');
        $parent_dir = vfsStream::url('myrootdir');
        $text_file = vfsStream::url('myrootdir/a.txt');

        $this->assertEquals(true, FileSystem::exists($target_dir));
        $this->assertEquals(true, FileSystem::exists($parent_dir));
        $this->assertEquals(true, FileSystem::exists($text_file));
        FileSystem::delete($target_dir, true);
        $this->assertEquals(false, FileSystem::exists($target_dir));
        $this->assertEquals(true, FileSystem::exists($parent_dir));
        $this->assertEquals(true, FileSystem::exists($text_file));

        // remove directoy which include directory/files
        $target_dir = vfsStream::url('myrootdir/y/q');
        $parent_dir = vfsStream::url('myrootdir/y');

        $this->assertEquals(true, FileSystem::exists($target_dir));
        $this->assertEquals(true, FileSystem::exists($parent_dir));
        $this->assertEquals(true, FileSystem::exists($text_file));
        FileSystem::delete($target_dir, true);
        $this->assertEquals(false, FileSystem::exists($target_dir));
        $this->assertEquals(true, FileSystem::exists($parent_dir));
        $this->assertEquals(true, FileSystem::exists($text_file));
    }

    public function testPut()
    {
        try{
            // put text
            vfsStream::setup("myrootdir");
            $target_file = vfsStream::url('myrootdir/dynamic.txt');

            $this->assertEquals(false, FileSystem::exists($target_file));

            FileSystem::put($target_file, 'Hello, World!');

            $this->assertEquals('Hello, World!', FileSystem::get($target_file));

            // put array
            vfsStream::setup("myrootdir");
            $target_file = vfsStream::url('myrootdir/dynamic.txt');

            $this->assertEquals(false, FileSystem::exists($target_file));

            FileSystem::put($target_file, ['Foo', 'Bar', 'Bazz']);

            $this->assertEquals('Foo' . PHP_EOL . 'Bar' . PHP_EOL . 'Bazz', FileSystem::get($target_file));

            // put stringable object
            vfsStream::setup("myrootdir");
            $target_file = vfsStream::url('myrootdir/dynamic.txt');

            $this->assertEquals(false, FileSystem::exists($target_file));

            FileSystem::put($target_file, new StringableObject([1,2,3,4,5]));

            $this->assertEquals('1,2,3,4,5', FileSystem::get($target_file));

            // put File object
            vfsStream::setup("myrootdir");
            $target_file = vfsStream::url('myrootdir/dynamic.txt');
            $source_file = vfsStream::url('myrootdir/source.txt');

            $this->assertEquals(false, FileSystem::exists($target_file));

            file_put_contents($source_file, 'Foo'.PHP_EOL.'Bar');
            FileSystem::put($target_file, new File($source_file));

            $this->assertEquals('Foo'.PHP_EOL.'Bar', FileSystem::get($target_file));

            // put Serializable object
            vfsStream::setup("myrootdir");
            $target_file = vfsStream::url('myrootdir/dynamic.txt');

            $this->assertEquals(false, FileSystem::exists($target_file));

            FileSystem::put($target_file, new SerializableObject(['Foo', 'Bar']));

            $this->assertEquals('C:40:"stk2k\filesystem\test\SerializableObject":34:{a:2:{i:0;s:3:"Foo";i:1;s:3:"Bar";}}', FileSystem::get($target_file));

            // put JsonSerializable object
            vfsStream::setup("myrootdir");
            $target_file = vfsStream::url('myrootdir/dynamic.txt');

            $this->assertEquals(false, FileSystem::exists($target_file));

            FileSystem::put($target_file, new JsonSerializableObject(['Foo', 'こんにちは']));

            $this->assertEquals('["Foo","こんにちは"]', FileSystem::get($target_file));

        }
        catch(FileInputException|FileOutputException $ex)
        {
            $this->fail($ex->getMessage());
        }
    }

    /**
     * @throws
     */
    public function testRenameFile()
    {
        vfsStream::setup("myrootdir");
        vfsStream::copyFromFileSystem(__DIR__ . '/files');

        // rename file with over writing
        $target_file = vfsStream::url('myrootdir/a.txt');

        $this->assertEquals(true, FileSystem::exists($target_file));

        $contents = FileSystem::get($target_file);

        try{
            $rename = vfsStream::url('myrootdir/new.txt');
            FileSystem::rename($target_file, new File($rename));
            $this->assertEquals($contents, FileSystem::get($rename));
            $this->assertEquals(false, FileSystem::exists($target_file));
        }
        catch(FileRenameException $e){
            $this->fail();
        }

        // rename file with over writing
        $target_file = vfsStream::url('myrootdir/b.txt');

        $this->assertEquals(true, FileSystem::exists($target_file));

        $contents = FileSystem::get($target_file);

        try{
            $rename = vfsStream::url('myrootdir/c.txt');
            FileSystem::rename($target_file, new File($rename));
            $this->assertEquals($contents, FileSystem::get($rename));
            $this->assertEquals(false, FileSystem::exists($target_file));
        }
        catch(FileRenameException $e){
            $this->fail();
        }
    }

    public function testRenameDir()
    {
        vfsStream::setup("myrootdir");
        vfsStream::copyFromFileSystem(__DIR__ . '/files');

        // rename directory with over writing
        $target_dir = vfsStream::url('myrootdir/x');

        $this->assertEquals(true, FileSystem::exists($target_dir));

        try{
            $rename = vfsStream::url('myrootdir/w');
            FileSystem::rename($target_dir, new File($rename));
            $this->assertEquals([
                'p',
                'x-1.txt',
            ], self::getFileList(FileSystem::listFiles($rename)));
        }
        catch(FileRenameException $e){
            $this->assertTrue(true);
        }

        $target_dir = __DIR__ . '/files/x';
        $this->assertEquals(true, FileSystem::exists($target_dir));

        try{
            $rename = __DIR__ . '/files/y';
            FileSystem::rename($target_dir, new File($rename));
            $this->fail();
        }
        catch(FileRenameException $e){
            $this->assertTrue(true);
        }
    }

    public function testMakeDirectory()
    {
        try {
            // checks /foo
            vfsStream::setup("myrootdir");
            $root_dir = vfsStream::url('myrootdir');
            $foo_dir = $root_dir . '/foo';

            $this->assertEquals(true, file_exists($root_dir));
            $this->assertEquals(false, file_exists($foo_dir));

            FileSystem::mkdir($foo_dir);

            $this->assertEquals(true, file_exists($foo_dir));

            // checks /foo/bar
            vfsStream::setup("myrootdir");
            $root_dir = vfsStream::url('myrootdir');
            $foo_dir = $root_dir . '/foo';
            $foo_bar_dir = $root_dir . '/foo/bar';

            $this->assertEquals(true, file_exists($root_dir));
            $this->assertEquals(false, file_exists($foo_dir));
            $this->assertEquals(false, file_exists($foo_bar_dir));

            FileSystem::mkdir($foo_bar_dir);

            $this->assertEquals(true, file_exists($foo_dir));
            $this->assertEquals(true, file_exists($foo_bar_dir));
        }
        catch(MakeDirectoryException $e)
        {
            $this->fail($e->getMessage());
        }
    }
}