<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\BranchRequest;
use App\Http\Resources\BranchResource;
use App\Models\Branch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class BranchController extends Controller
{

    public function index(Request $request)
    {
        if ($request->brand_id != null) {
            $branches = Branch::where('brand_id', $request->brand_id)->paginate();

            return BranchResource::collection($branches);
        }

        return response()->json(['message' => 'Brand ID is required.'], HttpResponse::HTTP_BAD_REQUEST);

    }

    public function privateIndex(Request $request)
    {
        if (!$this->validateUser()) {
            return response()->json(['error' => 'Unauthorized'], HttpResponse::HTTP_UNAUTHORIZED);
        }

        $branches = Branch::where('user_id', auth()->id())->get();

        return response()->json($branches);
    }

    public function store(BranchRequest $request): JsonResponse
    {
        if (!$this->validateUser()) {
            return response()->json(['error' => 'Unauthorized'], HttpResponse::HTTP_BAD_REQUEST);
        }

        return response()->json([Branch::create($request->validated()), HttpResponse::HTTP_CREATED]);
    }

    public function show(int $id): JsonResponse
    {
        $branch = Branch::find($id);

        if ($branch == null) {
            return response()->json(['message' => 'Branch not found.'], HttpResponse::HTTP_NOT_FOUND);
        }

        return response()->json($branch);
    }


    public function update(BranchRequest $request, Branch $branch): JsonResponse
    {
        $branch->update($request->validated());

        return response()->json($branch);
    }

    public function destroy(int $id): JsonResponse
    {
        if (!$this->validateUser()) {
            return response()->json(['error' => 'Unauthorized'], HttpResponse::HTTP_UNAUTHORIZED);
        }

        $branch = Branch::find($id);
        if (!isset($branch)) {
            return response()->json(['error' => 'Not found'], HttpResponse::HTTP_NOT_FOUND);
        }

        $branch->delete();

        return response()->noContent();
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
