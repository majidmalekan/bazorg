<?php

namespace App\Contracts;

interface CacheStrategyInterface
{
    /**
     * @param string $key
     * @param $value
     * @param $ttl
     * @return void
     */
    public function put(string $key, $value, $ttl): void;

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed;

    /**
     * @param string $key
     * @return void
     */
    public function forget(string $key): void;

    /**
     * @return void
     */
    public function clear(): void;

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * @param string $key
     * @param $ttl
     * @param callable $callback
     * @return mixed
     */
    public function remember(string $key, $ttl, callable $callback): mixed;
}
