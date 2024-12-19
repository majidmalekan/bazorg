<?php

namespace App\Traits;

use App\Exceptions\CommonException;
use Exception as ExceptionAlias;
use Illuminate\Support\Facades\DB;

trait DBTransactionLockedTrait
{
    /**
     * @param ExceptionAlias $exception
     * @throws CommonException
     */
    public function rollbackError(ExceptionAlias $exception)
    {
        DB::rollBack();
        throw new CommonException($exception->getMessage(), $exception->getCode());
    }
}
