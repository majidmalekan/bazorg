<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\CommonException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\UserResource;
use App\Service\UserService;
use App\Traits\CrudForPersonalAccessTokenTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Yaml\Yaml;

class AuthController extends Controller
{
    use CrudForPersonalAccessTokenTrait;

    protected UserService $service;

    public function __construct(UserService $userService)
    {
        $this->service = $userService;
    }

    /**
     * register a user.
     * @param RegisterRequest $request
     * @return JsonResponse
     * @throws CommonException
     */
    public function register(RegisterRequest $request): JsonResponse
    {

        try {
            $inputs = $request->only('emails');
            $inputs["password"] = bcrypt($request->post('password'));
            $user = $this->service->create($inputs);
            $token = $this->createANewToken($request->post('email'), $user);
            return success('', [
                "access_token" => $token,
                "token_type" => env('JWT_TYPE'),
                "expire_in" => env('JWT_TTL'),

            ]);
        } catch (\Exception $exception) {
            throw new CommonException($exception);
        }

    }

    /**
     * Store a newly created resource in storage.
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws CommonException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();
            if (!$this->validateCredentials($inputs))
                return failed('your credentials is false', 401);
            $token = $this->createANewToken(auth()->user()->email, auth()->user());
            return success('', [
                "access_token" => $token,
                "token_type" => env('JWT_TYPE'),
                "expire_in" => env('JWT_TTL'),

            ]);
        } catch (\Exception $exception) {
            throw new CommonException($exception);
        }
    }

    /**
     * Display the specified resource.
     * @param Request $request
     * @return JsonResponse
     * @throws CommonException
     */
    public function me(Request $request): JsonResponse
    {
        try {
            return success('',new UserResource($this->service->find($request->user()->id)));
        } catch (\Exception $exception) {
            throw new CommonException($exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateUserRequest $request
     * @param string $id
     * @return JsonResponse
     * @throws CommonException
     */
    public function update(UpdateUserRequest $request, string $id): JsonResponse
    {
        try {
            $inputs = $request->validated();
            return success('',new UserResource($this->service->updateAndFetch($id, $inputs)));
        } catch (\Exception $exception) {
            throw new CommonException($exception->getMessage());
        }
    }

    /**
     * @param array $inputs
     * @return bool
     */
    private function validateCredentials(array $inputs): bool
    {
        return Auth::attempt($inputs);
    }
}
