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
    $date = $request->input('date', date('Y-m-d'));
    $group = $request->input('group'); // get selected group

    // Get logs for the selected date
    $attendanceLogs = DB::table('attendance_logs')
        ->whereDate('created_at', $date)
        ->orderBy('created_at', 'asc')
        ->get();

    // Get unique UIDs from logs
    $uids = $attendanceLogs->pluck('uid')->unique();

    // Fetch users keyed by UID
    $users = DB::table('users')
        ->whereIn('uid', $uids)
        ->get()
        ->keyBy('uid');

    // If group is selected, filter both users and logs by group
    if ($group) {
        // Only keep users matching selected group
        $users = $users->filter(fn($user) => $user->group === $group);

        // Filter logs to only those UIDs in the filtered users
        $attendanceLogs = $attendanceLogs->filter(fn($log) => $users->has($log->uid));
    }

    return view('attendance.index', [
        'attendanceLogs' => $attendanceLogs,
        'users' => $users,
        'date' => $date,
        'group' => $group,
    ]);
}


    public function export(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));

        $attendanceLogs = AttendanceLog::whereDate('created_at', $date)
            ->orderBy('uid')
            ->orderBy('created_at')
            ->get();

        $users = User::whereIn('id', $attendanceLogs->pluck('uid')->unique())->get()->keyBy('id');

        $fileName = 'attendance_' . $date . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function () use ($attendanceLogs, $users) {
            $file = fopen('php://output', 'w');

            // CSV headers
            fputcsv($file, ['UID', 'Name', 'Contact', 'Group', 'Status', 'Time']);

            // Group logs by UID
            $groupedLogs = $attendanceLogs->groupBy('uid');

            foreach ($groupedLogs as $uid => $logs) {
                $user = $users->get($uid);
                $firstLog = $logs->sortBy('created_at')->first();
                $lastLog = $logs->sortByDesc('created_at')->first();

                // First log (IN)
                fputcsv($file, [
                    $uid,
                    $user->name ?? 'Unknown User',
                    $user->contact ?? '-',
                    ucfirst($user->group ?? '-'),
                    'IN',
                    Carbon::parse($firstLog->created_at)->format('H:i:s')
                ]);

                // Last log (OUT) if different from first
                if ($firstLog->id != $lastLog->id) {
                    fputcsv($file, [
                        $uid,
                        $user->name ?? 'Unknown User',
                        $user->contact ?? '-',
                        ucfirst($user->group ?? '-'),
                        'OUT',
                        Carbon::parse($lastLog->created_at)->format('H:i:s')
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
