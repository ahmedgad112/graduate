<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class AdminActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = Activity::query()
            ->with(['causer', 'subject'])
            ->latest();

        $log = $request->string('log')->toString();
        if ($log !== '') {
            $query->where('log_name', $log);
        }

        $activities = $query->paginate(25)->withQueryString();

        $logNames = Activity::query()
            ->select('log_name')
            ->whereNotNull('log_name')
            ->distinct()
            ->orderBy('log_name')
            ->pluck('log_name');

        return view('admin.activity.index', [
            'activities' => $activities,
            'logNames' => $logNames,
            'activeLog' => $log,
        ]);
    }
}
