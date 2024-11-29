<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{
    // Получение списка бронирований
    public function index()
    {
        $bookings = Booking::with('hall', 'user')->get();
        return response()->json($bookings);
    }

    // Создание бронирования
    public function store(Request $request)
    {
        $request->validate([
            'hall_id' => 'required|exists:halls,id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'reason' => 'required|string|max:255'
        ]);

        // Проверка занятости зала
        $conflict = Booking::where('hall_id', $request->hall_id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time]);
            })
            ->exists();

        if ($conflict) {
            return response()->json(['message' => 'Hall is already booked for the selected time'], 409);
        }

        $booking = Booking::create([
            'hall_id' => $request->hall_id,
            'user_id' => auth()->id(),
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'reason' => $request->reason
        ]);

        return response()->json(['message' => 'Booking created', 'booking' => $booking], 201);
    }

    // Удаление бронирования
    // public function destroy($id)
    // {
    //     $booking = Booking::findOrFail($id);

    //     if (auth()->user()->id !== $booking->user_id && !auth()->user()->is_admin) {
    //         return response()->json([
    //             'message' => 'You do not have permission to delete this booking.'
    //         ], 403);
    //     }

    //     $booking->delete();

    //     return response()->json([
    //         'message' => 'Booking deleted successfully.'
    //     ], 200);
    // }


    public function destroy($id)
{
    $booking = Booking::findOrFail($id);

    $booking->delete();

    return response()->json([
        'message' => 'Booking deleted successfully.'
    ], 200);
}


}
