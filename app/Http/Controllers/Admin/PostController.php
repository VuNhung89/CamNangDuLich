<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{

    public function index(Request $request)
    {
        $posts = Post::with('user')
            ->orderByDesc('created_at')
            ->paginate($request->get('per_page', 10));

        return response()->json($posts);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'location_id' => 'nullable|exists:locations,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $post = new Post();
        $post->user_id = auth()->id();
        $post->title = $request->title;
        $post->content = $request->content;
        $post->location_id = $request->location_id;

        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('posts', 'public');
            $post->image = '/storage/' . $image;
        }

        $post->save();

        return response()->json(['message' => 'Tạo bài viết thành công', 'post' => $post], 201);
    }
}
