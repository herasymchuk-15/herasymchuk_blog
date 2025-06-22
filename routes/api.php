<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Post; // не забудь імпортувати модель
use App\Http\Controllers\Api\Blog\PostController;
use App\Http\Controllers\Api\Blog\CategoryController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('blog/posts', [PostController::class, 'index']);
Route::get('blog/posts/{id}', [PostController::class, 'show']);
Route::get('blog/categories', [CategoryController::class, 'index']);

Route::delete('/blog/posts/{id}', function ($id) {
    $post = Post::find($id);
    if (!$post) {
        return response()->json(['message' => 'Post not found'], 404);
    }
    $post->delete();
    return response()->json(null, 204);
});

Route::post('/blog/posts', function(Request $request) {
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'slug' => 'required|string|unique:posts,slug',
        'description' => 'nullable|string',
        'category_id' => 'required|integer|exists:categories,id',
        'published_at' => 'nullable|date',
        'is_published' => 'required|boolean'
    ]);

    $post = Post::create($validated);

    return response()->json($post, 201);
});

Route::put('/blog/posts/{id}', function(Request $request, $id) {
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'slug' => 'required|string|unique:posts,slug,' . $id,
        'description' => 'nullable|string',
        'category_id' => 'required|integer|exists:categories,id',
        'published_at' => 'nullable|date',
        'is_published' => 'required|boolean'
    ]);

    $post = Post::findOrFail($id);
    $post->update($validated);

    return response()->json($post);
});
