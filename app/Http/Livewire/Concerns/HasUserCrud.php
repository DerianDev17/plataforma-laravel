<?php

namespace App\Http\Livewire\Concerns;

use App\Exports\StudentsExport;
use App\Models\User;
use App\Services\Audit\AuditLogService;
use App\Services\LiveClass\StudentLiveClassAccessService;
use App\Services\NotificationService;
use App\Services\Students\StudentDashboardCounterService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
        $username,
        $password,
        $status,
        $payment_status,
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

    public function updatingSearchTerm()
    {
        $this->resetPage();
    }

    public function updatingSearchTerm2()
    {
        $this->resetPage();
    }

    public function baseRender($view)
    {
        $this->authorizeAbility('edit_users');

        $searchTerm = trim((string) $this->searchTerm);

        return view($view, [
            'studentsForTable' => User::students()
                ->orderBy('id', 'desc')
                ->with('student_group')
                ->when($searchTerm !== '', function ($query) use ($searchTerm): void {
                    $likeSearch = '%' . $searchTerm . '%';

                    $query->where(function ($query) use ($likeSearch): void {
                        $query->where('email', 'like', $likeSearch)
                            ->orWhere('name', 'like', $likeSearch)
                            ->orWhere('last_name', 'like', $likeSearch)
                            ->orWhere('username', 'like', $likeSearch);
                    });
                })
                ->when($this->searchTerm2, function ($query): void {
                    if ($this->searchTerm2 === 'access') {
                        $query->withLiveClassPaymentAccess();
                        return;
                    }

                    if ($this->searchTerm2 === 'blocked') {
                        $query->where('payment_status', 'overdue');
                        return;
                    }

                    $query->where('payment_status', $this->searchTerm2);
                })
                ->paginate(15),
            'paymentStatusOptions' => User::paymentStatusOptions(),
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
        $this->username = '';
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
        $this->payment_status = 'pending';
        $this->name_representante = '';
        $this->last_name_representante = '';
        $this->cellphone_representante = '';
        $this->regimen = '';
        $this->exam_month = 'Junio';
        $this->role = 'student';
    }

    public function store()
    {
        $this->authorizeAbility('edit_users');

        $email_validation = 'required|string|email:rfc|max:255|not_regex:/[\r\n]/';
        if ($this->from_create === true) {
            $email_validation .= '|unique:users,email';
        } elseif ($this->student_id) {
            $email_validation .= '|unique:users,email,' . $this->student_id;
        }

        $username_validation = 'required|string|max:255|regex:/^[A-Za-z0-9._-]+$/';
        if ($this->from_create === true) {
            $username_validation .= '|unique:users,username';
        } elseif ($this->student_id) {
            $username_validation .= '|unique:users,username,' . $this->student_id;
        }

        $this->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => $username_validation,
            'password' => $this->from_create === true ? 'required|string|min:8' : 'nullable|string|min:8',
            'cedula' => 'required|string|max:20',
            'cellphone' => 'required|string|max:30',
            'email' => $email_validation,
            'highschool' => 'required',
            'city' => 'required',
            'payment_status' => 'required|in:' . implode(',', array_keys(User::paymentStatusOptions())),
            'name_representante' => 'required',
            'last_name_representante' => 'required',
            'cellphone_representante' => 'required',
            'regimen' => 'required',
            'exam_month' => 'required',
        ]);

        $paymentStatus = User::normalizePaymentStatus($this->payment_status);
        $roleName = 'student';

        $user = User::updateOrCreate(['id' => $this->student_id], [
            'name' => $this->name,
            'last_name' => $this->last_name,
            'username' => $this->username,
            'password' => $this->passwordForStorage(),
            'cedula' => $this->cedula,
            'cellphone' => $this->cellphone,
            'email' => $this->email,
            'fixedphone' => $this->fixedphone,
            'highschool' => $this->highschool,
            'especialty' => $this->especialty,
            'paralelo' => $this->paralelo,
            'city' => $this->city,
            'status' => User::paymentStatusAllowsAccess($paymentStatus),
            'payment_status' => $paymentStatus,
            'name_representante' => $this->name_representante,
            'last_name_representante' => $this->last_name_representante,
            'cellphone_representante' => $this->cellphone_representante,
            'regimen' => $this->regimen,
            'fecha_examen' => $this->exam_month,
            'exam_month' => $this->exam_month,
        ]);

        $user->assignRole($roleName);
        $this->audit()->log($user->wasRecentlyCreated ? 'user.admin.created' : 'user.admin.updated', auth()->user(), [
            'user_id' => $user->id,
            'email' => $user->email,
            'username' => $user->username,
            'role' => $roleName,
            'payment_status' => $user->payment_status,
        ]);
        $this->clearLiveClassCounters();
        $this->refreshStudentDashboardCounters();

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
        $this->username = $student->username;
        $this->password = '';
        $this->cedula = $student->cedula;
        $this->cellphone = $student->cellphone;
        $this->email = $student->email;
        $this->fixedphone = $student->fixedphone;
        $this->highschool = $student->highschool;
        $this->especialty = $student->especialty;
        $this->paralelo = $student->paralelo;
        $this->city = $student->city;
        $this->status = $student->status;
        $this->payment_status = $student->effective_payment_status;
        $this->name_representante = $student->name_representante;
        $this->last_name_representante = $student->last_name_representante;
        $this->cellphone_representante = $student->cellphone_representante;
        $this->regimen = $student->regimen;
        $this->exam_month = $student->exam_month;
        $this->role = $student->roles()->first()?->name ?? 'student';
        $this->from_create = false;
        $this->openModal();
    }

    private function passwordForStorage()
    {
        if ($this->student_id && blank($this->password)) {
            return User::findOrFail($this->student_id)->password;
        }

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

        $student = User::findOrFail($id);
        $this->audit()->log('user.admin.deleted', auth()->user(), [
            'user_id' => $student->id,
            'email' => $student->email,
            'username' => $student->username,
        ]);

        $student->forceDelete();
        $this->clearLiveClassCounters();
        $this->refreshStudentDashboardCounters();
        session()->flash('message', 'Estudiante elminado.');
    }

    public function downloadStudents()
    {
        $this->authorizeAbility('edit_users');
        $current = Carbon::now()->format('YmdHs');
        return \Maatwebsite\Excel\Facades\Excel::download(new StudentsExport, 'estudiantes' . $current . '.xlsx');
    }

    public function updatePaymentStatus($id, $paymentStatus)
    {
        $this->authorizeAbility('edit_users');

        $paymentStatus = User::normalizePaymentStatus($paymentStatus, null);

        if (! array_key_exists($paymentStatus, User::paymentStatusOptions())) {
            $this->addError('payment_status', 'Estado de pago invalido.');
            return;
        }

        $student = User::students()->findOrFail($id);
        $previousPaymentStatus = $student->effective_payment_status;

        $student->payment_status = $paymentStatus;
        $student->status = User::paymentStatusAllowsAccess($paymentStatus);

        if (! $student->status) {
            $student->id_zoom = null;
            $student->join_url = null;
        }

        $student->save();

        $this->audit()->log('user.admin.payment_status_updated', auth()->user(), [
            'user_id' => $student->id,
            'email' => $student->email,
            'from' => $previousPaymentStatus,
            'to' => $paymentStatus,
            'access' => (bool) $student->status,
        ]);

        $this->clearLiveClassCounters();
        $this->refreshStudentDashboardCounters();

        session()->flash('message', 'Estado de pago actualizado para ' . trim($student->name . ' ' . $student->last_name) . '.');
    }

    public function resetPassword($id)
    {
        $this->authorizeAbility('edit_users');
        $student = User::findOrFail($id);
        $tempPassword = Str::random(12);

        $student->password = Hash::make($tempPassword);
        $student->must_change_password = true;
        $student->save();

        $this->audit()->log('user.admin.password_reset', auth()->user(), [
            'user_id' => $student->id,
            'email' => $student->email,
            'username' => $student->username,
        ]);

        app(NotificationService::class)->sendRegistrationCredentials($student, $tempPassword);
        session()->flash('message', 'Contrasena temporal generada y enviada al estudiante.');
    }

    public function resetFilters()
    {
        $this->authorizeAbility('edit_users');

        $this->searchTerm = '';
        $this->searchTerm2 = '';
        $this->resetPage();
    }

    private function clearLiveClassCounters(): void
    {
        app(StudentLiveClassAccessService::class)->clearCountersCache();
    }

    private function refreshStudentDashboardCounters(): void
    {
        app(StudentDashboardCounterService::class)->clearCache();

        if (method_exists($this, 'update_counters')) {
            $this->update_counters();
        }
    }

    private function audit(): AuditLogService
    {
        return app(AuditLogService::class);
    }
}
