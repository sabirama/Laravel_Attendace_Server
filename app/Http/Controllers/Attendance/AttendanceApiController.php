<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceApiController extends Controller
{
    public function getByUid(Request $request, $uid)
    {
        $apiName = $request->input('api_name');
        $apiKey = $request->input('api_key');

        // ✅ Check that API credentials are provided
        if (empty($apiName) || empty($apiKey)) {
            return response()->json(['error' => 'Missing API credentials'], 400);
        }

        // ✅ Validate API credentials
        $isValid = DB::table('api_tokens')
            ->where([
                'name' => $apiName,
                'token' => $apiKey
            ])
            ->exists();

        if (!$isValid) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // ✅ Retrieve user by UID
        $student = DB::table('users')
            ->where('uid', $uid)
            ->select('uid', 'name', 'contact')
            ->first();

        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        $today = now()->format('Y-m-d');

        // ✅ Fetch today's logs for this UID
        $logsToday = AttendanceLog::where('uid', $uid)
            ->whereDate('created_at', $today)
            ->orderBy('created_at', 'asc')
            ->get();

        $logCount = $logsToday->count();

        if ($logCount >= 2) {
            // ✅ Update the latest (2nd) log instead of adding a new one
            $lastLog = $logsToday->last();
            $lastLog->update(['created_at' => now()]);
        } else {
            // ✅ Create new log
            AttendanceLog::create([
                'uid' => $uid,
                'created_at' => now()
            ]);
        }

        return response()->json([
            'student' => $student,
            'log_count' => $logCount >= 2 ? 2 : $logCount + 1,
            'message' => $logCount >= 2 ? 'Last log updated' : 'New log created'
        ]);
    }

}
