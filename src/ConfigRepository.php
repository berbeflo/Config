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

    /**
     * addSearchPath
     *
     * @param  string $directory a directory where the Repository should search for config files
     *
     * @return void
     *
     * @throws \InvalidArgumentException if the directory does not exist
     */
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

    /**
     * throwExceptions
     *
     * if called, the repository throws exceptions when it should load
     * invalid files. (which is the default behaviour)
     *
     * @return void
     */
    public static function throwExceptions() : void
    {
        self::$throw = true;
    }

    /**
     * suppressExceptions
     *
     * if called, the repository suppresses exceptions when it should
     * load invalid files
     *
     * @return void
     */
    public static function suppressExceptions() : void
    {
        self::$throw = false;
    }

    /**
     * add
     *
     * adds a Config object to the repo
     *
     * @param  string $fileName the name of the file that should be loaded
     *
     * @return void
     *
     * @throws \InvalidArgumentException if the file is invalid
     */
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

    /**
     * get
     *
     * gets the requested config data. If it doesn't exist,
     * $default will be returned. Defaults to null.
     *
     * @param  string $path
     * @param  mixed $default
     *
     * @return mixed
     */
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
