<?php

namespace App\Http\Livewire\Resources;

use App\Models\Course;
use App\Models\Drive;
use App\Models\User;
use Livewire\Component;

class Show extends Component
{
    const DRIVE_URL = 'https://drive.google.com/drive/folders';

    const LENG = 'Lengua y Literatura';
    const CCNN = 'Ciencias Naturales';
    const CCSS = 'Ciencias Sociales';
    const VOCA = 'Orientación Vocacional';
    const MATH = 'Matemáticas';

    public $imgs_urls = [
        self::LENG => '/storage/img/lengua.jpg',
        self::MATH => '/storage/img/mate.jpg',
        self::CCNN => '/storage/img/naturales.jpg',
        self::CCSS => '/storage/img/sociales.jpg',
        self::VOCA => '/storage/img/orientacion.jpg',
    ];

    public $card_colors = [
        self::LENG => 'bg-gray-200',
        self::MATH => 'bg-yellow-200',
        self::CCNN => 'bg-blue-200',
        self::CCSS => 'bg-pink-200',
        self::VOCA => 'bg-green-200',
    ];

    public $prompt;
    public $user;
    public $drives;

    public $n_modules;

    protected $listener = [
        'refreshParent',
        // 'refreshParent' => '$refresh',
    ];

    public function refreshParent()
    {
        $this->prompt = 'Componente padre refrescado';
    }

    public function mount()
    {
        $this->user = auth()->user();
        // dd($this->user);

    }

    public function changeSubject($old_subject)
    {
        switch ($old_subject) {
            case self::MATH:
                return 'Razonamiento Numérico';
                break;
            case self::LENG:
                return 'Razonamiento Verbal';
                break;
            case self::CCNN:
                return 'Razonamiento Lógico';
                break;
            case self::CCSS:
                return 'Razonamiento Espacial';
                break;
            default:
                return $old_subject;
                break;
        }
    }

    public function construirArrayDrives($drives)
    {
        $new_drives = [];
        for ($i = 0; $i < 5; $i++) {
            $new_drives['Modulo ' . strval($i + 1)] = [];
        }
        foreach ($drives as $key => $drive) {
            array_push($new_drives[$drive->modulo], [
                'materia' => $this->changeSubject($drive->materia),
                'links' => json_decode($drive->links),
                'img_url' => $this->imgs_urls[$drive->materia],
                'card_color' => $this->card_colors[$drive->materia],
            ]);
        }

        return $new_drives;
    }

    public function render()
    {
        $user = auth()->user();
        $student_group_id = $user->student_group->id ?? null;

        if (!$student_group_id) {
            $this->drives = $this->construirArrayDrives(collect());
            $this->n_modules = 0;

            return view('livewire.resources.show');
        }

        $drives = Drive::where('course_id', $student_group_id)
            ->orderBy('modulo')
            ->get()
            ->groupBy('materia')
            ->map(function ($group, $materia) {
                return [
                    'modulo' => $group->first()->modulo,
                    'materia' => $materia,
                    'course_id' => $group->first()->course_id,
                    'links' => $group->pluck('link')->toJson(),
                ];
            })
            ->values();
        // dd($drives);
        $this->drives = $this->construirArrayDrives($drives);

        
        if ($student_group_id == 4)
        {
            $this->n_modules = 2;
        }
        
        return view('livewire.resources.show');
    }
}
