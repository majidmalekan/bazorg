<?php

namespace App\Repository;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

interface BaseEloquentRepositoryInterface
{
    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes): Model;

    /**
     * @param int $modelId
     * @param array $attributes
     * @return bool
     */
    public function update(int $modelId, array $attributes): bool;

    /**
     * @param int $id
     * @return Model|null
     * @throws ModelNotFoundException
     */
    public function find(int $id): ?Model;


    /**
     * @param int $id
     * @return mixed
     */
    public function delete(int $id): mixed;

    /**
     * @param Request $request
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function index(Request $request, int $perPage): LengthAwarePaginator;

    /**
     * @param int $id
     * @param array $attributes
     * @return Model|null
     */
    public function updateAndFetch(int $id, array $attributes): ?Model;


}
