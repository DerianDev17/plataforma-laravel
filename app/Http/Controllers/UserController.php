<?php

namespace App\Http\Controllers;

use App\Models\CourseSession;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        dd($user);
    }

    public function enviarCorreoCuentas()
    {
        set_time_limit(0);

        $users = User::all();

        //filtrar usuarios rol estudiante
        $filtered = $users->filter(function ($user, $key) {
            return $user->hasRole('student');
        });

        //obtener con cuentas gmail
        $filtered = $users->filter(function ($user, $key) {
            $email = $user->email;
            $findme   = 'gmail';
            $pos = strpos($email, $findme);
            return $pos;
        });
        $filtered = $filtered->slice(0, 200);
        // dd($filtered);
        // dd($filtered->count());
        //enviar los correos

        $counter = 1;


        foreach ($filtered as $f) {

            $details = [
                'title' => 'Creación de cuenta Eus3',
                'body' => 'Saludos desde ',
                'user' => $f,
            ];
            // dd($f);
            if (filter_var($f->email, FILTER_VALIDATE_EMAIL)) {
                Mail::to($f->email)->send(new \App\Mail\RegistrationMail($details));
                $counter++;
            }
            usleep(250000);
        }

        return $counter . ' emails enviados';
    }

    public function getStudentsAjax(Request $request)
    {
        $request->all();
        $search = $request->search ?? '';
        // dd($request->search);
        $students = User::where('name', 'like', '%'.$search.'%')
            ->orWhere('last_name', 'like', '%'.$search.'%')
            ->orWhere('email', 'like', '%'.$search.'%')
            ->paginate(30);

        // dd($students);
        return $students->toJson();
    }

    public function getClasesAjax(Request $request)
    {
        $request->all();
        $search = $request->search ?? '';
        $clases = CourseSession::where('subject', 'like', '%'.$search.'%')
            ->orWhere('date', 'like', '%'.$search.'%')
            ->orWhere('time', 'like', '%'.$search.'%')
            ->paginate(30);
        return $clases->toJson();
    }

    public function setUserExamMonth(Request $request)
    {
        $request->all();
        // dd(auth()->user());
        $queried_user = User::find(auth()->user()->id);
        $queried_user->exam_month = $request->exam_month;
        $queried_user->save();

        return [
            'success' => 'ok',
            'message' => 'cambio de mes correcto'
        ];
        // dd($queried_user);
    }
}
