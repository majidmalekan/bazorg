<?php

namespace App\Service;

use App\Repository\Product\ProductRepositoryInterface;

class ProductService extends BaseService
{
    /**
     * @param ProductRepositoryInterface $repository
     */
    public function __construct(ProductRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
