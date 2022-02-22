<?php

namespace App\Http\Controllers;

use App\Models\Movies;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class MoviesController extends Controller
{
    /**
     * List of movies.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json(Movies::latest()->filter(request(['title', 'category']))->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "title" => "required",
            "category" => "required"
        ]);

        if($validator->fails()) {
            return response()->json(["error" => "Validation failed"], 400);
        }

        try {
            Movies::create($validator->validated());
        }catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()], 401);
        }

        return response()->json(["success" => "Movie added"], 200);
    }
}
