<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use App\Models\Project;

class ProjectUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('update', $this->project());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['sometimes', 'required', 'string'],
            'description' => ['sometimes', 'required', 'string'],
            'notes' => ['sometimes', 'nullable', 'string']
        ];
    }

    public function project()
    {
        return Project::findOrFail($this->route('project'));
    }

    public function save()
    {
        return tap($this->project())->update($this->validated());
    }
}
