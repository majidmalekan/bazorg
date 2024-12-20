<?php

namespace Tests\Feature;

use App\Enum\ProductStatusEnum;
use App\Models\Product;
use App\Repository\BaseRepository;
use App\Repository\Product\ProductRepository;
use App\Service\Cache\CacheContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class BaseRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected BaseRepository $repository;
    protected CacheContext $cache;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ProductRepository(new Product(), app(CacheContext::class));
        $this->cache = app(CacheContext::class);
    }

    public function test_find_caches_data_correctly()
    {
        $model = Product::factory()->create();
        $this->cache->clear();
        $this->repository->find($model->id);
        $cacheKey = $this->repository->getTableName() . '_find_' . $model->id;
        $this->assertTrue($this->cache->has($cacheKey));
        $this->assertEquals($model->toArray(), $this->cache->get($cacheKey)->toArray());
    }

    public function test_update_clears_cache()
    {
        $model = Product::factory()->create();
        $cacheKey = $this->repository->getTableName() . '_find_' . $model->id;
        $this->cache->put($cacheKey, $model, now()->addMinutes(10));
        $this->assertTrue($this->cache->has($cacheKey));
        $updated = $this->repository->update($model->id, ['title' => fn() => fake()->words(7, true),]);
        $this->assertTrue($updated);
        $this->assertFalse($this->cache->has($cacheKey));
    }

    public function test_create_clears_index_cache()
    {
        $cacheKey = $this->repository->getTableName() . '_index_';
        $this->cache->put($cacheKey, ['data'], now()->addMinutes(10));
        $this->assertTrue($this->cache->has($cacheKey));
        $title = fake()->words(7, true);
        $this->repository->create([
            'title' => $title,
            'description' => fake()->text(191),
            'sub_label' => fake()->text(50),
            'slug' => Str::slug($title),
            'sku' => strtoupper(fake()->unique()->bothify('??###')),
            'status' => ProductStatusEnum::Approved]);
        $this->assertFalse($this->cache->has($cacheKey));
    }

    public function test_delete_removes_data_and_clears_cache()
    {
        $model = Product::factory()->create();
        $findCacheKey = $this->repository->getTableName() . '_find_' . $model->id;
        $indexCacheKey = $this->repository->getTableName() . '_index_';
        $this->cache->put($findCacheKey, $model, now()->addMinutes(10));
        $this->cache->put($indexCacheKey, ['data'], now()->addMinutes(10));
        $this->assertTrue($this->cache->has($findCacheKey));
        $this->assertTrue($this->cache->has($indexCacheKey));
        $deleted = $this->repository->delete($model->id);
        $this->assertTrue(!!$deleted);
        $this->assertFalse($this->cache->has($findCacheKey));
        $this->assertFalse($this->cache->has($indexCacheKey));
    }

    public function test_index_paginates_and_caches_results()
    {
        Product::factory(50)->create();
        $cacheKey = $this->repository->getTableName() . '_index_1';
        $this->cache->clear();
        $results = $this->repository->index(request(), 10);
        $this->assertCount(10, $results);
        $this->assertTrue($this->cache->has($cacheKey));
    }

    public function test_update_and_fetch_returns_updated_model()
    {
        $model = Product::factory()->create();
        $title = fake()->words(7, true);
        $updatedModel = $this->repository->updateAndFetch($model->id, ['title' => $title]);
        $this->assertNotNull($updatedModel);
        $this->assertEquals($title, $updatedModel->title);
    }
}
