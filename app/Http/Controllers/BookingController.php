<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /* Lọc tất cả Booking mà user_id là người đang đăng nhập (Auth::id()).
        with('location'): lấy thêm thông tin địa điểm */

    // Danh sách booking của người dùng
    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())->with(['tour', 'hotel'])->get();
        return response()->json($bookings);
    }

    // Tạo booking mới
    public function store(Request $request)
    {
        // Nếu chưa đăng nhập, trả lỗi 401 rõ ràng
        if (!Auth::check()) {
            return response()->json(['message' => 'Chưa đăng nhập'], 401);
        }

        $data = $request->validate([
            'tour_id' => 'nullable|exists:tours,id',
            'hotel_id' => 'nullable|exists:hotels,id',
            'booking_date' => 'required|date|after:today',
            'total_price' => 'required|numeric|min:0',
        ]);

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'tour_id' => $data['tour_id'] ?? null,
            'hotel_id' => $data['hotel_id'] ?? null,
            'booking_date' => $data['booking_date'],
            'total_price' => $data['total_price'],
            'status' => 'pending',
        ]);

        $booking->load(['tour', 'hotel']);

        return response()->json($booking, 201);
    }

    // Xóa booking (nếu là người tạo)
    public function destroy(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $booking->delete();
        return response()->json(['message' => 'Booking cancelled']);
    }

    //Xem chi tiết booking
    public function myBookings()
    {
        $user = auth()->user();

        $bookings = Booking::with(['tour', 'hotel'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return inertia('Bookings/MyBookings', [
            'bookings' => $bookings
        ]);
    }
}
