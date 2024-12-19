<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\CommonException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Product\ProductResource;
use App\Service\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * @var ProductService
     */
    protected ProductService $service;

    /**
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->service = $productService;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     * @throws CommonException
     */
    public function index(Request $request): JsonResponse
    {
        try {
            return success('', new ProductCollection($this->service->index($request)));
        } catch (\Exception $exception) {
            throw new CommonException($exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreProductRequest $request
     * @return JsonResponse
     * @throws CommonException
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();
            return success('', $this->service->create($inputs));
        } catch (\Exception $exception) {
            throw new CommonException($exception->getMessage());
        }

    }

    /**
     * Display the specified resource.
     * @param string $id
     * @return JsonResponse
     * @throws CommonException
     */
    public function show(string $id): JsonResponse
    {
        try {
            return success('', new ProductResource($this->service->find($id)));
        } catch (\Exception $exception) {
            throw new CommonException($exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateProductRequest $request
     * @param string $id
     * @return JsonResponse
     * @throws CommonException
     */
    public function update(UpdateProductRequest $request, string $id): JsonResponse
    {
        try {
            $inputs = $request->validated();
            return success('', new ProductResource($this->service->updateAndFetch($id, $inputs)));
        } catch (\Exception $exception) {
            throw new CommonException($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param string $id
     * @return JsonResponse
     * @throws CommonException
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            return success('', $this->service->delete($id));
        } catch (\Exception $exception) {
            throw new CommonException($exception->getMessage());
        }
    }
}
