<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class UserController extends Controller
{
    public function create()
    {
        return view('user.create');
    }
    public function index(Request $request)
    {
        $groupFilter = $request->input('group');
        $sortBy = $request->input('sortBy', 'uid');

        $validSorts = ['uid', 'name', 'contact', 'group'];
        if (!in_array($sortBy, $validSorts)) {
            $sortBy = 'uid';
        }

        $query = User::query();

        if ($groupFilter && $groupFilter !== 'all') {
            $query->where('group', $groupFilter);
        }

        $users = $query->orderBy($sortBy)->get();

        $groups = User::select('group')->distinct()->pluck('group');

        return view('user.user', compact('users', 'groups', 'groupFilter', 'sortBy'));
    }

    public function store(Request $request)
    {
        $lines = explode("\n", $request->input('bulk_input'));
        $duplicateAction = $request->input('duplicate_action', 'skip');

        $inserted = 0;
        $updated = 0;
        $skipped = [];

        foreach ($lines as $line) {
            $fields = preg_split('/\t+/', trim($line));
            if (count($fields) < 4) {
                continue;
            }

            [$uid, $name, $contact, $group] = $fields;

            $existingUser = User::where('uid', $uid)->first();

            if ($existingUser) {
                if ($duplicateAction === 'overwrite') {
                    $existingUser->update([
                        'name' => trim($name),
                        'contact' => trim($contact),
                        'group' => trim($group),
                    ]);
                    $updated++;
                } else { // skip duplicates
                    $skipped[] = $uid;
                }
            } else {
                User::create([
                    'uid' => trim($uid),
                    'name' => trim($name),
                    'contact' => trim($contact),
                    'group' => trim($group),
                ]);
                $inserted++;
            }
        }

        $message = "Inserted: $inserted. Updated: $updated. Skipped: " . count($skipped);
        if (count($skipped)) {
            $message .= " (Skipped UIDs: " . implode(', ', $skipped) . ")";
        }

        return redirect()
            ->route('user.index')
            ->with('status', $message);
    }


    // Optional: If you want a separate massStore for another form:
    public function massStore(Request $request)
    {
        $rows = explode("\n", trim($request->input('raw_data')));
        $created = 0;

        foreach ($rows as $line) {
            $columns = preg_split("/\t+/", trim($line));
            if (count($columns) >= 4) {
                [$uid, $name, $contact, $group] = $columns;

                try {
                    User::create([
                        'uid' => trim($uid),
                        'name' => trim($name),
                        'contact' => trim($contact),
                        'group' => trim($group),
                    ]);
                    $created++;
                } catch (QueryException $e) {
                    if ($e->getCode() == 23000) {
                        // skip duplicates here as well
                        continue;
                    }
                    throw $e;
                }
            }
        }

        return redirect()->route('user')->with('success', "$created users added successfully.");
    }
}
