<?php

namespace App\Traits;

use App\Exceptions\CommonException;
use App\Service\UserService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

trait CrudForPersonalAccessTokenTrait
{
    /**
     * @param $token
     * @return Model|null
     */
    protected function getPersonalAccessToken($token): ?Model
    {
        return DB::table('personal_access_tokens')
            ->where('token', hash('sha256', $token))
            ->first();
    }

    protected function deleteExpireToken($tokenableId): int
    {
        return DB::table('personal_access_tokens')
            ->where('tokenable_id', $tokenableId)
            ->delete();
    }

    /**
     * @throws BindingResolutionException|CommonException
     */
    protected function createANewToken($tokenable_id, Model $user = null)
    {
        try {
            DB::beginTransaction();
            $userFind = $user != null ? $user : app()->make(UserService::class)->find(auth('sanctum')->user()?->id);
            $this->deleteExpireToken($userFind->id);
            $token= $userFind->createToken($tokenable_id)
                ->plainTextToken;
            DB::commit();
            return $token;
        }
        catch (\Exception $e) {
            DB::rollBack();
          return  throw new CommonException($e->getMessage(),401);
        }

    }
}
