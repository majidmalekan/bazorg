<?php

namespace App\Repository\User;

use App\Models\User;
use App\Repository\BaseRepository;
use App\Service\Cache\CacheContext;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model,CacheContext $cache)
    {
        parent::__construct($model,$cache);
    }
}
