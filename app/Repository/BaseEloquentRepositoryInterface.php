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
     * @param array|null $whereAttributes
     * @return bool
     */
    public function update(int $modelId, array $attributes,array $whereAttributes=null): bool;

    /**
     * @param int $id
     * @param array|null $whereAttributes
     * @return Model|null
     * @throws ModelNotFoundException
     */
    public function find(int $id,array $whereAttributes=null): ?Model;


    /**
     * @param int $id
     * @param array|null $whereAttributes
     * @return mixed
     */
    public function delete(int $id,array $whereAttributes=null): mixed;

    /**
     * @param Request $request
     * @param int $perPage
     * @param array|null $whereAttributes
     * @return LengthAwarePaginator
     */
    public function index(Request $request, int $perPage ,array $whereAttributes=null): LengthAwarePaginator;

    /**
     * @param int $id
     * @param array $attributes
     * @param array|null $whereAttributes
     * @return Model|null
     */
    public function updateAndFetch(int $id, array $attributes,array $whereAttributes=null): ?Model;

    /**
     * @param string|int|null $queryParam
     * @param array|null $whereAttributes
     * @return array|Collection
     */
    public function getAll(string|int $queryParam = null,array $whereAttributes=null): array|Collection;


}
