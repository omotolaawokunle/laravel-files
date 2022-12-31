<?php

namespace Omotolaawokunle\LaravelFiles\Tests\Feature;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use Omotolaawokunle\LaravelFiles\Tests\TestCase;
use Omotolaawokunle\LaravelFiles\Contracts\Files;
use Omotolaawokunle\LaravelFiles\Facades\File;

class FileTest extends TestCase
{
    private \Omotolaawokunle\LaravelFiles\File $file;
    private vfsStreamDirectory $root;
    private string $rootUrl;

    public function setUp(): void
    {
        parent::setUp();
        $this->root = vfsStream::setup('files');
        $this->rootUrl = vfsStream::url('files');
        $this->file = File::make($this->rootUrl, "test-file.txt", "Hello World!");
    }

    public function testInitialiseFile()
    {
        $file = File::make($this->rootUrl, "test-file.txt", "Hello World!");
        $this->assertEquals(vfsStream::url("files/test-file.txt"), $file->getPath());
        $this->assertTrue($this->root->hasChild("test-file.txt"));
        $this->assertEquals("Hello World!", $file->contents());
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage File not found!
     */
    public function testStrictInitialization()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("File not found!");
        File::make($this->rootUrl, "test-strict.txt", null, true);
    }

    public function testCreateFile()
    {
        $file = File::create($this->rootUrl, "test-file-2.txt", "Hello World!!");
        $this->assertEquals(vfsStream::url("files/test-file-2.txt"), $file->getPath());
        $this->assertTrue($this->root->hasChild("test-file-2.txt"));
        $this->assertEquals("Hello World!!", $file->contents());
    }

    public function testGetFilePath()
    {
        $this->assertEquals(vfsStream::url("files/test-file.txt"), $this->file->getPath());
    }

    public function testFileExists()
    {
        $this->assertTrue($this->root->hasChild("test-file.txt"));
    }

    public function testFileSize()
    {
        $this->assertEquals(12, $this->file->size());
        $this->assertEquals('12 B', $this->file->size('B', true));
        $this->assertEquals('0.0117 KB', $this->file->size('KB', true));
    }

    public function testFileDelete()
    {
        $file = File::create($this->rootUrl, "test-file-3.txt", "Hello World!!!");
        $this->assertTrue($this->root->hasChild("test-file-3.txt"));
        $file->delete();
        $this->assertFalse($this->root->hasChild("test-file-3.txt"));
    }

    public function testFileClone()
    {
        $newDir = $this->file->clone($this->rootUrl, "test-file-clone");
        $this->assertTrue($this->root->hasChild("test-file-clone.txt"));
        $this->assertEquals(vfsStream::url("files/test-file-clone.txt"), $newDir->getPath());
        $replaceFile = $this->file->clone($this->rootUrl, "test-file-2", true);
        $this->assertTrue($this->root->hasChild("test-file-2.txt"));
        $this->assertEquals(vfsStream::url("files/test-file-2.txt"), $replaceFile->getPath());
    }

    public function testGetFileExtension()
    {
        $this->assertEquals('txt', $this->file->extension());
    }

    public function testGetFileNameWithoutExtension()
    {
        $this->assertEquals('test-file', $this->file->nameWithoutExtension());
    }

    public function testFileMove()
    {
        $this->file->move(vfsStream::url("files/test-file-1.txt"));
        $this->assertTrue($this->root->hasChild('test-file-1.txt'));
        $this->assertEquals(vfsStream::url("files/test-file-1.txt"), $this->file->getPath());
        $this->assertFalse($this->root->hasChild("test-file.txt"));
    }

    public function testFileRename()
    {
        $this->file->rename("test-file.txt");
        $this->assertTrue($this->root->hasChild('test-file.txt'));
        $this->assertEquals(vfsStream::url("files/test-file.txt"), $this->file->getPath());
        $this->assertFalse($this->root->hasChild('test-file-1.txt'));
    }

    public function testFileContents()
    {
        $this->assertEquals('Hello World!', $this->file->contents());
    }

    public function testWriteToFile()
    {
        $this->file->writeToFile("\nHi, it's me again. Hello World!");
        $this->assertEquals("Hello World!\nHi, it's me again. Hello World!", $this->file->contents());
        $this->file->writeToFile("Overwritten! Hello World!!", true);
        $this->assertEquals("Overwritten! Hello World!!", $this->file->contents());
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }
}
