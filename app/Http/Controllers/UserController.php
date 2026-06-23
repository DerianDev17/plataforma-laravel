<?php

namespace App\Http\Controllers;

use App\Models\CourseSession;
use App\Models\User;
use App\Services\Audit\AuditLogService;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function destroy(Request $request, User $user, AuditLogService $audit)
    {
        $audit->log('user.admin.deleted', $request->user(), [
            'user_id' => $user->id,
            'email' => $user->email,
            'username' => $user->username,
        ]);

        $user->delete();

        return back()->with('message', 'Usuario eliminado.');
    }

    public function enviarCorreoCuentas(Request $request, NotificationService $notifications, AuditLogService $audit)
    {
        set_time_limit(0);

        $users = User::students()
            ->where('email', 'like', '%gmail%')
            ->take(200)
            ->get();

        $counter = 0;

        foreach ($users as $f) {
            if ($notifications->sendRegistrationCredentials($f)) {
                $counter++;
            }
            usleep(250000);
        }

        $audit->log('user.registration_emails.sent', $request->user(), [
            'sent' => $counter,
            'selected' => $users->count(),
            'email_filter' => 'gmail',
        ]);

        return $counter . ' emails enviados';
    }

    public function getStudentsAjax(Request $request)
    {
        $search = $request->search ?? '';

        return User::where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                  ->orWhere('last_name', 'like', '%'.$search.'%')
                  ->orWhere('email', 'like', '%'.$search.'%');
            })
            ->paginate(30)
            ->toJson();
    }

    public function getClasesAjax(Request $request)
    {
        $search = $request->search ?? '';

        return CourseSession::where(function ($q) use ($search) {
                $q->where('subject', 'like', '%'.$search.'%')
                  ->orWhere('date', 'like', '%'.$search.'%')
                  ->orWhere('time', 'like', '%'.$search.'%');
            })
            ->paginate(30)
            ->toJson();
    }

    public function setUserExamMonth(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $user->exam_month = $request->exam_month;
        $user->save();

        return [
            'success' => 'ok',
            'message' => 'cambio de mes correcto'
        ];
    }
}
