<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::all();
        return $brands;
        return BrandResource::collection($brands);
    }

    public function privateIndex()
    {
        return $this->validateUser();
        if (!$this->validateUser()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $brands = Brand::where('user_id', auth()->id());

        return response()->json($brands);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BrandRequest $request): Brand
    {
        if (!$this->validateUser()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

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

    protected function validateUser(): bool
    {
        // Check if the authenticated user has the necessary role
        if (auth()->user()->type != 'admin' || auth()->user()->type != 'Superadmin') {
            return false;
        }
        return true;
    }
}
