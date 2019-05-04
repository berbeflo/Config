<?php

declare(strict_types=1);
namespace berbeflo\Config\Test;

use berbeflo\Config\ConfigRepository;
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
        $this->expectException(InvalidArgumentException::class);
        $file = BASE_DIR . '/tests/files/config2.php';
        ConfigRepository::add($file);
    }
}
