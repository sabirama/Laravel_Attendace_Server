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

        AttendanceLog::create([
            'uid' => $uid,
        ]);

        return response()->json([
            'student' => $student,
        ]);
    }

}
