<?php

namespace Omotolaawokunle\LaravelFiles\Tests\Feature;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use Omotolaawokunle\LaravelFiles\Tests\TestCase;
use Omotolaawokunle\LaravelFiles\Contracts\Files;
use Omotolaawokunle\LaravelFiles\Facades\Directory;

class DirectoryTest extends TestCase
{
    private Files $dir;
    private vfsStreamDirectory $root;
    private string $rootUrl;

    public function setUp(): void
    {
        parent::setUp();
        $this->root = vfsStream::setup('folders');
        $this->rootUrl = vfsStream::url('folders');
        $this->dir = Directory::make($this->rootUrl, "test-folder");
    }

    public function testInitialiseDirectory()
    {
        $dir = Directory::make($this->rootUrl, "test-folder");
        $this->assertEquals(vfsStream::url("folders/test-folder"), $dir->getPath());
        $this->assertTrue($this->root->hasChild("test-folder"));
    }

    public function testCreateDirectory()
    {
        $dir = Directory::create($this->rootUrl, "test-folder-2");
        $this->assertEquals(vfsStream::url("folders/test-folder-2"), $dir->getPath());
        $this->assertTrue($this->root->hasChild("test-folder-2"));
    }

    public function testGetDirectoryPath()
    {
        $this->assertEquals(vfsStream::url("folders/test-folder"), $this->dir->getPath());
    }

    public function testDirectoryExists()
    {
        $this->assertTrue($this->root->hasChild("test-folder"));
    }

    public function testDirectorySize()
    {
        $this->assertEquals(0, $this->dir->size());
        $this->assertEquals('0 B', $this->dir->size('B', true));
        $this->assertEquals('0 KB', $this->dir->size('KB', true));
    }

    public function testDirectoryDelete()
    {
        $dir = Directory::create($this->rootUrl, "test-folder-3");
        $this->assertTrue($this->root->hasChild("test-folder-3"));
        $dir->delete();
        $this->assertFalse($this->root->hasChild("test-folder-3"));
    }

    public function testDirectoryClone()
    {
        $newDir = $this->dir->clone($this->rootUrl, "test-folder-clone");
        $this->assertTrue($this->root->hasChild("test-folder-clone"));
        $this->assertEquals(vfsStream::url("folders/test-folder-clone"), $newDir->getPath());
    }

    public function testDirectoryMove()
    {
        $this->dir->move(vfsStream::url("folders/test-folder-1"));
        $this->assertTrue($this->root->hasChild('test-folder-1'));
        $this->assertEquals(vfsStream::url("folders/test-folder-1"), $this->dir->getPath());
        $this->assertFalse($this->root->hasChild("test-folder"));
    }

    public function testDirectoryRename()
    {
        $this->dir->rename("test-folder");
        $this->assertTrue($this->root->hasChild('test-folder'));
        $this->assertEquals(vfsStream::url("folders/test-folder"), $this->dir->getPath());
        $this->assertFalse($this->root->hasChild('test-folder-1'));
    }

    public function testDirectoryContents()
    {
        $this->assertTrue($this->dir->contents() instanceof \Illuminate\Support\Collection);
        $this->assertTrue($this->dir->contents()->count() == 0);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }
}
