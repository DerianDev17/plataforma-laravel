<?php

namespace App\Imports;

use App\Models\User;
use App\Services\StudentImport\StudentImportResult;
use App\Services\StudentImport\StudentImportService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;

class StudentsImportar implements ToCollection, WithStartRow, WithChunkReading
{
    private ?StudentImportResult $lastResult = null;
    private array $failed_emails = [];
    private StudentImportService $service;
    private $delete;

    public function __construct($delete, ?StudentImportService $service = null)
    {
        $this->delete = $delete;
        $this->service = $service ?? app(StudentImportService::class);
    }

    public function collection(Collection $estudiantes)
    {
        set_time_limit(0);

        $this->lastResult = $this->service->import($estudiantes, [
            'delete_missing' => (bool) $this->delete,
        ]);

        $this->failed_emails = $this->lastResult->failedEmails();
    }

    public function borrarEstudiantes($estudiantes)
    {
        $this->service->deleteStudentsNotIn($estudiantes);
    }

    public function getRowCount(): int
    {
        return $this->lastResult?->created() ?? 0;
    }

    public function getFailedUpdates(): array
    {
        return $this->lastResult?->errors() ?? [];
    }

    public function getFailedEmails(): array
    {
        return $this->failed_emails;
    }

    public function startRow(): int
    {
        return 2;
    }

    public function chunkSize(): int
    {
        return 800;
    }

    public function actualizarUserData($row, $user)
    {
        $this->service->updateUser($row, $user);
    }

    public function existeUsuarioBase($excel_row, &$db_students)
    {
        return $this->service->findExistingStudent($excel_row, $db_students);
    }

    public function getStudentGroupbyCode($code)
    {
        return $this->service->studentGroupByCode($code);
    }

    public function crearUsuario($row)
    {
        $this->service->createUser($row);
    }

    public function enviarCorreoUsuario($user, $tempPassword = null)
    {
        $this->service->sendRegistrationEmail($user, $tempPassword);
    }

    public function asignarRol($id_user, $rol)
    {
        $this->service->assignRole($id_user, $rol);
    }

    public function createUsername($first_name, $last_name)
    {
        return $this->service->generateUsername($first_name, $last_name);
    }

    public function usernameExists($username)
    {
        return User::where('username', $username)->first();
    }
}
