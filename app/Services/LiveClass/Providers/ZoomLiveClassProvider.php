<?php

namespace App\Services\LiveClass\Providers;

use App\Models\StudentGroup;
use App\Models\User;
use App\Services\LiveClass\Contracts\LiveClassProvider;
use App\Services\LiveClass\LiveClassOperationResult;
use App\Services\LiveClass\LiveClassSyncResult;
use App\Services\Zoom\ZoomOAuthClient;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

class ZoomLiveClassProvider implements LiveClassProvider
{
    public function __construct(private ZoomOAuthClient $zoom) {}

    public function label(): string
    {
        return 'Zoom';
    }

    public function registerStudent(User $student): LiveClassOperationResult
    {
        $student->loadMissing('student_group');

        if (! $student->student_group || ! $student->student_group->webinar_id) {
            return LiveClassOperationResult::failure('El estudiante no tiene un paralelo con webinar configurado.');
        }

        $webinarId = $student->student_group->webinar_id;

        try {
            $response = $this->zoom->post('webinars/' . $webinarId . '/registrants', [
                'email' => $student->email,
                'first_name' => $student->name,
                'last_name' => $student->last_name,
            ]);

            if ($response->failed()) {
                return LiveClassOperationResult::failure($this->responseMessage($response), $response->json() ?: []);
            }

            $payload = $response->json() ?: [];
            $registrantId = $payload['registrant_id'] ?? ($payload['id'] ?? null);
            $accessUrl = $payload['join_url'] ?? null;

            if ($registrantId) {
                $approval = $this->approveRegistrant($webinarId, $registrantId, $student->email);

                if ($approval->failed()) {
                    return LiveClassOperationResult::failure(
                        $this->responseMessage($approval),
                        $approval->json() ?: []
                    );
                }
            }

            return LiveClassOperationResult::success(
                'Estudiante registrado en Zoom.',
                $registrantId ? (string) $registrantId : null,
                $accessUrl,
                $payload
            );
        } catch (\Throwable $th) {
            report($th);

            return LiveClassOperationResult::failure('No se pudo registrar el estudiante en Zoom.');
        }
    }

    public function syncAccessLinks(): LiveClassSyncResult
    {
        $updated = 0;
        $errors = [];

        User::students()->update([
            'id_zoom' => null,
            'join_url' => null,
        ]);

        $groups = StudentGroup::valids()
            ->whereNotNull('webinar_id')
            ->where('webinar_id', '!=', '')
            ->get();

        foreach ($groups as $group) {
            try {
                $updated += $this->syncGroupAccessLinks($group);
            } catch (\Throwable $th) {
                report($th);
                $errors[] = 'No se pudo sincronizar ' . ($group->name ?: 'paralelo ' . $group->id) . '.';
            }
        }

        return LiveClassSyncResult::completed(
            $updated,
            'Sincronizacion de accesos Zoom completada.',
            $errors
        );
    }

    private function approveRegistrant(string $webinarId, string $registrantId, string $email): Response
    {
        $path = 'webinars/' . $webinarId . '/registrants/status';
        $occurrenceId = $this->currentOccurrenceId($webinarId);

        if ($occurrenceId) {
            $path .= '?occurrence_id=' . urlencode($occurrenceId);
        }

        return $this->zoom->put($path, [
            'action' => 'approve',
            'registrants' => [[
                'id' => $registrantId,
                'email' => $email,
            ]],
        ]);
    }

    private function currentOccurrenceId(string $webinarId): ?string
    {
        try {
            $response = $this->zoom->get('webinars/' . $webinarId, ['show_previous_occurrences' => false]);

            if ($response->failed()) {
                return null;
            }

            $payload = $response->json() ?: [];

            if (! empty($payload['occurrences'][0]['occurrence_id'])) {
                return (string) $payload['occurrences'][0]['occurrence_id'];
            }
        } catch (\Throwable $th) {
            Log::warning('No se pudo obtener occurrence_id de Zoom.', [
                'webinar_id' => $webinarId,
                'message' => $th->getMessage(),
            ]);
        }

        return null;
    }

    private function syncGroupAccessLinks(StudentGroup $group): int
    {
        $registrants = $this->webinarRegistrants($group->webinar_id);

        if (empty($registrants)) {
            return 0;
        }

        $emails = array_filter(array_column($registrants, 'email'));

        if (empty($emails)) {
            return 0;
        }

        $users = User::students()
            ->whereIn('email', $emails)
            ->get()
            ->keyBy('email');

        $updated = 0;

        foreach ($registrants as $registrant) {
            if (empty($registrant['email'])) {
                continue;
            }

            $student = $users->get($registrant['email']);

            if (! $student) {
                continue;
            }

            $student->id_zoom = $registrant['id'] ?? null;
            $student->join_url = $registrant['join_url'] ?? null;
            $student->save();
            $updated++;
        }

        return $updated;
    }

    private function webinarRegistrants(string $webinarId, string $status = 'approved'): array
    {
        $registrants = [];
        $nextPageToken = null;

        do {
            $params = [
                'page_size' => 300,
                'status' => $status,
            ];

            if ($nextPageToken) {
                $params['next_page_token'] = $nextPageToken;
            }

            $response = $this->zoom->get('webinars/' . $webinarId . '/registrants', $params);

            if ($response->failed()) {
                throw new \RuntimeException($this->responseMessage($response));
            }

            $payload = $response->json() ?: [];
            $registrants = array_merge($registrants, $payload['registrants'] ?? []);
            $nextPageToken = $payload['next_page_token'] ?? null;
        } while ($nextPageToken);

        return $registrants;
    }

    private function responseMessage(Response $response): string
    {
        $payload = $response->json();

        if (is_array($payload) && ! empty($payload['message'])) {
            return 'Zoom respondio: ' . $payload['message'];
        }

        return 'Zoom respondio con estado ' . $response->status() . '.';
    }
}
