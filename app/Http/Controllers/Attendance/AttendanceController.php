<?php

namespace App\Http\Controllers\Attendance;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        // Get the selected date or default to today
        $date = $request->input('date', date('Y-m-d'));

        // Get attendance logs for that date
        $attendanceLogs = DB::table('attendance_logs')
            ->whereDate('created_at', $date)
            ->orderBy('created_at', 'asc')
            ->get();

        // Get user IDs from logs
        $uids = $attendanceLogs->pluck('uid')->unique();

        // Fetch users data for those uids
        $users = User::whereIn('uid', $uids)->get()->keyBy('uid');

        return view('attendance.index', compact('attendanceLogs', 'users', 'date'));
    }
}
