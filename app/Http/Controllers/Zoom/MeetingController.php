<?php

namespace App\Http\Controllers\Zoom;

use App\Http\Controllers\Controller;
use App\Models\Reunion;
use App\Models\StudentGroup;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ZoomJWT;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Gate;


class MeetingController extends Controller
{
    use ZoomJWT;

    const MEETING_TYPE_INSTANT = 1;
    const MEETING_TYPE_SCHEDULE = 2;
    const MEETING_TYPE_RECURRING = 3;
    const MEETING_TYPE_FIXED_RECURRING_FIXED = 8;

    public function list(Request $request)
    {
        $path = 'users/me/meetings';
        $response = $this->zoomGet($path);

        $data = json_decode($response->body(), true);
        $data['meetings'] = array_map(function (&$m) {
            $m['start_at'] = $this->toUnixTimeStamp($m['start_time'], $m['timezone']);
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
        $response = $this->zoomPost($path, [
            'topic' => $data['topic'],
            'type' => self::MEETING_TYPE_SCHEDULE,
            'start_time' => $this->toZoomTimeFormat($data['start_time']),
            'duration' => 30,
            'agenda' => $data['agenda'],
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
        $response = $this->zoomGet($path);

        $data = json_decode($response->body(), true);
        if ($response->ok()) {
            $data['start_at'] = $this->toUnixTimeStamp($data['start_time'], $data['timezone']);
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
        $response = $this->zoomPatch($path, [
            'topic' => $data['topic'],
            'type' => self::MEETING_TYPE_SCHEDULE,
            'start_time' => (new \DateTime($data['start_time']))->format('Y-m-d\TH:i:s'),
            'duration' => 30,
            'agenda' => $data['agenda'],
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
        $response = $this->zoomDelete($path);

        return [
            'success' => $response->status() === 204,
            'data' => json_decode($response->body(), true),
        ];
    }

    public function add_registrant(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
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
            //code...
            // $path = 'meetings/' . $data['meeting_id'] . '/registrants';
            $path = 'webinars/' . $data['meeting_id'] . '/registrants';
            // dd($path);
            $response = $this->zoomPost($path, [
                'email' => $data['email'],
                'first_name' => $data['name'],
                'last_name' => $data['name'],
            ]);
            // dd($response);
            // dd($response->body());

            return view('meetings.registrants-create');
        } catch (\Throwable $th) {
            //throw $th;
            return [
                'failed' => 'Usuario no encontrado',
            ];
        }
    }

    public function change_approval_type($type, $meeting_id)
    {
        try {
            //code...
            $path = 'webinars/' . $meeting_id;

            $response = $this->zoomPatch($path, [
                'settings' => ["approval_type" => $type,],
            ]);
            // dd($response->body());

            return [
                'status' => 'ok',
                'response' => $response
            ];
        } catch (\Throwable $th) {
            //throw $th;
            return [
                'failed' => 'Usuario no encontrado',
            ];
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
        $id_febrero = '93481791337';
        $id_junio = '93946442405';
        $id_julio = '95599131511';

        $registrants_febrero = $this->getWebinarRegistrants($id_febrero, 'pending');
        $registrants_febrero = $this->formatRegistrants($registrants_febrero);
        $this->deny_registrants($id_febrero, $registrants_febrero);

        $registrants_junio = $this->getWebinarRegistrants($id_junio, 'pending');
        $registrants_junio = $this->formatRegistrants($registrants_junio);
        $this->deny_registrants($id_junio, $registrants_junio);

        $registrants_julio = $this->getWebinarRegistrants($id_julio, 'pending');
        $registrants_julio = $this->formatRegistrants($registrants_julio);
        $this->deny_registrants($id_julio, $registrants_julio);

        // dd($registrants_febrero, $registrants_junio, $registrants_julio);
    }

    public function deny_registrants($meeting_number, $registrants)
    {
        try {
            // $path2 = 'webinars/' . $meeting_number;
            // $response2 = $this->zoomGet($path2);
            // dd($response2->json());
            $path = 'webinars/' . $meeting_number . '/registrants/status';
            $response = $this->zoomPut($path, [
                'action' => 'deny',
                'registrants' => $registrants,
                'occurrence_id' => '1623441600000',
            ]);
            // dd(($response->body()));
            return $response;
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
            return [
                'failed' => 'Usuario no encontrado',
            ];
        }
    }

    public function format_response_error_for_print($json_error)
    {
        $msg = $json_error['code'] . '|' . $json_error['message'] . '|';
        foreach ($json_error['errors'] as $error) {
        }
        return;
    }

    public function registrar_estudiantes_zoom(Request $request)
    {
        set_time_limit(0);
        if (!Gate::allows('invitar-estudiantes')) {
            abort(403);
        }
        $std_febrero = $this->getStudentsbyMonth('Febrero');
        $std_junio = $this->getStudentsbyMonth('Junio');
        $std_julio = $this->getStudentsbyMonth('Julio');

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

        $responses = [];

        // foreach ($array1 as $student) {
        //     $resp = $this->registerToWebinarZoom($student);
        //     if ($resp->failed()) {
        //         // dd($resp->body());
        //         if (isset($student->email))
        //             $responses[$student->email] = $resp;
        //         // array_push($responses, $elem);
        //     }
        // }
        // dd($student);

        foreach ($std_febrero as $student) {
            $resp = $this->registerToWebinarZoom($student);
            if ($resp->failed()) {
                if (isset($student->email))
                    $responses[$student->email] = $resp;
            }
        }

        // foreach ($std_junio as $student) {
        //     $resp = $this->registerToWebinarZoom($student);
        //     if ($resp->failed()) {
        //         if (isset($student->email))
        //             $responses[$student->email] = $resp;
        //     }
        // }

        // foreach ($std_julio as $student) {
        //     $resp = $this->registerToWebinarZoom($student);
        //     if ($resp->failed()) {
        //         if (isset($student->email))
        //             $responses[$student->email] = $resp;
        //     }
        // }

        $messages = '';
        // dd($responses);
        foreach ($responses as $email => $resp) {
            // dd($email, $resp->body());
            $messages .= $email . ': ';
            $messages .= $resp->body();
            $messages .= "<br>";
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
        // dd($students->slice(0, 40), $webinar_id);
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
        $students = User::join('role_user', 'users.id', '=', 'role_user.user_id')
            ->where('role_id', '=', 2)
            ->where('status', '=', 1)
            //->where('id_zoom', '=', null)
            // ->select('*')
            // ->select('users.email', 'users.name', 'users.last_name')
            ->get();

        //dd($students);
        return $students;
    }

    function getStudentsbyGroupId($student_group_id)
    {
        $students = User::join('role_user', 'users.id', '=', 'role_user.user_id')
            ->where('role_id', '=', 2)
            ->where('status', '=', 1)
            //->where('enviarCorreo', '=', 'si')
            //->where('id_zoom', '=', null)
            ->where('student_group_id', '=', $student_group_id)
            ->get();
        //dd('total ' . $students);
        var_dump(count($students));
        return $students;
    }

    function registerStudentZoom($student)
    {
        $webinar_id = $student->student_group->webinar_id;
        try {
            $path = 'meetings/' . $webinar_id . '/registrants';

            $response = $this->zoomPost($path, [
                'email' => $student->email,
                'first_name' => $student->name,
            ]);
            // dd(($response->body()));
            return $response;
        } catch (\Throwable $th) {
            //throw $th;
            // dd($th);
            return [
                'failed' => 'Usuario no encontrado',
            ];
        }
    }

    function registerToWebinarZoom($student)
    {
        $webinar_id = $student->student_group->webinar_id;

        try {
            $path = 'webinars/' . $webinar_id . '/registrants';

            $response = $this->zoomPost($path, [
                'email' => $student->email,
                'first_name' => $student->name,
                'last_name' => $student->last_name,
            ]);
            //echo (' -> ' . $student->email);
            //dd(($response->body()));
            return $response;
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
            return [
                'failed' => 'Usuario no encontrado',
            ];
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
            $response = $this->zoomGet($path, $params);
            // dd(($response->json()))
            if (isset($response->json()['next_page_token'])) {

                $next_page_token = $response->json()['next_page_token'];
                $registrants = array_merge($registrants, $response->json()['registrants']);
                while ($next_page_token) {
                    $response = $this->zoomGet($path, [
                        'page_size' => 300,
                        'status' => $status,
                        'next_page_token' => $next_page_token,
                    ]);
                    $next_page_token = $response->json()['next_page_token'];
                    $registrants = array_merge($registrants, $response->json()['registrants']);
                }
            }
            // return $response;
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
            return [
                // 'failed' => 'General error.',
                'message' => $th,
            ];
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
        // dd($registrants);    
        foreach ($registrants as $r) {
            if (!isset($r['email'])) {
                dd($r);
            }
            $found_user = User::where('email', $r['email'])->first();
            //dd('probando: ' . $found_user);
            if ($found_user != null) {
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

            $response = $this->zoomPut($path, [
                'action' => 'approve',
                'registrants' => $registrants,
            ]);
            //dd(($response->body()));
            return $response;
        } catch (\Throwable $th) {
            //throw $th;
            dd('error: ' . $th);
            return [
                'failed' => 'Usuario no encontrado',
            ];
        }
    }

    function getCurrentOccurrenceId($meeting_number)
    {
        try {
            $path = 'webinars/' . $meeting_number;
            $response = $this->zoomGet($path, [
                'show_previous_occurrences' => false,
            ]);
            // dd($response->json());
            if ($response->json()['occurrences']) {
                return $response->json()['occurrences'][0]['occurrence_id'];
            }
            return [];
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
            return [
                'failed' => 'Usuario no encontrado',
            ];
        }
    }
}
