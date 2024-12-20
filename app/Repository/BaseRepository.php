<?php

namespace App\Repository;

use App\Service\Cache\CacheContext;
use App\Traits\CacheRepositoryTrait;
use App\Traits\DBTransactionLockedTrait;
use App\Traits\TableInformationTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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
    public function update(int $id, array $attributes): bool
    {

        $this->clearCache('index');
        $this->clearCache('find', $id);
        return $this->model->query()
            ->where('id', $id)
            ->update($attributes);
    }


    /**
     * @inheritDoc
     */
    public function find(int $id): ?Model
    {
        return $this->cache->remember($this->getTableName() . '_find_' . (auth('sanctum')->check() ? request()->user('sanctum')->id . $id : $id),
            (auth('sanctum')->check() ? env('CACHE_EXPIRE_TIME') : env('CACHE_EXPIRE_GENERAL_TIME')),
            function () use ($id) {
                return $this->model
                    ->query()
                    ->where('id', $id)
                    ->firstOrFail();
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
    public function delete(int $id): mixed
    {
        $this->clearCache('find', $id);
        $this->clearCache('index');
        return $this->model
            ->query()
            ->where('id', $id)
            ->delete();
    }


    /**
     * @inheritDoc
     */
    public function index(Request $request, int $perPage): LengthAwarePaginator
    {

        return $this->cache->remember($this->getTableName() . '_index_' . ($request->user() ? $request->user()->id : '') . $request->get('page', 1),
            (auth('sanctum')->check() ? env('CACHE_EXPIRE_TIME') : env('CACHE_EXPIRE_GENERAL_TIME')),
            function () use ($request, $perPage) {
                return $this->model->query()
                    ->when($request->user(), function ($query) use ($request) {
                        $query->when(!$request->user()->is_admin, function ($query) use ($request) {
                            $query->where('user_id', $request->user()->id);
                        });
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
}
