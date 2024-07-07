<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait CrudOperations
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $with = $request->query('with', $this->with);
        $withArray = $with ? (is_array($with) ? $with : explode(',', $with)) : null;
        $modelInstance = new $this->model();
        return $this->fetchAll($modelInstance, $withArray);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate($this->rules());

        $modelInstance = (new $this->model())->create($validatedData);

        return response()->json($modelInstance, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, $id): JsonResponse
    {
        $with = $request->query('with', $this->with);
        $withArray = $with ? (is_array($with) ? $with : explode(',', $with)) : null;

        $query = (new $this->model())->newQuery();

        if ($withArray) {
            $query->with($withArray);
        }

        $modelInstance = $query->find($id);

        if (! $modelInstance) {
            return response()->json(['message' => 'Resource not found'], 404);
        }

        return response()->json($modelInstance, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validatedData = $request->validate($this->rules());

        $modelInstance = (new $this->model())->findOrFail($id);

        $modelInstance->update($validatedData);

        return response()->json($modelInstance, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $modelInstance = (new $this->model())->findOrFail($id);
        $modelInstance->delete();

        return response()->json(null, 204);
    }
}
