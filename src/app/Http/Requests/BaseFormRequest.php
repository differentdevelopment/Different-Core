<?php

namespace Different\DifferentCore\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class BaseFormRequest extends FormRequest
{

    protected $rules;

    public function attributes()
    {
        $attributes = [];
        foreach($this->rules as $key => $values)
        {
            $attributes[$key] = __($key);
        }
        return array_merge(parent::attributes(), $attributes);
    }

}
