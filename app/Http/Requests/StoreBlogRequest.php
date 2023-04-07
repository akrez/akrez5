<?php

namespace App\Http\Requests;

use App\Enums\BlogStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBlogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:blogs,name',
            'title' => 'required',
            'slug' => 'required',
            'blog_status' => [Rule::in(BlogStatus::getKeys())],
            'description' => 'required|max:160',
        ];
    }
}
