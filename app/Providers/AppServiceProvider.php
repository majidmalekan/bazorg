<?php

namespace App\Providers;

use App\Repository\BaseEloquentRepositoryInterface;
use App\Repository\BaseRepository;
use App\Repository\Product\ProductRepository;
use App\Repository\Product\ProductRepositoryInterface;
use App\Repository\User\UserRepository;
use App\Repository\User\UserRepositoryInterface;
use App\Service\Cache\CacheContext;
use App\Service\Cache\DatabaseCache;
use App\Service\Cache\FileCache;
use App\Service\Cache\RedisCache;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(BaseEloquentRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CacheContext::class, function ($app) {
            $strategy = match (config('cache.default')) {
                'redis' => new RedisCache(),
                'database' => new DatabaseCache(),
                default => new FileCache(),
            };
            return new CacheContext($strategy);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
