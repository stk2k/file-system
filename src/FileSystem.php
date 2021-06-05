<?php
declare(strict_types=1);

namespace Stk2k\FileSystem;

use Serializable;
use JsonSerializable;

use Stk2k\FileSystem\Exception\FileInputException;
use Stk2k\FileSystem\Exception\FileOutputException;
use Stk2k\FileSystem\Exception\FileRenameException;
use Stk2k\FileSystem\Exception\MakeDirectoryException;
use Stk2k\FileSystem\Exception\MakeFileException;

class FileSystem
{
    /**
     * Returns file hash
     *
     * @param string $path
     * @param string $algo
     *
     * @return string
     */
    public static function hash(string $path, string $algo = 'sha1') : string
    {
        return (new File($path))->hash($algo);
    }

    /**
     *  Returns if the file or directory can be read.
     *
     * @param string $path
     *
     * @return bool
     */
    public static function canRead(string $path) : bool
    {
        return (new File($path))->canRead();
    }

    /**
     *  Returns if the file or directory can be written.
     *
     * @param string $path
     *
     * @return bool
     */
    public static function canWrite(string $path) : bool
    {
        return (new File($path))->canWrite();
    }

    /**
     *  Returns file size of the file or directory in bytes.
     *
     * @param string $path
     *
     * @return int
     */
    public static function getFileSize(string $path) : int
    {
        return (new File($path))->getFileSize();
    }

    /**
     *  Returns file permissions
     *
     * @param string $path
     *
     * @return int
     */
    public static function getFilePerms(string $path) : int
    {
        return (new File($path))->getFilePerms();
    }

    /**
     *  Returns file type
     *
     * @param string $path
     *
     * @return string
     */
    public static function getFileType(string $path) : string
    {
        return (new File($path))->getFileType();
    }

    /**
     *  Virtual path
     *
     * @param string $path
     *
     * @return string
     */
    public static function getPath(string $path) : string
    {
        return (new File($path))->getPath();
    }

    /**
     *  Return if the path means file.
     *
     * @param string $path
     *
     * @return bool
     */
    public static function isFile(string $path) : bool
    {
        return (new File($path))->isReadable();
    }

    /**
     *  Return if the path means directory.
     *
     * @param string $path
     *
     * @return bool
     */
    public static function isDir(string $path) : bool
    {
        return (new File($path))->isReadable();
    }

    /**
     *  Return if the path means directory.
     *
     * @param string $path
     *
     * @return bool
     */
    public static function isDirectory(string $path) : bool
    {
        return (new File($path))->isReadable();
    }

    /**
     *  Return if the file or directory can be read.
     *
     * @param string $path
     *
     * @return bool
     */
    public static function isReadable(string $path) : bool
    {
        return (new File($path))->isReadable();
    }

    /**
     *  Return if the file or directory can be written.
     *
     * @param string $path
     *
     * @return bool
     */
    public static function isWriteable(string $path) : bool
    {
        return (new File($path))->isWriteable();
    }

    /**
     *  Extension of the file
     *
     * @param string $path
     *
     * @return string
     */
    public static function getExtension(string $path) : string
    {
        return (new File($path))->getExtension();
    }

    /**
     *  returns last modified time(UNIX time)
     *
     * @param string $path
     *
     * @return int
     */
    public static function getLastModifiedTime(string $path) : int
    {
        return (new File($path))->getLastModifiedTime();
    }

    /**
     *  returns last access time(UNIX time)
     *
     * @param string $path
     *
     * @return int
     */
    public static function getLastAccessTime(string $path) : int
    {
        return (new File($path))->getLastAccessTime();
    }

    /**
     *  returns file owner
     *
     * @param string $path
     *
     * @return int
     */
    public static function getFileOwner(string $path) : int
    {
        return (new File($path))->getFileOwner();
    }

    /**
     *  Return if the file or directory exists.
     *
     * @param string $path
     *
     * @return bool
     */
    public static function exists(string $path) : bool
    {
        return (new File($path))->exists();
    }

