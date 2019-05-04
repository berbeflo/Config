<?php

declare(strict_types=1);
namespace berbeflo\Config;

class Config
{
    private $configArray;
    private $cache = [];

    /**
     * __construct
     *
     * @param  string $filePath the path to the file to load
     *
     * @throws \InvalidArgumentException when file is invalid
     */
    public function __construct(string $filePath)
    {
        if (!$this->checkFileValid($filePath)) {
            throw new \InvalidArgumentException('Invalid file: ' . $filePath);
        }

        $arr = include($filePath);

        if (!\is_array($arr)) {
            throw new \InvalidArgumentException('file ' . $filePath . ' does not return an array');
        }

        $this->configArray = $arr;
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
    public function get(string $path, $default = null)
    {
        if (\array_key_exists($path, $this->cache)) {
            return $this->returnFromCache($path, $default);
        }

        $hierarchy = \explode('.', $path);
        $current = $this->configArray;

        while (($next = \array_shift($hierarchy)) !== null) {
            if (!\is_array($current)) {
                $this->addDefaultToCache($path);

                return $default;
            }

            if (!\array_key_exists($next, $current)) {
                $this->addDefaultToCache($path);

                return $default;
            }
            $current = $current[$next];
        }

        $this->cache[$path] = $current;

        return $current;
    }

    /**
     * getAll
     *
     * returns the fully loaded array
     *
     * @return array
     */
    public function getAll() : array
    {
        return $this->configArray;
    }

    private function addDefaultToCache(string $path) : void
    {
        $this->cache[$path] = new NotExistent();
    }

    private function returnFromCache(string $path, $defaultValue)
    {
        $cachedValue = $this->cache[$path];

        if (\is_object($cachedValue) && $cachedValue instanceof NotExistent) {
            return $defaultValue;
        }

        return $cachedValue;
    }

    private function checkFileValid(string $filePath) : bool
    {
        if (!\file_exists($filePath)) {
            return false;
        }

        if (!\is_file($filePath)) {
            return false;
        }

        if (!\is_readable($filePath)) {
            return false;
        }

        return true;
    }
}
