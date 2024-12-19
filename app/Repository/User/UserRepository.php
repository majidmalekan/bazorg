<?php

namespace App\Repository\User;

use App\Models\User;
use App\Repository\BaseRepository;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}
