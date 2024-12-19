<?php

namespace App\Service;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BaseService
{
    /**
     * @var mixed
     */
    public mixed $repository;

    /**
     * @var int
     */
    protected int $perPageLimit = 20;

    /**
     * BaseService constructor.
     *
     * @param $repository
     */
    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get pagination per-page according to $perPageLimit.
     *
     * @param int $perPage
     *
     * @return int
     */
    protected function getPerPage(int $perPage): int
    {
        return ($perPage > $this->perPageLimit) ? $this->perPageLimit : $perPage;
    }

    /**
     * @param int $id
     * @param array $attributes
     * @param array|null $whereAttributes
     * @return bool
     */
    public function update(int $id, array $attributes, array $whereAttributes = null): bool
    {
        return $this->repository->update($id, $attributes,$whereAttributes);
    }

    /**
     * @param int $id
     * @param array $attributes
     * @param array|null $whereAttributes
     * @return Model|null
     */
    public function updateAndFetch(int $id, array $attributes, array $whereAttributes = null): ?Model
    {
        if ($this->update($id, $attributes, $whereAttributes)) {
            return $this->find($id);
        }
        return null;
    }

    /**
     * @param int $id
     * @return Model|null
     */
    public function find(int $id): ?Model
    {
        return $this->repository->find($id);
    }

    /**
     * @param array $inputs
     * @return Model
     */
    public function create(array $inputs): Model
    {
        return $this->repository->create($inputs);
    }

    /**
     * @param int $id
     * @param array|null $whereAttributes
     * @return bool
     */
    public function delete(int $id, array $whereAttributes = null): bool
    {
        return $this->repository->delete($id, $whereAttributes);
    }


    /**
     * @param Request $request
     * @param array|null $whereAttributes
     * @return LengthAwarePaginator
     */
    public function index(Request $request,array $whereAttributes=null): LengthAwarePaginator
    {
        $perPage = $this->getPerPage((int)$request->query('perPage', $this->perPageLimit));
        return $this->repository->index($request, $perPage ,$whereAttributes);
    }

    /**
     * @param string|int|null $queryParam
     * @param array|null $whereAttributes
     * @return mixed
     */
    public function getAll(string|int $queryParam = null, array $whereAttributes = null): mixed
    {
        return $this->repository->getAll($queryParam, $whereAttributes);
    }
}
