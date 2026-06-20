<?php

namespace App\Http\Livewire\CoursePrograms;

use App\Http\Livewire\Concerns\AuthorizesLivewireActions;
use App\Models\CourseProgram;
use App\Models\CourseProgramTopic;
use App\Models\ResourceFile;
use App\Models\ResourceUrl;
use App\Models\StudentGroup;
use App\Models\TopicResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;



class Main extends Component
{
    use WithFileUploads;
    use AuthorizesLivewireActions;

    protected $listeners = [
        'updateTopic' => 'update_topic',
        'updateOrdering' => 'updateOrdering',
    ];

    public $curr_user;

    public $test;
    public $counter = 0;
    public $selected_course;
    public $courses;
    public $student_groups;
    public $isOpen = 0;
    public $res_type = 'url';

    // formulario recursos
    public $resourceId;
    public $resUrl;
    public $resTitle;
    public $updatingTopicId;
    public $resourceFile = null;
    public $isEditingResource = false;

    public $new_topic_title;
    public $updated_topic_title;

    public $selected_group_id;
    public $selected_course_id;

    // protected $rules = [
    //     // 'resUrl' => 'required|url',
    //     'resTitle' => 'required|',
    //     'resourceFile' => 'required|',
    // ];

    protected $validationAttributes = [
        'resUrl' => 'link',
        'resTitle' => 'título',
        'resourceFile' => 'archivo',

    ];

    private function authorizeCourseProgramRead(): void
    {
        abort_unless(
            auth()->check()
                && (auth()->user()->can('read_course_programs') || auth()->user()->can('crud_course_programs')),
            403
        );
    }

    private function authorizeCourseProgramMutation(): void
    {
        $this->authorizeAbility('crud_course_programs');
    }

    private function authorizeResourceAccess(TopicResource $resource): void
    {
        if ($this->curr_user && $this->curr_user->can('crud_course_programs')) {
            return;
        }

        abort_unless(
            $this->curr_user
                && $this->curr_user->student_group_id
                && (int) $resource->student_groups_id === (int) $this->curr_user->student_group_id,
            403
        );
    }

    private function selectedCourseRelations(): array
    {
        return [
            'topics.resources.resource_url',
            'topics.resources.resource_file',
        ];
    }

    private function loadSelectedCourse($courseId): void
    {
        $this->selected_course = CourseProgram::with($this->selectedCourseRelations())->findOrFail($courseId);
        $this->selected_course_id = $this->selected_course->id;
    }

    public function mount()
    {
        $this->authorizeCourseProgramRead();

        // el usuario se utiliza, por el momento para el manejo de permisos
        $this->curr_user = Auth::user();
        $this->curr_user->loadMissing('student_group');

        // los cursos son las materias que se dictan
        $this->courses = CourseProgram::all();

        // seleccionar el curso que se mostrará por defecto
        if ($this->courses->isNotEmpty()) {
            $this->loadSelectedCourse($this->courses->first()->id);
        }

        $this->student_groups = StudentGroup::valids()->get();

        // establecer el valor delselect con el id del paralelo del estudiante logueado
        $this->setSelectedStudentGroupId();

        $this->filterResources();

        // emitir evento para inicializarr el js que habilita el drag and drop
        // $this->emit('selectHasChanged');
    }

    public function sumar()
    {
        $this->counter++;
    }

    public function resetCourseProgramFields()
    {
        $this->new_topic_title = '';
    }

    public function resetResourceVariables()
    {
        $this->resourceId = null;
        $this->resUrl = null;
        $this->resTitle = '';
        $this->updatingTopicId = '';
    }

    public function addTopic()
    {
        $this->authorizeCourseProgramMutation();

        // dd($this->new_topic_title);
        if ($this->new_topic_title == '') {
            return;
        }
        $topic = new CourseProgramTopic;
        $topic->course_program_id = $this->selected_course->id;
        $topic->topic_title = $this->new_topic_title;
        $topic->save();

        $this->refreshData();
    }

    public function deleteTopic($id_topic)
    {
        $this->authorizeCourseProgramMutation();

        $topic = CourseProgramTopic::findOrFail($id_topic);
        $topic->delete();

        $this->refreshData();
    }

    public function update_topic($data)
    {
        $this->authorizeCourseProgramMutation();

        $topic_obj = CourseProgramTopic::findOrFail($data['topicId']);
        $topic_obj->topic_title = $data['title'];
        // dd($topic_obj);
        $topic_obj->save();

        $this->refreshData();
    }

