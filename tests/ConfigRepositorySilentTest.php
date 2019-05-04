<?php

declare(strict_types=1);
namespace berbeflo\Config\Test;

use berbeflo\Config\ConfigRepository;
use PHPUnit\Framework\TestCase;

class ConfigRepositorySilentTest extends TestCase
{
    public static function setUpBeforeClass() : void
    {
        $directory = BASE_DIR . '/tests/files/';
        $file1 = 'config1';
        $file2 = 'config3';

        ConfigRepository::suppressExceptions();
        ConfigRepository::addSearchPath($directory);
        ConfigRepository::add($file1);
        ConfigRepository::add($file2);
    }

    public function setUp() : void
    {
        ConfigRepository::suppressExceptions();
    }

    public function testAddInvalidFile()
    {
        $file = 'config2';
        ConfigRepository::add($file);
    }

    public function testAddInvalidDirectory()
    {
        ConfigRepository::addSearchPath(BASE_DIR . '/does/not/exist');
    }

    public function testGetValue()
    {
        $this->assertSame('case', ConfigRepository::get('config3.test'));
    }

    public function testGetWhole()
    {
        $this->assertEquals(['test' => 'case'], ConfigRepository::get('config3'));
    }

    public function testGetDefault()
    {
        $this->assertEquals(null, ConfigRepository::get('config2'));
    }
}
