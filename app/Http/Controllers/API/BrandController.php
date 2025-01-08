<?php

namespace App\Http\Controllers\Api;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Requests\BrandRequest;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $brands = Brand::paginate();

        return BrandResource::collection($brands);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BrandRequest $request): Brand
    {
        return Brand::create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand): Brand
    {
        return $brand;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BrandRequest $request, Brand $brand): Brand
    {
        $brand->update($request->validated());

        return $brand;
    }

    public function destroy(Brand $brand): Response
    {
        $brand->delete();

        return response()->noContent();
    }
}
