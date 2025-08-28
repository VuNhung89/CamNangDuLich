<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Comment;
use App\Models\Event;
use App\Models\Hotel;
use App\Models\Like;
use App\Models\Location;
use App\Models\Post;
use App\Models\Rating;
use App\Models\Tour;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function stats()
    {
        return response()->json([
            'users' => User::count(),
            'posts' => Post::count(),
            'tours' => Tour::count(),
            'bookings' => Booking::count(),
            'hotels' => Hotel::count(),
            'locations' => Location::count(),
            'events' => Event::count(),
            'videos' => Video::count(),
            'comments' => Comment::count(),
            'ratings' => Rating::count(),
            'likes' => Like::count(),
            'pendingPosts' => Post::where('status', 'pending')->count(),
        ]);
    }
}
