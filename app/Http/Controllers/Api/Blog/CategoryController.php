<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::orderBy('title')->get();

        return response()->json([
            'data' => $categories
        ]);
    }
}
