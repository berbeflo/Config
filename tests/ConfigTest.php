<?php

declare(strict_types=1);
namespace berbeflo\Config\Test;

use berbeflo\Config\Config;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    private static $config;

    public static function setUpBeforeClass() : void
    {
        $file = BASE_DIR . '/tests/files/config1.php';
        self::$config = new Config($file);
    }

    public function testInexistentFile() : void
    {
        $file = BASE_DIR . '/tests/files/config0.php';
        $this->expectException(InvalidArgumentException::class);
        $config = new Config($file);
    }

    public function testInvalidFile() : void
    {
        $file = BASE_DIR . '/tests/files/config2.php';
        $this->expectException(InvalidArgumentException::class);
        $config = new Config($file);
    }

    public function testGetWholeFile() : void
    {
        $file = BASE_DIR . '/tests/files/config3.php';
        $config = new Config($file);
        $this->assertEquals(['test' => 'case'], $config->getAll());
    }

    public function testDefault() : void
    {
        $default1 = null;
        $default2 = '';
        $path = 'does.not.exist';
        $this->assertSame($default1, self::$config->get($path));
        $this->assertSame($default1, self::$config->get($path, $default1));
        $this->assertSame($default2, self::$config->get($path, $default2));
    }

    public function testReturnsNull() : void
    {
        $path = 'a.for.tests';
        $default = '';
        $this->assertSame(null, self::$config->get($path, $default));
    }

    public function testFindsResult() : void
    {
        $path = 'just.test';
        $this->assertSame('file', self::$config->get($path));
    }
}
