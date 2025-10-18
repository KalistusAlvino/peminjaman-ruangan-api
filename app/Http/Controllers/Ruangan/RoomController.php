<?php

namespace App\Http\Controllers\Ruangan;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        try {
            $request->validate([
                'room_name' => 'nullable|string',
            ]);
            $rooms = Room::where('room_name', 'like', '%' . $request->room_name . '%')
                ->with([
                    'bookings' => function ($query) use ($request) {
                        $query->whereDate('date', $request->date)
                            ->with('user');
                    }
                ])
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Rooms data fetched successfully',
                'data' => RoomResource::collection($rooms)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching bookings. Please try again.' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
}
