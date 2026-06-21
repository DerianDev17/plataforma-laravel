<?php

namespace App\Http\Controllers\Zoom;

use App\Http\Controllers\Controller;
use App\Models\Reunion;
use App\Models\StudentGroup;
use App\Models\User;
use App\Services\Zoom\ZoomOAuthClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;


class MeetingController extends Controller
{
    const MEETING_TYPE_INSTANT = 1;
    const MEETING_TYPE_SCHEDULE = 2;
    const MEETING_TYPE_RECURRING = 3;
    const MEETING_TYPE_FIXED_RECURRING_FIXED = 8;

    public function __construct(private ZoomOAuthClient $zoom) {}

    public function list(Request $request)
    {
        $path = 'users/me/meetings';
        $response = $this->zoom->get($path);

        $data = json_decode($response->body(), true);
        $data['meetings'] = array_map(function (&$m) {
            $m['start_at'] = $this->zoom->toUnixTimeStamp($m['start_time'], $m['timezone']);
            return $m;
        }, $data['meetings']);

        return [
            'success' => $response->ok(),
            'data' => $data,
        ];
    }

    public function create(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'topic' => 'required|string',
            'start_time' => 'required|date',
            'agenda' => 'string|nullable',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }
        $data = $validator->validated();

        $path = 'users/me/meetings';
        $response = $this->zoom->post($path, [
            'topic' => $data['topic'],
            'type' => self::MEETING_TYPE_SCHEDULE,
            'start_time' => $this->zoom->toZoomTimeFormat($data['start_time']),
            'duration' => 30,
            'agenda' => $data['agenda'] ?? '',
            'settings' => [
                'host_video' => false,
                'participant_video' => false,
                'waiting_room' => true,
            ]
        ]);


