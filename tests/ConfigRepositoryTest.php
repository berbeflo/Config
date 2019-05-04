<?php

declare(strict_types=1);
namespace berbeflo\Config\Test;

use berbeflo\Config\ConfigRepository;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ConfigRepositoryTest extends TestCase
{
    public static function setUpBeforeClass() : void
    {
        $directory = BASE_DIR . '/tests/files/';

        ConfigRepository::throwExceptions();
        ConfigRepository::addSearchPath($directory);
    }

    public function setUp() : void
    {
        ConfigRepository::throwExceptions();
    }

    public function testAddInvalidFile()
    {
        $this->expectException(InvalidArgumentException::class);
        $file = BASE_DIR . 'config2';
        ConfigRepository::add($file);

        \var_dump('throw');
    }

    public function testAddInvalidDirectory()
    {
        $this->expectException(InvalidArgumentException::class);
        ConfigRepository::addSearchPath(BASE_DIR . '/does/not/exist');
    }
}
