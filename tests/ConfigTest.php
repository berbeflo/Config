<?php

declare(strict_types=1);
namespace berbeflo\Config\Test;

use berbeflo\Config\Config;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class ConfigTest extends TestCase
{
    private $config;

    public function __construct()
    {
        parent::__construct();
        $file = BASE_DIR . '/tests/files/config1.php';
        $this->config = new Config($file);
    }

    public function testInvalidFile() : void
    {
        $file = BASE_DIR . '/tests/files/config0.php';
        $this->expectException(InvalidArgumentException::class);
        $config = new Config($file);
    }

    public function testDefault() : void
    {
        $default1 = null;
        $default2 = '';
        $path = 'does.not.exist';
        $this->assertSame($default1, $this->config->get($path));
        $this->assertSame($default1, $this->config->get($path, $default1));
        $this->assertSame($default2, $this->config->get($path, $default2));
    }

    public function testReturnsNull() : void
    {
        $path = 'a.for.tests';
        $default = '';
        $this->assertSame(null, $this->config->get($path, $default));
    }

    public function testFindsResult() : void
    {
        $path = 'just.test';
        $this->assertSame('file', $this->config->get($path));
    }
}
