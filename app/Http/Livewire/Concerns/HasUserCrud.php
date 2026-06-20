<?php

namespace App\Http\Livewire\Concerns;

use App\Exports\StudentsExport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

trait HasUserCrud
{
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
        $exam_month,
        $role;
    public $isOpen = 0;
    public $roles = [];
    public $from_create = null;
    public $searchTerm;
    public $searchTerm2;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function baseRender($view)
    {
        $this->authorizeAbility('edit_users');

        $searchTerm = '%' . $this->searchTerm . '%';
        $searchTerm2 = '%' . $this->searchTerm2 . '%';

        return view($view, [
            'studentsForTable' => User::orderBy('id', 'desc')
                ->with('roles')
                ->where('email', 'like', $searchTerm)
                ->where('status', 'like', $searchTerm2)
                ->paginate(15)
        ]);
    }

    public function create()
    {
        $this->authorizeAbility('edit_users');
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
    }

    public function store()
    {
        $this->authorizeAbility('edit_users');

        $email_validation = 'required|email:rfc|not_regex:/[\r\n]/';
        if ($this->from_create === true) {
            $email_validation .= '|unique:users,email';
        } elseif ($this->student_id) {
            $email_validation .= '|unique:users,email,' . $this->student_id;
        }

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
            'exam_month' => 'required',
        ]);

        $user = User::updateOrCreate(['id' => $this->student_id], [
            'name' => $this->name,
            'last_name' => $this->last_name,
            'password' => $this->passwordForStorage(),
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

        $user->assignRole($this->role);

        session()->flash('message', 'Estudiante Actualizado Correctamente.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $this->authorizeAbility('edit_users');

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
        $this->exam_month = $student->exam_month;
        $this->openModal();
    }

    private function passwordForStorage()
    {
        if ($this->student_id) {
            $student = User::find($this->student_id);
            if ($student && hash_equals($student->password, $this->password)) {
                return $student->password;
            }
        }
        return Hash::make($this->password);
    }

    public function delete($id)
    {
        $this->authorizeAbility('edit_users');
        User::findOrFail($id)->forceDelete();
        session()->flash('message', 'Estudiante elminado.');
    }

    public function downloadStudents()
    {
        $this->authorizeAbility('edit_users');
        $current = Carbon::now()->format('YmdHs');
        return \Maatwebsite\Excel\Facades\Excel::download(new StudentsExport, 'estudiantes' . $current . '.xlsx');
    }

    public function resetPassword($id)
    {
        $this->authorizeAbility('edit_users');
        $student = User::findOrFail($id);
        $student->password = Hash::make($student->username);
        $student->save();
        session()->flash('message', 'Contraseña restablecida.');
    }
}