        return [
            'success' => $response->status() === 201,
            'data' => json_decode($response->body(), true),
        ];
    }

    public function get(Request $request, string $id)
    {
        $path = 'meetings/' . $id;
        $response = $this->zoom->get($path);

        $data = json_decode($response->body(), true);
        if ($response->ok()) {
            $data['start_at'] = $this->zoom->toUnixTimeStamp($data['start_time'], $data['timezone']);
        }

        return [
            'success' => $response->ok(),
            'data' => $data,
        ];
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'topic' => 'required|string',
            'start_time' => 'required|date',
            'agenda' => 'string|nullable',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }
        $data = $validator->validated();

        $path = 'meetings/' . $id;
        $response = $this->zoom->patch($path, [
            'topic' => $data['topic'],
            'type' => self::MEETING_TYPE_SCHEDULE,
            'start_time' => (new \DateTime($data['start_time']))->format('Y-m-d\TH:i:s'),
            'duration' => 30,
            'agenda' => $data['agenda'] ?? '',
            'settings' => [
                'host_video' => false,
                'participant_video' => false,
                'waiting_room' => true,
            ]
        ]);

        return [
            'success' => $response->status() === 204,
            'data' => json_decode($response->body(), true),
        ];
    }

    public function delete(Request $request, string $id)
    {
        $path = 'meetings/' . $id;
        $response = $this->zoom->delete($path);

        return [
            'success' => $response->status() === 204,
            'data' => json_decode($response->body(), true),
        ];
    }

    public function add_registrant(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email:rfc|not_regex:/[\r\n]/',
            'name' => 'required|string',
            'meeting_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }

        $data = $validator->validated();

        try {
            $path = 'webinars/' . $data['meeting_id'] . '/registrants';
            $this->zoom->post($path, [
                'email' => $data['email'],
                'first_name' => $data['name'],
                'last_name' => $data['name'],
            ]);

            return view('meetings.registrants-create');
        } catch (\Throwable $th) {
            return [
                'failed' => 'Usuario no encontrado',
            ];
        }
    }

    public function change_approval_type($type, $meeting_id)
    {
        try {
            $path = 'webinars/' . $meeting_id;
            $response = $this->zoom->patch($path, [
                'settings' => ['approval_type' => $type],
            ]);

            return ['status' => 'ok', 'response' => $response];
        } catch (\Throwable $th) {
            return ['failed' => 'Usuario no encontrado'];
        }
    }

    public function formatRegistrants($registrants)
    {
        return array_map(function ($registrant) {
            return [
                'id' => $registrant['id'],
                'email' => $registrant['email']
            ];
        }, $registrants);
    }

    public function deny_all_registrants()
    {
        $ids = ['93481791337', '93946442405', '95599131511'];

        foreach ($ids as $id) {
            $registrants = $this->getWebinarRegistrants($id, 'pending');
            $this->deny_registrants($id, $this->formatRegistrants($registrants));
        }
    }

    public function deny_registrants($meeting_number, $registrants)
    {
        try {
            $path = 'webinars/' . $meeting_number . '/registrants/status';
            return $this->zoom->put($path, [
                'action' => 'deny',
                'registrants' => $registrants,
                'occurrence_id' => '1623441600000',
            ]);
        } catch (\Throwable $th) {
            report($th);
            return ['failed' => 'Usuario no encontrado'];
        }
    }

    public function format_response_error_for_print($json_error)
    {
        return $json_error['code'] . '|' . $json_error['message'];
    }

    public function registrar_estudiantes_zoom(Request $request)
    {
        set_time_limit(0);
        if (!Gate::allows('invitar-estudiantes')) {
            abort(403);
        }
        $responses = [];

        foreach ($this->getStudentsbyMonth('Febrero') as $student) {
            $resp = $this->registerToWebinarZoom($student);
            if ($resp->failed() && isset($student->email)) {
                $responses[$student->email] = $resp;
            }
        }

        $messages = '';
        foreach ($responses as $email => $resp) {
            $messages .= $email . ': ' . $resp->body() . '<br>';
        }

        return $messages;
    }

    public function registro_participantes()
    {
        //Obtener Student groups
        $groups = StudentGroup::all();
        //pasar sutudents_groups a la vista
        return view('meetings.registrants-participante', [
            'student_groups' => $groups
        ]);
    }

    public function add_participantes(Request $request)
    {
        $students = $this->getStudentsbyGroupId($request->group_id);
        $webinar_id = StudentGroup::find($request->group_id)->webinar_id;
        $this->register_students_webinar($students, $webinar_id);

        return back();
    }

    public function register_students_webinar($students, $webinar_id)
    {
        $this->change_approval_type(0, $webinar_id);
        foreach ($students as $student) {
            $resp = $this->registerToWebinarZoom($student);
        }
        $this->change_approval_type(1, $webinar_id);
    }

    public function create_registrant()
    {
        return view('meetings.registrants-create');
    }

    function getStudentsbyMonth($mes)
    {
        return User::whereHas('roles', function ($q) {
                $q->where('role_id', 2);
            })
            ->with('student_group')
            ->where('status', '=', 1)
            ->where('exam_month', $mes)
            ->get();
    }

    function getStudentsbyGroupId($student_group_id)
    {
        return User::whereHas('roles', function ($q) {
                $q->where('role_id', 2);
            })
            ->with('student_group')
            ->where('status', '=', 1)
            ->where('student_group_id', '=', $student_group_id)
            ->get();
    }

    function registerToWebinarZoom($student)
    {
        $webinar_id = $student->student_group->webinar_id;

        try {
            $path = 'webinars/' . $webinar_id . '/registrants';

            return $this->zoom->post($path, [
                'email' => $student->email,
                'first_name' => $student->name,
                'last_name' => $student->last_name,
            ]);
        } catch (\Throwable $th) {
            report($th);
            return ['failed' => 'Usuario no encontrado'];
        }
    }

    function getWebinarRegistrants($webinarId, $status = 'approved')
    {
        $registrants = [];
        $path = 'webinars/' . $webinarId . '/registrants';
        $response = null;
        $params = [
            'page_size' => 300,
            'status' => $status
        ];
        try {
            $response = $this->zoom->get($path, $params);

            $registrants = $response->json()['registrants'] ?? [];

            if (isset($response->json()['next_page_token'])) {
                $next_page_token = $response->json()['next_page_token'];

                while ($next_page_token) {
                    $response = $this->zoom->get($path, [
                        'page_size' => 300,
                        'status' => $status,
                        'next_page_token' => $next_page_token,
                    ]);
                    $next_page_token = $response->json()['next_page_token'] ?? null;
                    $registrants = array_merge($registrants, $response->json()['registrants'] ?? []);
                }
            }
        } catch (\Throwable $th) {
            report($th);
            return ['message' => $th];
        }

        return $registrants;
    }

    public function set_zoom_ids()
    {
        User::students()->update([
            'id_zoom' => null,
            'join_url' => null,
        ]);
        $std_groups = StudentGroup::valids()->get();
        foreach ($std_groups as $group) {
            $this->set_zoom_fields($group->webinar_id);
        }
    }

    public function set_zoom_fields($meetin_id)
    {
        $registrants = $this->getWebinarRegistrants($meetin_id);
        $emails = array_column($registrants, 'email');
        $emails = array_filter($emails);

        if (empty($emails)) return;

        $users = User::whereIn('email', $emails)->get()->keyBy('email');

        foreach ($registrants as $r) {
            if (!isset($r['email'])) continue;

            $found_user = $users->get($r['email']);
            if ($found_user) {
                $found_user->id_zoom = $r['id'];
                $found_user->join_url = $r['join_url'];
                $found_user->save();
            }
        }
    }

    function approveStudents($meeting_number, $registrants)
    {
        try {
            $occurrence_id = $this->getCurrentOccurrenceId($meeting_number);
            $path = 'webinars/' . $meeting_number . '/registrants/status?occurrence_id=' . $occurrence_id;

            return $this->zoom->put($path, [
                'action' => 'approve',
                'registrants' => $registrants,
            ]);
        } catch (\Throwable $th) {
            report($th);
            return ['failed' => 'Usuario no encontrado'];
        }
    }

    function getCurrentOccurrenceId($meeting_number)
    {
        try {
            $path = 'webinars/' . $meeting_number;
            $response = $this->zoom->get($path, ['show_previous_occurrences' => false]);

            if ($response->json()['occurrences']) {
                return $response->json()['occurrences'][0]['occurrence_id'];
            }

            return [];
        } catch (\Throwable $th) {
            report($th);
            return ['failed' => 'Usuario no encontrado'];
        }
    }
}
