<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

trait CacheRepositoryTrait
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function clearCache($key, $id = ''): void
    {
        if ($id != '') {
            Cache::forget($this->getTableName() . '_' . $key . '_' . (auth('sanctum')->check() ? request()->user('sanctum')->id . $id : $id));
        }
        Cache::forget($this->getTableName() . '_index_');
        for ($i = 1; $i <= $this->getLastPage(); $i++) {
            $newKey = $this->getTableName() . '_' . ($key == "index" ? $key : "index") . '_' . (auth('sanctum')->check() ? request()->user('sanctum')->id . $i : $i);
            if (Cache::has($newKey)) {
                Cache::forget($newKey);
            } else {
                break;
            }
        }
        $this->clearCacheGetAll('getAll');
        $this->clearCacheGetAll($key);
    }

    public function clearCacheGetAll($key): void
    {
        Cache::forget($this->getTableName() . '_' . $key);
        if (request()->user()) {
            Cache::forget($this->getTableName() . '_' . $key . request()->user()->id);
        }
    }

    public function clearAllCache(): void
    {
        Cache::clear();
    }

    protected function clearCacheByPattern($pattern)
    {
        $redis = Redis::connection();
        $cursor = 0;
        do {
            // Scan for keys matching the pattern
            list($cursor, $keys) = $redis->scan($cursor, ['MATCH' => $pattern, 'COUNT' => 100]);
            // Delete each key that matches the pattern
            foreach ($keys as $key) {
                $redis->del($key);
            }
        } while ($cursor != 0);
    }
}
