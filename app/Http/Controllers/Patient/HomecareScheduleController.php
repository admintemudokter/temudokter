<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\Request;

class HomecareScheduleController extends Controller
{
    public function slots(Request $request)
    {
        $date = $request->query('date');
        
        if (!$date) {
            return response()->json(['error' => 'Date is required'], 400);
        }

        $allSlots = ['09:00', '11:00', '13:00', '15:00', '17:00'];
        
        // Check if the entire day is blocked by admin
        $fullDayBlock = \App\Models\HomecareBlock::where('type', 'block')->where('date', $date)->whereNull('time')->exists();
        if ($fullDayBlock) {
            $slots = collect($allSlots)->map(function ($time) {
                return [
                    'time' => $time,
                    'booked' => true,
                ];
            });
            return response()->json([
                'date' => $date,
                'slots' => $slots,
            ]);
        }

        // Get slots blocked by admin for specific times
        $adminBlockedTimes = \App\Models\HomecareBlock::where('type', 'block')->where('date', $date)->whereNotNull('time')
            ->pluck('time')->map(fn($t) => substr($t, 0, 5))->toArray();
        
        $bookedSlots = Consultation::where('type', 'homecare')
            ->whereDate('homecare_schedule_date', $date)
            ->whereNotIn('consultation_status', ['rejected', 'payment_rejected'])
            ->pluck('homecare_schedule_time')
            ->map(fn($t) => substr($t, 0, 5)) // Ensure format is HH:MM
            ->toArray();

        $slots = collect($allSlots)->map(function ($time) use ($bookedSlots, $adminBlockedTimes) {
            return [
                'time' => $time,
                'booked' => in_array($time, $bookedSlots) || in_array($time, $adminBlockedTimes),
            ];
        });

        return response()->json([
            'date' => $date,
            'slots' => $slots,
        ]);
    }
}
