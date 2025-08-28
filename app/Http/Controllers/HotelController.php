<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::with('location')->get();
        return response()->json($hotels);
    }

    public function show($id)
    {
        return Hotel::with('location')->findOrFail($id);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'required|string',
            'description' => 'required|string'
        ]);

        $hotel = Hotel::create($data);
        $hotel->load('location');
        return response()->json($hotel, 201);
    }

    public function update(Request $request, Hotel $hotel)
    {
        $data = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'required|string',
            'description' => 'required|string'
        ]);

        $hotel->update($data);
        $hotel->load('location');
        return response()->json($hotel);
    }

    public function destroy(Hotel $hotel)
    {
        $hotel->delete();
        return response()->json(null, 204);
    }
}
