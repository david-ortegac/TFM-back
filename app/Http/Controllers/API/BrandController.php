<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $brands = Brand::paginate();

        foreach ($brands as $brand) {
            $brand->user = User::find($brand->user_id);
            unset($brand->user_id);
            unset($brand->created_at);
            unset($brand->updated_at);
            unset($brand->status);
            unset($brand->user->type);
            unset($brand->user->id);
        }

        return BrandResource::collection($brands);
    }

    public function privateIndex(): JsonResponse
    {
        if (!$this->validateUser()) {
            return response()->json(['error' => 'Unauthorized'], HttpResponse::HTTP_UNAUTHORIZED);
        }

        $brands = Brand::where('user_id', auth()->id())->get();

        return response()->json($brands);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BrandRequest $request): JsonResponse
    {
        if (!$this->validateUser()) {
            return response()->json(['error' => 'Unauthorized'], HttpResponse::HTTP_BAD_REQUEST);
        }

        $brand = new Brand();
        $brand->property = $request->property;
        $brand->user_id = auth()->id();
        $brand->name = $request->name;
        $brand->description = $request->description;
        $brand->phone = $request->phone;
        $brand->email = $request->email;
        $brand->address = $request->address;
        $brand->save();

        return response()->json($brand);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $brand = Brand::find($id);
        if (isset($brand)) {
            return response()->json($brand);
        } else {
            return response()->json(['error' => 'Not found'], HttpResponse::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, Brand $brand): JsonResponse
    {
        $brand->update($request->validated());

        return response()->json($brand, HttpResponse::HTTP_OK);
    }

    public function destroy(int $id): JsonResponse
    {
        if (!$this->validateUser()) {
            return response()->json(['error' => 'Unauthorized'], HttpResponse::HTTP_UNAUTHORIZED);
        }
        $brand = Brand::find($id);

        if(isset($brand)) {
            $brand->delete();

            return response()->json('Deleted success', HttpResponse::HTTP_OK);
        }else{
            return response()->json(['error' => 'Not found'], HttpResponse::HTTP_NOT_FOUND);
        }

    }

    protected function validateUser(): bool
    {
        // Check if the authenticated user has the necessary role
        if (auth()->user()->type == "admin" || auth()->user()->type == "Superadmin") {
            return true;
        }
        return false;

    }
}
