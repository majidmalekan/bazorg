<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class CommonException extends Exception
{
    public function render(Request $request,Throwable $e ): JsonResponse
    {

        return failed($e->getMessage(), $e->getCode());
    }
}
