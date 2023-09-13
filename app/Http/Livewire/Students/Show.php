<?php

namespace App\Http\Livewire\Students;

use App\Exports\StudentsExport;
use App\Http\Controllers\Zoom\MeetingController;
use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use App\Traits\ZoomJWT;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Show extends Component
{
    use ZoomJwT;

    use WithPagination;

    public $students,
        $name,
        $last_name,
        $cedula,
        $cellphone,
        $fixedphone,
        $highschool,
        $especialty,
        $paralelo,
        $city,
        $student_id,
        $email,
        $password,
        $status,
        $name_representante,
        $last_name_representante,
        $cellphone_representante,
        $regimen,
        // $fecha_examen,
        $exam_month,
        $role;
    public $isOpen = 0;
    public $roles = [];
    public $from_create = null;

    public $searchTerm;
    public $searchTerm2;

    public $registrants_n;
    public $canceled_users_n;

    public $isloading = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $searchTerm = '%' . $this->searchTerm . '%';
        $searchTerm2 = '%' . $this->searchTerm2 . '%';

        return view('livewire.students.show', [
            'studentsForTable' => User::orderBy('id', 'desc')
                ->where('email', 'like', $searchTerm)
                ->where('status', 'like', $searchTerm2)
                ->paginate(15)
        ]);
    }

    public function create()
    {
        $this->from_create = true;

        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->last_name = '';
        $this->password = '';
        $this->cedula = '';
        $this->cellphone = '';
        $this->email = '';
        $this->fixedphone = '';
        $this->highschool = '';
        $this->especialty = '';
        $this->paralelo = '';
        $this->city = '';
        $this->student_id = '';
        $this->status = '';
        $this->name_representante = '';
        $this->last_name_representante = '';
        $this->cellphone_representante = '';
        $this->regimen = '';
        // $this->fecha_examen = '';
        // $this->exam_month = '';
    }

    public function store()
    {
        $email_validation = "";
        if ($this->from_create === true) {
            $email_validation = 'required|unique:users,email';
        }
        // PENDIENTE: no hacer obligatorio los campos
        $this->validate([
            'name' => 'required',
            'last_name' => 'required',
            'password' => 'required',
            'cedula' => 'required',
            'cellphone' => 'required',
            'email' => $email_validation,
            'highschool' => 'required',
            'city' => 'required',
            'status' => 'required',
            'name_representante' => 'required',
            'last_name_representante' => 'required',
            'cellphone_representante' => 'required',
            'regimen' => 'required',
            // 'fecha_examen' => 'required',
            'exam_month' => 'required',
        ]);

        // PENDIENTE: pasar strings vacìos para que no de error en la base de datos

        $user = User::updateOrCreate(['id' => $this->student_id], [

            'name' => $this->name,
            'last_name' => $this->last_name,
            'password' => $this->password,
            'cedula' => $this->cedula,
            'cellphone' => $this->cellphone,
            'email' => $this->email,
            'fixedphone' => $this->fixedphone,
            'highschool' => $this->highschool,
            'especialty' => $this->especialty,
            'paralelo' => $this->paralelo,
            'city' => $this->city,
            'status' => $this->status,
            'name_representante' => $this->name_representante,
            'last_name_representante' => $this->last_name_representante,
            'cellphone_representante' => $this->cellphone_representante,
            'regimen' => $this->regimen,
            'exam_month' => $this->exam_month,
        ]);
        // var_dump($this->exam_month);


        $user->assignRole($this->role);

        // PENDIENTE: asignarle al usuario, el rol seleccionado
        // mas o menos debe ser
        // $user->assignRole($this->role)

        session()->flash(
            'message',
            'Estudiante Actualizado Correctamente.'
        );

        $this->closeModal();
        $this->resetInputFields();
    }

    //primera funcian llamada
    public function mount()
    {
        // $this->roles = Role::all();
        $this->update_counters();
    }

    function update_counters()
    {
        $this->canceled_users_n = User::join('role_user', 'users.id', '=', 'role_user.user_id')
            ->where('status', 1)
            ->where('role_id', '=', 2)
            ->count();
        $this->registrants_n = User::join('role_user', 'users.id', '=', 'role_user.user_id')
            ->whereNotNull('join_url')
            ->where('role_id', '=', 2)
            ->where('status', '=', 1)
            ->count();
    }

    public function edit($id)
    {
        $student = User::findOrFail($id);
        $this->student_id = $id;
        $this->name = $student->name;
        $this->last_name = $student->last_name;
        $this->password = $student->password;
        $this->cedula = $student->cedula;
        $this->cellphone = $student->cellphone;
        $this->email = $student->email;
        $this->fixedphone = $student->fixedphone;
        $this->highschool = $student->highschool;
        $this->especialty = $student->especialty;
        $this->paralelo = $student->paralelo;
        $this->city = $student->city;
        $this->status = $student->status;
        $this->name_representante = $student->name_representante;
        $this->last_name_representante = $student->last_name_representante;
        $this->cellphone_representante = $student->cellphone_representante;
        $this->regimen = $student->regimen;
        // $this->fecha_examen = $student->fecha_examen;
        $this->exam_month = $student->exam_month;
        $this->openModal();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function delete($id)
    {
        User::find($id)->forcedelete();
        session()->flash('message', 'Estudiante elminado.');
    }

    public function downloadStudents()
    {
        $current = Carbon::now()->format('YmdHs');
        return Excel::download(new StudentsExport, 'estudiantes' . $current . '.xlsx');
    }

    public function resetPassword($id)
    {
        $student = User::findOrFail($id);
        $student->password = Hash::make($student->username);
        $student->save();

        session()->flash(
            'message',
            'Contraseña restablecida.'
        );
    }

    public function registerStudent($id)
    {
        $student = User::findOrFail($id);
        $meeting_id = $student->student_group->webinar_id;

        $path = 'webinars/' . $meeting_id . '/registrants';
        // $response = $this->zoomGet($path);

        $response = $this->zoomPost($path, [
            'email' => $student->email,
            'first_name' => $student->name,
            'last_name' => $student->last_name,
        ]);
        // dd($response->body());

        session()->flash(
            'message',
            'response'
        );
    }

    function registerToWebinar()
    {
        $this->isloading = true;
        $to_register = User::join('role_user', 'users.id', '=', 'role_user.user_id')
            ->whereNull('join_url')
            ->where('role_id', '=', 2)
            ->where('status', '=', 1)
            // ignorar paralelo C
            ->where('student_group_id', '!=', 3)
            ->get();
        //dd('1' . $to_register);
        //Datos de prueba
        // $array1 = [
        //     User::find(1),
        //     User::find(4993),
        //     User::find(4994),
        //     // User::find(4995),
        //     // User::find(4996),
        //     // User::find(4997),
        //     // User::find(4998),
        // ];
        $failed_responses = [];

        $meet_ctrl = new MeetingController();

        $to_approve = [];

        foreach ($to_register as $student) {
            //dd('2' . $student);
            $resp = $meet_ctrl->registerToWebinarZoom($student);

            //dd('3' . $resp->body());
            if ($resp->failed()) {
                if (isset($student->email)) {
                    $responses[$student->email] = $resp;
                }
            } else {
                $to_approve = [
                    'id' => $resp->json()['registrant_id'],
                    'email' => $student->email,
                ];

                $resp2 = $meet_ctrl->approveStudents($student->student_group->webinar_id, [$to_approve]);

                //dd('5: ' . $resp2);
                if ($resp2->failed()) {
                    //echo ('6 ' . $resp2);
                    if (isset($student->email)) {
                        $responses[$student->email] = $resp;
                    }
                }
            }
        }
        $this->isloading = false;
        session()->flash(
            'message',
            'Completado'
        );
        //dd($failed_responses);
    }

    function update_zoom_links()
    {
        $meet_ctrl = new MeetingController();
        //dd('probando update_zoom_links ');
        $meet_ctrl->set_zoom_ids();
        // TEMP quitar enlaces a paralelo c
        User::students()->where('student_group_id', 3)->update(['join_url' => null]);
        $this->update_counters();
    }
}
