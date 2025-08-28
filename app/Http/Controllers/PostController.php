<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; //dùng để lấy thông tin user đã đăng nhập
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    //phương thức index (hiển thị danh sách tất cả bản ghi)
    public function index()
    {
        //lấy danh sách bài viết kèm thông tin liên quan(tác giả, địa điểm, đánh giá, bình luận, lượt thích)
        $posts = Post::with('user', 'location', 'ratings', 'comments.user', 'likes')->get();
        return response()->json($posts);
    }

    //phương thức store (tạo mới dựa trên dữ liệu từ client)

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'location_id' => 'nullable|exists:locations,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $post = new Post([
            'title' => $request->title,
            'content' => $request->content,
            'location_id' => $request->location_id,
            'user_id' => auth()->id(),
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads/posts', 'public');
            $post->image = '/storage/' . $path;
        }

        $post->save();

        return back()->with('success', 'Đăng bài thành công!');
    }

    //phương thức show (xem chi tiết 1 bản ghi theo ID)
    public function show($id)
    {
        if (!is_numeric($id)) {
            return response()->json(['message' => 'ID bài viết không hợp lệ'], 400);
        }
        return \App\Models\Post::with(['user', 'location'])->findOrFail($id);
    }

    //phương thức update (cập nhật)
    public function update(Request $request, Post $post)
    {
        //nếu id đang đăng nhập khác user_id của post thì trả về lỗi 403
        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'location_id' => 'nullable|exists:locations,id',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('images', 'public');
        }

        $post->update($data);
        return response()->json($post);
    }

    //phương thức destroy (xóa)

    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            return back()->withErrors(['message' => 'Unauthorized']);
        }

        $post->delete();

        return back()->with('success', 'Post deleted');
    }

    //phương thức xóa bài viết bất kì dành cho admin
    public function adminDestroy(Post $post)
    {
        $post->delete();
        return response()->json(['message' => 'Post deleted by admin']);
    }

    public function pending(Request $request)
    {
        try {
            $perPage = $request->query('per_page', 10);
            $page = $request->query('page', 1);

            if (!is_numeric($perPage) || $perPage < 1) {
                return response()->json(['error' => 'Invalid per_page parameter'], 400);
            }
            if (!is_numeric($page) || $page < 1) {
                return response()->json(['error' => 'Invalid page parameter'], 400);
            }

            $posts = Post::where('status', 'pending')
                ->with('user:id,name')
                ->select('id', 'title', 'user_id', 'created_at')
                ->paginate($perPage, ['*'], 'page', $page);

            return response()->json($posts);
        } catch (\Exception $e) {
            Log::error('Error in PostController::pending: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function approve(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->update(['status' => 'approved']);
        return response()->json(['message' => 'Bài viết đã được duyệt']);
    }

    public function userPosts(Request $request)
    {
        return response()->json(Post::with('location')->where('user_id', $request->user()->id)->latest()->get());
    }
}
