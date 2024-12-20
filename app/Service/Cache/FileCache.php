<?php

namespace App\Service\Cache;

use App\Contracts\CacheStrategyInterface;
use Illuminate\Support\Facades\Cache;

class FileCache implements CacheStrategyInterface
{
    /**
     * @inheritDoc
     */
    public function put(string $key, $value, $ttl): void
    {
        Cache::store('file')->put($key, $value, $ttl);
    }

    /**
     * @inheritDoc
     */
    public function get(string $key): mixed
    {
        return Cache::store('file')->get($key);
    }
    /**
     * @inheritDoc
     */
    public function forget(string $key): void
    {
        Cache::store('file')->forget($key);
    }
    /**
     * @inheritDoc
     */
    public function clear(): void
    {
        Cache::store('file')->clear();
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return Cache::store('file')->has($key);
    }

    /**
     * @inheritDoc
     */
    public function remember(string $key, $ttl, callable $callback):mixed
    {
        return Cache::store('file')->remember($key, $ttl, $callback);
    }
}
