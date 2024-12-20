<?php

namespace App\Service\Cache;

use App\Contracts\CacheStrategyInterface;
use Illuminate\Support\Facades\Cache;

class RedisCache implements CacheStrategyInterface
{
    /**
     * @inheritDoc
     */
    public function put(string $key, $value, $ttl): void
    {
        Cache::store('redis')->put($key, $value, $ttl);
    }

    /**
     * @inheritDoc
     */
    public function get(string $key):mixed
    {
        return Cache::store('redis')->get($key);
    }
    /**
     * @inheritDoc
     */
    public function forget(string $key): void
    {
        Cache::store('redis')->forget($key);
    }

    /**
     * @inheritDoc
     */
    public function clear(): void
    {
        Cache::store('redis')->clear();
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return  Cache::store('redis')->has($key);
    }

    /**
     * @inheritDoc
     */
    public function remember(string $key, $ttl, callable $callback):mixed
    {
        return Cache::store('redis')->remember($key, $ttl, $callback);
    }
}
