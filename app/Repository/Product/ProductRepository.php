<?php

namespace App\Repository\Product;

use App\Models\Product;
use App\Repository\BaseRepository;
use App\Service\Cache\CacheContext;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    /**
     * @param Product $model
     * @param CacheContext $cache
     */
    public function __construct(Product $model,CacheContext $cache)
    {
        parent::__construct($model,$cache);
    }
}
