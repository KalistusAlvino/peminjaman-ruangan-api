<?php

namespace App\Http\Controllers\Peminjaman;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoomResource;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'room_id' => 'required|exists:rooms,id',
                'date' => 'required|date',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
                'purpose' => 'required|string',
            ]);
            $userId = Auth::user()->id;
            $isBooked = Booking::where('room_id', $validated['room_id'])
                ->where('date', $validated['date'])
                ->where('status', 'approved')
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                        ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                        ->orWhere(function ($q) use ($validated) {
                            $q->where('start_time', '<=', $validated['start_time'])
                                ->where('end_time', '>=', $validated['end_time']);
                        });
                })
                ->with(['user', 'room'])
                ->first();

            $notApproved = Booking::where('room_id', $validated['room_id'])
                ->where('date', $validated['date'])
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                        ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                        ->orWhere(function ($q) use ($validated) {
                            $q->where('start_time', '<=', $validated['start_time'])
                                ->where('end_time', '>=', $validated['end_time']);
                        });
                })
                ->with(['user', 'room'])
                ->first();

            if ($isBooked) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your selected room is already booked by ' . $isBooked->user->username . ' from ' . $isBooked->start_time . ' to ' . $isBooked->end_time . '. Please select a different time or room.',
                    'data' => []
                ], 409);
            }

            if ($notApproved != null) {
                Booking::create([
                    'user_id' => $userId,
                    'room_id' => $validated['room_id'],
                    'date' => $validated['date'],
                    'start_time' => $validated['start_time'],
                    'end_time' => $validated['end_time'],
                    'purpose' => $validated['purpose'],
                    'status' => 'pending',
                ]);
                return response()->json([
                    'success' => true,
                    'message' => 'Successfully booking!. But your room already booked by ' . $notApproved->user->username . ' from ' . $notApproved->start_time . ' to ' . $notApproved->end_time . '. Please wait until the admin approve your booking.',
                    'data' => []
                ], 201);
            }
            Booking::create([
                'user_id' => $userId,
                'room_id' => $validated['room_id'],
                'date' => $validated['date'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'purpose' => $validated['purpose'],
                'status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully',
                'data' => []
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the booking. Please try again.' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function approve($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            $isBooked = Booking::where('room_id', $booking->room_id)
                ->where('date', $booking->date)
                ->where('status', 'approved')
                ->where(function ($query) use ($booking) {
                    $query->whereBetween('start_time', [$booking->start_time, $booking->end_time])
                        ->orWhereBetween('end_time', [$booking->start_time, $booking->end_time])
                        ->orWhere(function ($q) use ($booking) {
                            $q->where('start_time', '<=', $booking->start_time)
                                ->where('end_time', '>=', $booking->end_time);
                        });
                })
                ->with(['user', 'room'])
                ->first();
            if ($isBooked) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your selected room is already booked by ' . $isBooked->user->username . ' from ' . $isBooked->start_time . ' to ' . $isBooked->end_time . '. Please select a different time or room.',
                    'data' => []
                ], 409);
            }
            Booking::where('id', $id)->update([
                'status' => 'approved'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking approved successfully',
                'data' => []
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve booking',
                'data' => []
            ], 500);
        }
    }
    public function reject($id)
    {
        try {
            Booking::where('id', $id)->update([
                'status' => 'rejected'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking rejected successfully',
                'data' => []
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject booking',
                'data' => []
            ], 500);
        }
    }
}
