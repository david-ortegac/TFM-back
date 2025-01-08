<?php

namespace App\Http\Controllers\API;

use App\Models\Branch;
use Illuminate\Http\Request;
use App\Http\Requests\BranchRequest;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\BranchResource;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $branches = Branch::paginate();

        return BranchResource::collection($branches);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BranchRequest $request): Branch
    {
        return Branch::create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch): Branch
    {
        return $branch;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BranchRequest $request, Branch $branch): Branch
    {
        $branch->update($request->validated());

        return $branch;
    }

    public function destroy(Branch $branch): Response
    {
        $branch->delete();

        return response()->noContent();
    }
}
