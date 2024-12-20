<?php

namespace App\Service\Cache;

use App\Contracts\CacheStrategyInterface;
use Illuminate\Support\Facades\Cache;

class DatabaseCache implements CacheStrategyInterface
{
    /**
     * @inheritDoc
     */
    public function put(string $key, $value, $ttl): void
    {
        Cache::store('database')->put($key, $value, $ttl);
    }

    /**
     * @inheritDoc
     */
    public function get(string $key): mixed
    {
        return Cache::store('database')->get($key);
    }

    /**
     * @inheritDoc
     */
    public function forget(string $key): void
    {
        Cache::store('database')->forget($key);
    }

    /**
     * @inheritDoc
     */
    public function clear(): void
    {
        Cache::store('database')->clear();
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return Cache::store('database')->has($key);
    }

    /**
     * @inheritDoc
     */
    public function remember(string $key, $ttl, callable $callback): mixed
    {
        return Cache::store('database')->remember($key, $ttl, $callback);
    }
}
