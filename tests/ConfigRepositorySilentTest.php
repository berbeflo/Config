<?php

declare(strict_types=1);
namespace berbeflo\Config\Test;

use berbeflo\Config\ConfigRepository;
use PHPUnit\Framework\TestCase;

class ConfigRepositorySilentTest extends TestCase
{
    public function __construct()
    {
        parent::__construct();
        $directory = BASE_DIR . '/tests/files/';
        $file1 = BASE_DIR . 'config1.php';
        $file2 = BASE_DIR . 'config3.php';

        ConfigRepository::suppressExceptions();
        ConfigRepository::addSearchPath($directory);
        ConfigRepository::add($file1);
        ConfigRepository::add($file2);
    }

    public function testAddInvalidFile()
    {
        $file = BASE_DIR . '/tests/files/config2.php';
        ConfigRepository::add($file);
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
