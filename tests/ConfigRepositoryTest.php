<?php

declare(strict_types=1);
namespace berbeflo\Config\Test;

use berbeflo\Config\ConfigRepository;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ConfigRepositoryTest extends TestCase
{
    public function __construct()
    {
        parent::__construct();
        $directory = BASE_DIR . '/tests/files/';

        ConfigRepository::throwExceptions();
        ConfigRepository::addSearchPath($directory);
    }

    public function testAddInvalidFile()
    {
        ConfigRepository::throwExceptions();
        $this->expectException(InvalidArgumentException::class);
        $file = BASE_DIR . 'config2';
        ConfigRepository::add($file);

        \var_dump('throw');
    }

    public function testAddInvalidDirectory()
    {
        ConfigRepository::throwExceptions();
        $this->expectException(InvalidArgumentException::class);
        ConfigRepository::addSearchPath(BASE_DIR . '/does/not/exist');
    }
}
