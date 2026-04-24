<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $profiles = DB::table('profiles')
            ->whereNull('address')
            ->get(['id', 'national_id']);

        foreach ($profiles as $p) {
            $application = DB::table('applications')
                ->where('national_id', $p->national_id)
                ->where('status', 'approved')
                ->orderByDesc('id')
                ->first(['address']);
            if ($application && is_string($application->address) && $application->address !== '') {
                DB::table('profiles')->where('id', $p->id)->update(['address' => $application->address]);
            }
        }
    }

    public function down(): void
    {
        //
    }
};
