<?php

namespace Different\DifferentCore\app\Http\Requests\Crud\Post;

use Illuminate\Foundation\Http\FormRequest;

class PostUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $postModel = config('backpack.permissionmanager.models.post');
        $postModel = new $postModel();
        $routeSegmentWithId = empty(config('backpack.base.route_prefix')) ? '2' : '3';

        $postId = $this->get('id') ?? \Request::instance()->segment($routeSegmentWithId);

        if (! $postModel->find($postId)) {
            abort(400, 'Could not find that entry in the database.');
        }

        return [
            'title' => ['required', 'string'],
            'slug' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
        ];
    }
}
