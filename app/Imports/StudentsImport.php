<?php

namespace App\Imports;

use App\Models\User;
use App\Services\Audit\AuditLogService;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class StudentsImport implements ToCollection, WithStartRow
{
    private $rows = 0;
    private $failed_updates = [];
    private int $updated = 0;

    public function __construct(private ?User $actor = null) {}

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            ++$this->rows;

            $user = User::where('email', '=', $row[7])
                ->first();

            // revisar si el usuario existe
            if ($user !== null) {
                $paymentStatus = User::normalizePaymentStatus($row[15] ?? null, 'paid');

                $user->status = User::paymentStatusAllowsAccess($paymentStatus);
                $user->payment_status = $paymentStatus;
                $user->payment_day = $row[14];
                $user->fecha_examen = trim($row[13]);
                $user->exam_month = trim($row[13]);

                if (! $user->status) {
                    $user->id_zoom = null;
                    $user->join_url = null;
                }

                $user->save();
                $this->updated++;
            } else {
                array_push($this->failed_updates, $row[7] . ' - ' . $row[15]);
            }
        }

        app(AuditLogService::class)->log('student.import.batch', $this->actor, [
            'created' => 0,
            'updated' => $this->updated,
            'deleted' => 0,
            'failed_emails' => count($this->failed_updates),
            'delete_missing' => false,
            'mode' => 'update_existing',
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }

    public function getFailedUpdates(): array
    {
        return $this->failed_updates;
    }
}
