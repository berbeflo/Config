<?php

declare(strict_types=1);
namespace berbeflo\Config;

class ConfigRepository
{
    private static $throw = true;
    private static $configObjects = [];
    private static $searchPaths = [];

    private function __construct()
    {
    }

    public static function addSearchPath(string $directory) : void
    {
        if (\file_exists($directory) && \is_dir($directory)) {
            self::$searchPaths[] = $directory;

            return;
        }

        if (self::$throw) {
            throw new \InvalidArgumentException('the directory ' . $directory . ' does not exist');
        }
    }

    public static function throwExceptions() : void
    {
        self::$throw = true;
    }

    public static function suppressExceptions() : void
    {
        self::$throw = false;
    }

    public static function add(string $fileName) : void
    {
        foreach (self::$searchPaths as $path) {
            $filePath = $path . '/' . $fileName . '.php';

            if (\file_exists($filePath) && \is_file($filePath)) {
                try {
                    $object = new Config($filePath);
                    self::$configObjects[$fileName] = $object;
                } catch (\Exception $e) {
                    if (self::$throw) {
                        throw $e;
                    }
                }

                return;
            }
        }

        if (self::$throw) {
            throw new \InvalidArgumentException('file ' . $fileName . ' does not exist');
        }
    }

    public static function get(string $path, $default = null)
    {
        $split = \explode('.', $path, 2);


        if (!\array_key_exists($split[0], self::$configObjects)) {
            return $default;
        }

        if (\count($split) === 1) {
            return self::$configObjects[$split[0]]->getAll();
        }

        return self::$configObjects[$split[0]]->get($split[1], $default);
    }
}
