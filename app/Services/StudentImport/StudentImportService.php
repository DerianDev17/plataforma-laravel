<?php

namespace App\Services\StudentImport;

use App\Models\StudentGroup;
use App\Models\User;
use App\Services\Audit\AuditLogService;
use App\Services\NotificationService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentImportService
{
    public const STUDENT_ROLE_ID = 2;

    private Collection $studentGroups;

    public function __construct(
        private AuditLogService $audit,
        private NotificationService $notifications,
    ) {
        $this->studentGroups = StudentGroup::all();
    }

    private array $tempPasswords = [];

    public function import(iterable $rows, array $options = []): StudentImportResult
    {
        $this->tempPasswords = [];

        $rows = $rows instanceof Collection ? $rows : collect($rows);

        $deleteMissing = (bool) ($options['delete_missing'] ?? false);
        $sendEmails = (bool) ($options['send_emails'] ?? true);
        $actor = $options['actor'] ?? null;

        $created = 0;
        $updated = 0;
        $deleted = 0;
        $failedEmails = $this->collectInvalidEmails($rows);
        $errors = [];

        $existingStudents = User::students()
            ->select(['id', 'email', 'status', 'payment_status', 'exam_month', 'diapago', 'enviarCorreo', 'student_group_id', 'id_zoom', 'join_url'])
            ->get()
            ->keyBy(fn ($u) => trim($u->email))
            ->all();

        if ($deleteMissing) {
            $deleted = $this->deleteStudentsNotIn($rows);
        }

        foreach ($rows as $row) {
            try {
                $existing = $this->findExistingStudent($row, $existingStudents);

                if ($existing) {
                    $this->updateUser($row, $existing);
                    $updated++;

                    continue;
                }

                $tempPassword = $this->createUser($row);

                if ($sendEmails) {
                    $user = User::where('email', trim($row[7]))->first();
                    if ($user) {
                        $this->sendRegistrationEmail($user, $tempPassword);
                    }
                }

                $created++;
            } catch (\Throwable $th) {
                report($th);
                $errors[] = $th->getMessage();
            }
        }

        $result = StudentImportResult::make($created, $updated, $deleted, $failedEmails, $errors);

        $this->audit->log('student.import.batch', $actor, [
            'created' => $created,
            'updated' => $updated,
            'deleted' => $deleted,
            'failed_emails' => count($failedEmails),
            'delete_missing' => $deleteMissing,
        ]);

        return $result;
    }

    public function deleteStudentsNotIn(iterable $importRows): int
    {
        $toDelete = User::students()
            ->select(['id', 'email'])
            ->with('roles:id,name')
            ->get();
        $importEmails = collect($importRows)
            ->map(fn ($row) => trim($row[7] ?? ''))
            ->filter()
            ->all();

        $remaining = $toDelete->reject(function (User $user) use ($importEmails) {
            return in_array(trim($user->email), $importEmails, true);
        })->reject(fn (User $user) => $user->hasRole('superadmin'));

        $count = 0;
        foreach ($remaining as $user) {
            $user->delete();
            $count++;
        }

        return $count;
    }

    public function collectInvalidEmails(iterable $rows): array
    {
        $invalid = [];
        foreach ($rows as $row) {
            $email = $row[7] ?? null;
            if ($email !== null && ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $invalid[] = $email;
            }
        }

        return $invalid;
    }

    public function studentGroupByCode(string $code)
    {
        return $this->studentGroups->firstWhere('code', $code);
    }

    public function findExistingStudent(array $row, array $dbStudents): ?User
    {
        $email = trim($row[7] ?? '');

        return $dbStudents[$email] ?? null;
    }

    public function updateUser(array $row, User $user): void
    {
        $paralelo = trim($row[13]);
        $paymentStatus = User::normalizePaymentStatus($row[15] ?? null, 'paid');

        $user->status = User::paymentStatusAllowsAccess($paymentStatus);
        $user->payment_status = $paymentStatus;
        $user->exam_month = $row[13];
        $user->diapago = $row[16] ?? null;
        $user->enviarCorreo = $row[17] ?? null;

        $group = $this->studentGroupByCode($paralelo);
        if ($group) {
            $user->student_group_id = $group->id;
        }

        if (! $user->status) {
            $user->id_zoom = null;
            $user->join_url = null;
        }

        $user->save();
    }

    public function createUser(array $row): string
    {
        $paralelo = trim($row[13]);

        $trashed = User::withTrashed()->where('email', trim($row[7]))->first();
        if ($trashed) {
            $trashed->forceDelete();
        }

        $user = new User();
        $user->name = $row[1];
        $user->last_name = $row[2];
        $user->cellphone = $row[3] ?? 'no data';
        $user->name_representante = $row[4] ?? 'no data';
        $user->cellphone_representante = $row[5] ?? 'no data';
        $user->fixedphone = $row[6] ?? 'no data';
        $user->email = trim($row[7]);
        $user->regimen = $row[8] ?? 'no data';
        $user->city = $row[9] ?? 'no data';
        $user->highschool = $row[10] ?? 'no data';
        $user->especialty = $row[11] ?? 'no data';
        $user->paralelo = $row[12] ?? 'no data';
        $user->fecha_examen = $row[13];
        $user->exam_month = $row[13];
        $user->payment_day = $row[14] ?? null;

        $paymentStatus = User::normalizePaymentStatus($row[15] ?? null, 'paid');
        $user->status = User::paymentStatusAllowsAccess($paymentStatus);
        $user->payment_status = $paymentStatus;
        $user->diapago = $row[16] ?? null;
        $user->enviarCorreo = $row[17] ?? null;

        $user->remember_token = Str::random(10);

        $group = $this->studentGroupByCode($paralelo);
        if ($group) {
            $user->student_group_id = $group->id;
        }

        $tempPassword = Str::random(12);
        $user->username = $this->generateUsername($row[1], $row[2]);
        $user->password = Hash::make($tempPassword);
        $user->must_change_password = true;

        $user->last_name_representante = '-1';
        $user->cedula = '-1';

        $user->save();

        $this->assignRole($user->id, self::STUDENT_ROLE_ID);

        return $tempPassword;
    }

    public function sendRegistrationEmail(User $user, ?string $tempPassword = null): void
    {
        $this->notifications->sendRegistrationCredentials($user, $tempPassword);

    }

    public function assignRole(int $userId, int $roleId): void
    {
        DB::table('role_user')->insert([
            'user_id' => $userId,
            'role_id' => $roleId,
        ]);
    }

    public function generateUsername(string $firstName, string $lastName): string
    {
        $cleanFirst = $this->stripAccents(mb_strtolower($firstName));
        $cleanLast = $this->stripAccents(mb_strtolower($lastName));

        $firstInitial = explode(' ', $cleanFirst)[0][0] ?? 'x';
        $lastParts = explode(' ', $cleanLast);
        $primaryLast = $lastParts[0] ?? 'user';
        $secondaryInitial = $lastParts[1][0] ?? $primaryLast[0];

        $base = $firstInitial . $primaryLast . $secondaryInitial . 'EUS';

        if (! User::where('username', $base)->exists()) {
            return $base;
        }

        for ($i = 0; $i < 10; $i++) {
            $candidate = $base . random_int(1, 9);
            if (! User::where('username', $candidate)->exists()) {
                return $candidate;
            }
        }

        return $base . '_' . Str::random(4);
    }

    public function stripAccents(string $value): string
    {
        $accents = [
            'Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª',
            'É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê',
            'Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î',
            'Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô',
            'Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û',
            'Ñ', 'ñ', 'Ç', 'ç',
        ];
        $plain = [
            'A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a',
            'E', 'E', 'E', 'E', 'e', 'e', 'e', 'e',
            'I', 'I', 'I', 'I', 'i', 'i', 'i', 'i',
            'O', 'O', 'O', 'O', 'o', 'o', 'o', 'o',
            'U', 'U', 'U', 'U', 'u', 'u', 'u', 'u',
            'N', 'n', 'C', 'c',
        ];

        return str_replace($accents, $plain, $value);
    }
}