    public function openModal()
    {
        $this->isOpen = true;
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function closeModal()
    {
        $this->resourceId = null;
        $this->isOpen = false;
    }

    public function prepareToAddResource($id_topic)
    {
        $this->authorizeCourseProgramMutation();

        $this->resetResourceVariables();
        $this->updatingTopicId = $id_topic;
        $this->openModal();
    }

    public function prepareToEditResource($id_resource)
    {
        $this->authorizeCourseProgramMutation();

        $this->isEditingResource = true;

        $resource_model = TopicResource::findOrFail($id_resource);

        $this->resourceId = $resource_model->id;
        $this->resTitle = $resource_model->resource_title;

        if ($resource_model->resource_url) {
            $res_topic_url = ResourceUrl::find($resource_model->resource_url->id);
            $this->resUrl = $res_topic_url->url;
            $this->res_type = 'url';
        } elseif ($resource_model->resource_file) {
            $res_topic_url = ResourceUrl::find($resource_model->resource_file->id);
            $this->resourceFile = null;
            $this->res_type = 'file';
        }

        $this->openModal();
    }

    public function saveResource()
    {
        $this->authorizeCourseProgramMutation();

        if (!$this->isEditingResource) {
            $this->createResource();
        } else {
            $this->updateResource($this->resourceId);
        }

        $this->resetResourceVariables();
        $this->closeModal();
        $this->refreshData();
    }

    public function updateResource($resource_id)
    {
        $this->authorizeCourseProgramMutation();

        $resource = TopicResource::findOrFail($resource_id);
        // dd($this->resourceId);
        $resource->resource_title = $this->resTitle;
        $resource->type = $this->res_type;
        // no se debe permitir actualizar el paralelo del recurso ni el tema al que pertnence el recurso,
        // por eso solo se lo setea en la creacion
        $resource->save();

        if ($resource->resource_url) {
            $resource_url = ResourceUrl::find($resource->resource_url->id);
            $resource_url->topic_resource_id = $resource->id;
            $resource_url->url = $this->resUrl;
            $resource_url->save();
        } elseif ($resource->resource_file) {
            $resource_file = ResourceFile::find($resource->resource_file->id);

            if ($this->resourceFile) {
                // delete previous file
                $current_file_path = $resource_file->path;
                Storage::delete($current_file_path);
                // store new file and path
                $resource_file->path = $this->resourceFile->store('resource_files');
                $resource_file->save();
            }
        }
    }

    public function createResource()
    {
        $this->authorizeCourseProgramMutation();

        $this->validateResourceForm($this->res_type);
        $resource = new TopicResource();
        $resource->topic_id = $this->updatingTopicId;
        $resource->resource_title = $this->resTitle;
        $resource->type = $this->res_type;
        $resource->student_groups_id = $this->selected_group_id; // c/recurso corresponde a un solo paralelo
        $resource->order = 9999; // valor para que aparezca al final del listado de recursos
        $resource->save();

        if ($this->res_type == 'url') {
            $resource_url = new ResourceUrl();
            $resource_url->topic_resource_id = $resource->id;
            $resource_url->url = $this->resUrl;
            $resource_url->save();
        } elseif ($this->res_type == 'file') {
            $resource_file = new ResourceFile();
            $resource_file->topic_resource_id = $resource->id;
            $resource_file->path = $this->resourceFile->store('resource_files');
            $resource_file->save();
            // $this->resourceFile->store('resource_files');
        }
    }

    public function downloadResourceFile($id_resource)
    {
        $this->authorizeCourseProgramRead();

        $download_resourceFile = ResourceFile::findOrFail($id_resource);
        $resource = TopicResource::findOrFail($download_resourceFile->topic_resource_id);
        $this->authorizeResourceAccess($resource);

        return response()->download(storage_path('app/' . $download_resourceFile->path));
    }

    public function validateResourceForm($resource_type)
    {
        $this->validate([
            'resTitle' => 'required',
        ]);
        if ($resource_type == 'url') {

            $this->validate([
                'resUrl' => 'required|url',
            ]);
        }
        if ($resource_type == 'file') {
            $this->validate([
                'resourceFile' => 'required|file',
            ]);
        }
    }

    public function courseChange()
    {
        $this->selected_course_id = intval($this->selected_course_id);
        $this->setSelectedStudentGroupId();
        $this->refreshData();

        // emitir evento para que se vuelva a ejecutar el js que habilita el drag and drop
        $this->dispatch('selectHasChanged');
    }

    // el estudiante no debe tener acceso a la info de otros paralelos.
    function setSelectedStudentGroupId()
    {
        if ($this->curr_user->cannot('crud_course_programs')) {
            $this->curr_user->loadMissing('student_group');
            abort_unless($this->curr_user->student_group, 403);
            $this->selected_group_id = $this->curr_user->student_group->id;
        } else {
            $firstGroup = $this->student_groups->first();
            $this->selected_group_id = $firstGroup ? $firstGroup->id : null;
        }
    }

    public function resChange()
    {
    }

    public function studentGroupChange()
    {
        $this->authorizeCourseProgramMutation();

        $this->selected_group_id = intval($this->selected_group_id);

        $this->refreshData();

        // emitir evento para que se vuelva a ejecutar el js que habilita el drag and drop
        $this->dispatch('selectHasChanged');
    }

    public function filterResources()
    {
        if (!$this->selected_course || !$this->selected_group_id) {
            return;
        }

        // filtrar recursos de paralelo especifico
        foreach ($this->selected_course->topics as $topic) {
            // dd($topic->resources);
            $topic->setRelation('resources', $topic->resources
                ->filter(function ($resc) {
                    return $resc->student_groups_id == $this->selected_group_id;
                })
                ->sortBy('order')
                ->values());
        }
    }

    public function refreshData()
    {
        $courseId = $this->selected_course_id ?: ($this->selected_course ? $this->selected_course->id : null);

        if (!$courseId) {
            return;
        }

        $this->loadSelectedCourse($courseId);
        // dd($this->selected_course);
        $this->filterResources();
    }

    public function deleteResource($resource_id)
    {
        $this->authorizeCourseProgramMutation();

        $resource_model = TopicResource::findOrFail($resource_id);
        if ($resource_model->resource_url) {
        } elseif ($resource_model->resource_file) {
            Storage::delete($resource_model->resource_file->path);
        }

        $resource_model->delete();

        $this->refreshData();
    }

    public function updateOrdering($data)
    {
        $this->authorizeCourseProgramMutation();

        foreach ($data['orders'] as $order) {
            $res = TopicResource::findOrFail($order['resourceId']);
            $res->order = $order['order'];
            $res->save();
        }
        $this->refreshData();
    }

    public function render()
    {
        $this->authorizeCourseProgramRead();

        $this->refreshData();
        return view('livewire.course-programs.main');
    }
}
