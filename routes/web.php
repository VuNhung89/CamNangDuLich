<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\TourController;
use App\Http\Controllers\Admin\HotelController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\BookingController;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// ✅ Dashboard admin
Route::middleware(['auth', 'check-role:admin'])->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/stats', [AdminController::class, 'stats']);
});

Route::middleware(['auth'])->group(function () {
    //Bài viết chờ duyệt
    Route::get('/posts/pending', [PostController::class, 'pending']);
    Route::get('/api/posts/{id}', [PostController::class, 'show']);
    Route::put('/api/posts/{id}/approve', [PostController::class, 'approve']);
    Route::delete('/api/posts/{post}', [PostController::class, 'adminDestroy']);
});

// Xem chi tiết bài viết admin (Inertia)
Route::middleware(['auth', 'check-role:admin'])->get('/admin/posts/{id}', function ($id) {
    return Inertia::render('Admin/PostDetail', ['postId' => $id]);
});

Route::middleware(['auth', 'check-role:admin'])->get('/admin/bookings', function () {
    return Inertia::render('Admin/Bookings');
});

Route::get('/admin/users/{id}', function ($id) {
    return Inertia::render('Admin/UserDetail', ['userId' => $id]);
})->middleware(['auth', 'check-role:admin']);

// ✅ Auth
Route::get('/register', fn() => Inertia::render('Auth/Register'))->name('register');
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->middleware('guest')->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('guest');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');
Route::middleware('auth')->post('/posts', [PostController::class, 'store']);
Route::middleware('auth')->delete('/posts/{post}', [PostController::class, 'destroy']);
Route::middleware('auth')->get('/user/posts', function () {
    return response()->json(
        Post::with('location')->where('user_id', auth()->id())->latest()->get()
    );
});
Route::get('/my-bookings', [BookingController::class, 'myBookings'])->middleware('auth');
Route::middleware('auth')->get('/api/user', function (Request $request) {
    return response()->json($request->user());
});

Route::middleware(['auth'])->group(function () {
    Route::post('/api/bookings', [BookingController::class, 'store']);
    Route::delete('/api/bookings/{booking}', [BookingController::class, 'destroy']);
});

// Dashboard admin
Route::middleware(['auth', 'check-role:admin'])->prefix('admin')->group(function () {
    Route::get('/users', fn() => Inertia::render('Admin/Users'))->name('admin.users');
    Route::get('/posts', fn() => Inertia::render('Admin/Posts'))->name('admin.posts');
    Route::get('/tours', fn() => Inertia::render('Admin/Tours'))->name('admin.tours');
    Route::get('/hotels', fn() => Inertia::render('Admin/Hotels'))->name('admin.hotels');
    Route::get('/locations', fn() => Inertia::render('Admin/Locations'))->name('admin.locations');
    Route::get('/events', fn() => Inertia::render('Admin/Events'))->name('admin.events');
    Route::get('/bookings/pending', [AdminBookingController::class, 'pending']);
    Route::put('/bookings/{id}/approve', [AdminBookingController::class, 'approve']);
    Route::delete('/bookings/{id}', [AdminBookingController::class, 'destroy']);
});

Route::middleware(['auth', 'check-role:admin'])->prefix('api/admin')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::get('/posts', [AdminPostController::class, 'index']);
    Route::post('/posts', [AdminPostController::class, 'store']);
    Route::get('/tours', [TourController::class, 'index']);
    Route::post('/tours', [TourController::class, 'store']);
    Route::post('/tours/{id}', [TourController::class, 'update']);
    Route::delete('/tours/{id}', [TourController::class, 'destroy']);
    Route::get('/hotels', [HotelController::class, 'index']);
    Route::post('/hotels', [HotelController::class, 'store']);
    Route::delete('/hotels/{id}', [HotelController::class, 'destroy']);
    Route::get('/locations', [LocationController::class, 'index']);
    Route::post('/locations/{id}', [LocationController::class, 'update']);
    Route::post('/locations', [LocationController::class, 'store']);
    Route::delete('/locations/{id}', [LocationController::class, 'destroy']);
    Route::get('/events', [EventController::class, 'index']);
    Route::post('/events', [EventController::class, 'store']);
    Route::post('/events/{id}', [EventController::class, 'update']);
    Route::delete('/events/{id}', [EventController::class, 'destroy']);
    Route::get('/bookings', [AdminBookingController::class, 'index']);
    Route::post('/bookings/{id}/approve', [AdminBookingController::class, 'approve']);
    Route::delete('/bookings/{id}', [AdminBookingController::class, 'destroy']);
});

// ✅ Trang chủ
Route::get('/', fn() => Inertia::render('Home'))->middleware('auth')->name('home');

// ✅ Các trang public
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::get('/forgot-password', fn() => Inertia::render('Auth/ForgotPassword'))->middleware('guest')->name('password.request');
Route::get('/locations', fn() => Inertia::render('Locations/Locations'));
Route::get('/locations/{id}', fn($id) => Inertia::render('Locations/LocationDetail', ['locationId' => $id]));
Route::get('/tours', fn() => Inertia::render('Tours/Tours'));
Route::get('/tours/{id}', fn($id) => Inertia::render('Tours/TourDetail', ['tourId' => $id]));
Route::get('/hotels', fn() => Inertia::render('Hotels/Hotels'));
Route::get('/hotels/{id}', fn($id) => Inertia::render('Hotels/HotelDetail', ['hotelId' => $id]));
Route::get('/events', fn() => Inertia::render('Events/Events'));
Route::get('/events/{id}', fn($id) => Inertia::render('Events/EventDetail', ['eventId' => $id]));
Route::get('/posts', fn() => Inertia::render('Posts/Posts'));
Route::get('/posts/{id}', fn($id) => is_numeric($id)
    ? Inertia::render('Posts/PostDetail', ['postId' => $id])
    : abort(404, 'Bài viết không tồn tại'));
Route::get('/events/{id}', fn($id) => Inertia::render('Events/EventDetail', ['eventId' => $id]));

// ✅ Hồ sơ cá nhân – dùng ProfileController
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update']);
    Route::post('/change-password', [ProfileController::class, 'changePassword']);
});

require __DIR__ . '/auth.php';
