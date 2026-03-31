<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Debug: Check request data
       // dd($request->all());

        $request->validate([
            'user_id' => 'required|exists:users,id', // Ensures user exists
        ]);

        // Check if an attendance record exists for the user on the given date
        $attendance = Attendance::where('user_id', $request->user_id)
            ->where('dated', now()->format('Y-m-d'))
            ->first();

        if ($attendance) {
            // Update out_time if record exists
            $attendance->update([
                'out_time' => now()->format('H:i:s'),
                'updated_at' => now(),
                'status' => 1
            ]);
        } else {
          //  dd($request->all());
            // Create a new record
            $attendance = Attendance::create([
                'user_id' => $request->user_id,
                'dated' => now()->format('Y-m-d'),
                'in_time' => now()->format('H:i:s'),
                'out_time' => null,
                'status' => 1
            ]);
        }

        return response()->json($attendance, 201);
    }

    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         $attendance = Attendance::where('user_id', $id)
            ->latest('dated')
            ->first();

        return response()->json([
            'success' => true,
            'message' => 'Attendance records retrieved successfully.',
            'data' => $attendance
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendence)
    {
        $attendence->update([
            'updated_at' => $request->input('updated_at'),
            'out_time' => $request->input('out_time'),
            
        ]);

        return response()->json($attendence);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