    /**
     *  Absolute path of the file or directory
     *
     * @param string $path
     *
     * @return string
     */
    public static function getAbsolutePath(string $path) : string
    {
        return (new File($path))->getAbsolutePath();
    }

    /**
     *  Name of the file or directory
     *
     * @param string $path
     * @param string|null $suffix       file suffix which is ignored.
     *
     * @return string
     */
    public static function getName(string $path, string $suffix = null) : string
    {
        return (new File($path))->getName($suffix);
    }

    /**
     *  Name of parent directory
     *
     * @param string $path
     *
     * @return string
     */
    public static function getDirName(string $path) : string
    {
        return (new File($path))->getDirName();
    }

    /**
     *  Child of the file or directory
     *
     * @param string $path
     * @param string $file_or_dir_name
     *
     * @return File
     */
    public static function getChild(string $path, string $file_or_dir_name) : File
    {
        return (new File($path))->getChild($file_or_dir_name);
    }

    /**
     *  Parent of the file or directory
     *
     * @param string $path
     *
     * @return File
     */
    public static function getParent(string $path) : File
    {
        return (new File($path))->getParent();
    }

    /**
     *  Contents of the file or directory
     *
     * @param string $path
     *
     * @return string
     *
     * @throws FileInputException
     */
    public static function get(string $path) : string
    {
        return (new File($path))->get();
    }

    /**
     *  get contents of the file as array
     *
     * @param string $path
     * @param int $flags
     *
     * @return array|false
     */
    public static function getAsArray(string $path, int $flags = FILE_IGNORE_NEW_LINES) : array
    {
        return (new File($path))->getAsArray($flags);
    }

    /**
     *  Save string data as a file
     *
     * @param string $path
     * @param string|array|Serializable|JsonSerializable $contents
     * @param bool $ex_lock
     *
     * @return File
     *
     * @throws FileOutputException|FileInputException
     */
    public static function put(string $path, $contents, bool $ex_lock = false) : File
    {
        return (new File($path))->put($contents, $ex_lock);
    }

    /**
     *  Rename the file or directory
     *
     * @param string $path
     * @param File $new_file
     *
     * @return File
     *
     * @throws FileRenameException
     */
    public static function rename(string $path, File $new_file) : File
    {
        return (new File($path))->rename($new_file);
    }

    /**
     *  Create file
     *
     * @param string $path
     * @param string $contents File contents
     * @param int $mode File mode
     *
     * @return File
     *
     * @throws MakeFileException|MakeDirectoryException
     */
    public static function mkfile(string $path, string $contents, int $mode = File::MAKEDIRECTORY_DEFAULT_MODE) : File
    {
        return (new File($path))->mkfile($contents, $mode);
    }

    /**
     *  Create empty directory
     *
     * @param string $path
     * @param int $mode
     *
     * @return File
     *
     * @throws MakeDirectoryException
     */
    public static function mkdir(string $path, int $mode = File::MAKEDIRECTORY_DEFAULT_MODE) : File
    {
        return (new File($path))->mkdir($mode);
    }

    /**
     *  Delete the file or directory
     *
     * @param string $path
     * @param bool $drilldown
     *
     * @return File
     */
    public static function delete(string $path, bool $drilldown = false) : File
    {
        return (new File($path))->delete($drilldown);
    }

    /**
     *  Delete the file or directory
     *
     * @param string $path
     *
     * @return File
     */
    public static function rmdirRecursive(string $path) : File
    {
        return (new File($path))->rmdirRecursive();
    }

    /**
     *  Listing up files in directory which this object means
     *
     * @param string $path
     * @param FileFilterInterface|callable $filter       Fileter object which implements selection logic. If this parameter is omitted, all files will be selected.
     *
     * @return File[]
     */
    public static function listFiles(string $path, $filter = null) : array
    {
        return (new File($path))->listFiles($filter);
    }

    /**
     *  Update last modified date of the file
     *
     * @param string $path
     * @param int|null $time      time value to set
     *
     * @return File
     */
    public static function touch(string $path, int $time = null) : File
    {
        return (new File($path))->touch($time);
    }
}