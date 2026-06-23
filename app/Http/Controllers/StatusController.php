<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Audit\AuditLogService;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function store(Request $request, AuditLogService $audit)
    {
        $affected = User::query()->update(['status' => true]);

        $audit->log('user.status.bulk_unlocked', $request->user(), [
            'affected' => $affected,
        ]);

        return back()->with('message', 'Cuentas desbloqueadas.');
    }
}
