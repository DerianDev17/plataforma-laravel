<?php

namespace App\Http\Controllers;

use App\Models\CourseSession;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function destroy(User $user)
    {
        $user->delete();

        return back()->with('message', 'Usuario eliminado.');
    }

    public function enviarCorreoCuentas()
    {
        set_time_limit(0);

        $users = User::whereHas('roles', function ($q) {
                $q->where('name', 'student');
            })
            ->where('email', 'like', '%gmail%')
            ->take(200)
            ->get();

        $counter = 0;

        foreach ($users as $f) {
            if (filter_var($f->email, FILTER_VALIDATE_EMAIL)) {
                \Mail::to($f->email)->send(new \App\Mail\RegistrationMail([
                    'title' => 'Creacion de cuenta Semilla Digital',
                    'body' => 'Saludos desde ',
                    'user' => $f,
                ]));
                $counter++;
            }
            usleep(250000);
        }

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
