<?php

namespace App\Http\Controllers;

use App\Models\Movies;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MoviesController extends Controller
{
    /**
     * List of movies.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $movies = null;
        try {
            $movies = response()->json(Movies::latest()->filter(request(['title', 'category']))->get());
        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()], 400);
        }
        return $movies;
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
            "title" => "required|unique:movies|max:255",
            "category" => "required|max:255"
        ], [
            'required' => 'The :attribute field is required.',
            'max' => 'The :attribute must be less than :max.',
            'unique' => 'This movie is already on database.',
        ]);

        if($validator->fails()) {
            return response()->json(["error" => $validator->messages()], 400);
        }

        try {
            Movies::create($validator->validated());
        }catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()], 400);
        }

        return response()->json(["success" => "Movie added"], 200);
    }
}
