<?php

namespace Pbc\AutoVersion;

/**
 * Tests for AutoVersion class
 */

require_once dirname(__DIR__) . '/vendor/autoload.php';

/**
 * Class AutoVersionTest
 * @package Pbc\AutoVersion
 */
class AutoVersionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string $tempFolder Temporary older folder for tests
     */
    protected static $tempFolder;
    protected static $tempFolderName;

    /**
     * Set Up
     */
    public static function setUpBeforeClass()
    {
        self::$tempFolderName = 'tmp';
        self::$tempFolder = __DIR__ . '/'.self::$tempFolderName;
        if(!file_exists(self::$tempFolder)) {
            mkdir(self::$tempFolder);
        }

        putenv("DOCUMENT_ROOT=".__DIR__);
    }

    /**
     * Delete directory recursively
     * http://stackoverflow.com/a/7288067/405758
     * @param $dir
     */
    protected static function rmdir_recursive($dir) {
        foreach(scandir($dir) as $file) {
            if ('.' === $file || '..' === $file) continue;
            if (is_dir("$dir/$file")) self::rmdir_recursive("$dir/$file");
            else unlink("$dir/$file");
        }
        rmdir($dir);
    }

    /**
     * Tear Down
     */
    public static function tearDownAfterClass()
    {
        self::rmdir_recursive(self::$tempFolder);
    }

    /**
     * Test setting document root from constructor
     */
    public function testSetDocumentRootFromConstructor()
    {
        $folder = '/path/to/public/html';
        $auto = new AutoVersion($folder);

        $r = new \ReflectionObject($auto);
        $p = $r->getMethod('getDocumentRoot');
        $p->setAccessible(true);

        $this->assertEquals($folder, $p->invoke($auto, null));

    }

    /**
     * Test trying to auto version a string that's a directory will return itself unchanged
     */
    public function testGetFileUnchagedIfDirectory()
    {
        $folder = __DIR__;
        $auto = new AutoVersion($folder);

        $fileName = '/tmp/';
        $file = $auto->file($fileName);

        $this->assertEquals($fileName, $file);

    }

    /**
     * Test trying to auto version a string that's a missing file will return itself unchanged
     */
    public function testGetFileUnchagedIfMissingFile()
    {
        $folder = __DIR__;
        $auto = new AutoVersion($folder);

        $fileName = '/this/file/does/not/exist.json';
        $file = $auto->file($fileName);

        $this->assertEquals($fileName, $file);

    }



    /**
     * Test trying to auto version a file by default setting
     */
    public function testGetFile()
    {
        $folder = __DIR__;
        $auto = new AutoVersion($folder);

        $fileName =  md5(microtime(true)).'-version.json';
        file_put_contents(self::$tempFolder.'/'.$fileName, time());
        $fileMTime = filemtime(self::$tempFolder.'/'.$fileName);

        $versionedFile = $auto->file('/'.self::$tempFolderName.'/'.$fileName);

        $this->assertNotEquals('/'.self::$tempFolderName.'/'.$fileName, $versionedFile);
        $this->assertContains((string)$fileMTime, (string)$versionedFile);
        $this->assertNotContains('?', (string)$versionedFile);

    }

    /**
     * Test trying to auto version a file by default setting by static option
     */
    public function testGetFileByStatic()
    {

        $fileName =  md5(microtime(true)).'-version.json';
        file_put_contents(self::$tempFolder.'/'.$fileName, time());
        $fileMTime = filemtime(self::$tempFolder.'/'.$fileName);

        $versionedFile = AutoVersionSingle::file('/'.self::$tempFolderName.'/'.$fileName);

        $this->assertNotEquals('/'.self::$tempFolderName.'/'.$fileName, $versionedFile);
        $this->assertContains((string)$fileMTime, (string)$versionedFile);
        $this->assertNotContains('?', (string)$versionedFile);

    }

    /**
     * Test trying to auto version a file by regular expression option
     */
    public function testGetFileByRegExp()
    {
        $folder = __DIR__;
        $auto = new AutoVersion($folder);

        foreach(['r','regexp'] as $option) {

            $fileName = md5(microtime(true)) . '-version-'.$option.'.json';
            file_put_contents(self::$tempFolder . '/' . $fileName, time());
            $fileMTime = filemtime(self::$tempFolder . '/' . $fileName);

            $versionedFile = $auto->file('/' . self::$tempFolderName . '/' . $fileName, $option);

            $this->assertNotEquals('/' . self::$tempFolderName . '/' . $fileName, $versionedFile);
            $this->assertContains((string)$fileMTime, (string)$versionedFile);
            $this->assertNotContains('?', (string)$versionedFile);
        }

    }

    /**
     * Test trying to auto version a file by regular expression option by static call
     */
    public function testGetFileByRegExpByStatic()
    {
        $folder = __DIR__;

        foreach(['r','regexp'] as $option) {

            $fileName = md5(microtime(true)) . '-version-'.$option.'.json';
            file_put_contents(self::$tempFolder . '/' . $fileName, time());
            $fileMTime = filemtime(self::$tempFolder . '/' . $fileName);

            $versionedFile = AutoVersionSingle::file('/' . self::$tempFolderName . '/' . $fileName, $option);

            $this->assertNotEquals('/' . self::$tempFolderName . '/' . $fileName, $versionedFile);
            $this->assertContains((string)$fileMTime, (string)$versionedFile);
            $this->assertNotContains('?', (string)$versionedFile);
        }

    }

    /**
     * Test trying to auto version a file by regular expression option
     */
    public function testGetFileByQuery()
    {
        $folder = __DIR__;
        $auto = new AutoVersion($folder);

        foreach(['q','query'] as $option) {

            $fileName = md5(microtime(true)) . '-version-'.$option.'.json';
            file_put_contents(self::$tempFolder . '/' . $fileName, time());
            $fileMTime = filemtime(self::$tempFolder . '/' . $fileName);

            $versionedFile = $auto->file('/' . self::$tempFolderName . '/' . $fileName, $option);

            $this->assertNotEquals('/' . self::$tempFolderName . '/' . $fileName, $versionedFile);
            $this->assertContains((string)$fileMTime, (string)$versionedFile);
            $this->assertContains('?'.(string)$fileMTime, (string)$versionedFile);
        }

    }

    /**
     * Test trying to auto version a file by regular expression option by static call
     */
    public function testGetFileByQueryByStatic()
    {

        foreach(['q','query'] as $option) {

            $fileName = md5(microtime(true)) . '-version-'.$option.'.json';
            file_put_contents(self::$tempFolder . '/' . $fileName, time());
            $fileMTime = filemtime(self::$tempFolder . '/' . $fileName);

            $versionedFile = AutoVersionSingle::file('/' . self::$tempFolderName . '/' . $fileName, $option);

            $this->assertNotEquals('/' . self::$tempFolderName . '/' . $fileName, $versionedFile);
            $this->assertContains((string)$fileMTime, (string)$versionedFile);
            $this->assertContains('?'.(string)$fileMTime, (string)$versionedFile);
        }

    }
}
