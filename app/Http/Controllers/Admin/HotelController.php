<?php

// app/Http/Controllers/Admin/HotelController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\Location;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::with('location:id,name')->get();
        return response()->json($hotels);
    }

    public function store(Request $request)
    {
        $request->validate([
            'location_id' => 'required|exists:locations,id',
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('hotels', 'public');
        }

        $hotel = Hotel::create([
            'location_id' => $request->location_id,
            'name' => $request->name,
            'address' => $request->address,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return response()->json($hotel, 201);
    }

    public function destroy($id)
    {
        $hotel = Hotel::findOrFail($id);
        $hotel->delete();

        return response()->json(['message' => 'Xóa khách sạn thành công']);
    }
}
