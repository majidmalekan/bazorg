<?php

namespace App\Repository;

use App\Service\Cache\CacheContext;
use App\Traits\CacheRepositoryTrait;
use App\Traits\DBTransactionLockedTrait;
use App\Traits\TableInformationTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BaseRepository implements BaseEloquentRepositoryInterface
{
    use TableInformationTrait, CacheRepositoryTrait, DBTransactionLockedTrait;

    /**
     * @var Model
     */

    public Model $model;
    public CacheContext $cache;

    /**
     * @param Model $model
     * @param CacheContext $cache
     */
    public function __construct(Model $model,CacheContext $cache)
    {
        $this->model = $model;
        $this->cache = $cache;
    }


    /**
     * @inheritDoc
     */
    public function update(int $id, array $attributes, array $whereAttributes = null): bool
    {

        $this->clearCache('index');
        $this->clearCache('find', $id);

        return $this->model->query()
            ->where('id', $id)
            ->when($whereAttributes != null, function ($query) use ($whereAttributes) {
                $query->where($whereAttributes);
            })
            ->update($attributes);
    }


    /**
     * @inheritDoc
     */
    public function find(int $id, array $whereAttributes = null): ?Model
    {
        return $this->cache->remember($this->getTableName() . '_find_' . (auth('sanctum')->check() ? request()->user('sanctum')->id . $id : $id),
            (auth('sanctum')->check() ? env('CACHE_EXPIRE_TIME') : env('CACHE_EXPIRE_GENERAL_TIME')),
            function () use ($id, $whereAttributes) {
                return $this->model
                    ->query()
                    ->where('id', $id)
                    ->when($whereAttributes != null, function ($query) use ($whereAttributes) {
                        $query->where($whereAttributes);
                    })
                    ->firstOrFail($id);
            });
    }


    /**
     * @inheritDoc
     */
    public function create(array $attributes): Model
    {
        $this->clearCache('index');
        return $this->model
            ->query()
            ->create($attributes);
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id, array $whereAttributes = null): mixed
    {
        $this->clearCache('find', $id);
        $this->clearCache('index');
        return $this->model
            ->query()
            ->where('id', $id)
            ->when($whereAttributes != null, function ($query) use ($whereAttributes) {
                $query->where($whereAttributes);
            })
            ->delete();
    }


    /**
     * @inheritDoc
     */
    public function index(Request $request, int $perPage, array $whereAttributes = null): LengthAwarePaginator
    {

        return $this->cache->remember($this->getTableName() . '_index_' . ($request->user() ? $request->user()->id : '') . $request->get('page', 1),
            (auth('sanctum')->check() ? env('CACHE_EXPIRE_TIME') : env('CACHE_EXPIRE_GENERAL_TIME')),
            function () use ($request, $perPage, $whereAttributes) {
                return $this->model->query()
                    ->when($request->user(), function ($query) use ($request) {
                        $query->when(!$request->user()->is_admin, function ($query) use ($request) {
                            $query->where('user_id', $request->user()->id);
                        });
                    })
                    ->when($whereAttributes != null, function ($query) use ($whereAttributes) {
                        $query->where($whereAttributes);
                    })
                    ->when($request->has('filter'), function ($query) use ($request) {
                        $query->where($request->input('filter'), '=', $request->get('filter_value'));
                    })
                    ->orderBy($request->get('sort', 'id'), $request->get('direction', 'DESC'))
                    ->paginate($perPage, '*', '', $request->get('page', 1));
            });
    }

    /**
     * @inheritDoc
     */
    public function updateAndFetch(int $id, array $attributes, array $whereAttributes = null): ?Model
    {
        if ($this->update($id, $attributes, $whereAttributes)) {
            return $this->find($id);
        }
        return null;
    }


    /**
     * @inheritDoc
     */
    public function getAll(string|int $queryParam = null, array $whereAttributes = null): array|Collection
    {
        return $this->cache->remember($this->getTableName() . '_getAll',
            (auth('sanctum')->check() ? env('CACHE_EXPIRE_TIME') : env('CACHE_EXPIRE_GENERAL_TIME')),
            function () use ($queryParam, $whereAttributes) {
                return $this->model->query()
                    ->when(auth('sanctum')->check(), function ($query) {
                        $query->when(!request()->user('sanctum')->is_admin, function ($query) {
                            $query->where('user_id', request()->user('sanctum')->id);
                        });
                    })
                    ->when($whereAttributes != null, function ($query) use ($whereAttributes) {
                        $query->where($whereAttributes);
                    })
                    ->get();
            });

    }


}
